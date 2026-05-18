<?php
$titulo = 'Escalas';
require_once 'header.php';
$pdo = db();
$mensagem = '';
$detalhesGeracao = [];

function buscar_equipe_padrao(PDO $pdo): ?int {
    $equipeId = $pdo->query('SELECT id FROM equipes ORDER BY id LIMIT 1')->fetchColumn();
    return $equipeId ? (int)$equipeId : null;
}

function funcionario_tem_folga(PDO $pdo, int $funcionarioId, string $data): bool {
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM folgas
        WHERE funcionario_id = ?
          AND status = 'aprovado'
          AND ? BETWEEN data_inicio AND data_fim
    ");
    $stmt->execute([$funcionarioId, $data]);
    return (int)$stmt->fetchColumn() > 0;
}

function gerar_escalas_automaticas(PDO $pdo, string $dataInicio, string $dataFim, array $turnosSelecionados, bool $incluirDomingo, array $funcionariosSelecionados = []): array {
    $inicio = DateTime::createFromFormat('Y-m-d', $dataInicio);
    $fim = DateTime::createFromFormat('Y-m-d', $dataFim);

    if (!$inicio || !$fim || $inicio > $fim) {
        throw new Exception('Informe um periodo valido para gerar as escalas.');
    }

    if (!$turnosSelecionados) {
        throw new Exception('Selecione pelo menos um turno.');
    }

    if ($funcionariosSelecionados) {
        $placeholdersFuncionarios = implode(',', array_fill(0, count($funcionariosSelecionados), '?'));
        $stmtFuncionarios = $pdo->prepare("SELECT id, nome FROM funcionarios WHERE ativo = 1 AND id IN ($placeholdersFuncionarios) ORDER BY nome");
        $stmtFuncionarios->execute($funcionariosSelecionados);
        $funcionarios = $stmtFuncionarios->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $funcionarios = $pdo->query('SELECT id, nome FROM funcionarios WHERE ativo = 1 ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
    }

    if (!$funcionarios) {
        throw new Exception('Cadastre funcionarios ativos antes de gerar escalas.');
    }

    $placeholders = implode(',', array_fill(0, count($turnosSelecionados), '?'));
    $stmtTurnos = $pdo->prepare("SELECT id, nome FROM turnos WHERE id IN ($placeholders) ORDER BY id");
    $stmtTurnos->execute($turnosSelecionados);
    $turnos = $stmtTurnos->fetchAll(PDO::FETCH_ASSOC);

    if (!$turnos) {
        throw new Exception('Nenhum turno valido foi encontrado.');
    }

    $stmtExistentes = $pdo->prepare("
        SELECT funcionario_id, COUNT(*) total
        FROM escalas
        WHERE data_escala BETWEEN ? AND ?
        GROUP BY funcionario_id
    ");
    $stmtExistentes->execute([$dataInicio, $dataFim]);
    $contagem = [];
    foreach ($funcionarios as $funcionario) {
        $contagem[(int)$funcionario['id']] = 0;
    }
    foreach ($stmtExistentes->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $contagem[(int)$linha['funcionario_id']] = (int)$linha['total'];
    }

    $stmtJaEscalado = $pdo->prepare('SELECT COUNT(*) FROM escalas WHERE funcionario_id = ? AND data_escala = ?');
    $stmtTurnoJaCoberto = $pdo->prepare('SELECT COUNT(*) FROM escalas WHERE turno_id = ? AND data_escala = ?');
    $stmtInserir = $pdo->prepare('
        INSERT INTO escalas (funcionario_id, equipe_id, turno_id, data_escala, tipo, observacoes)
        VALUES (?, ?, ?, ?, "normal", ?)
    ');

    $equipeId = buscar_equipe_padrao($pdo);
    $criados = 0;
    $ignorados = 0;
    $detalhes = [];

    $pdo->beginTransaction();
    try {
        $dataAtual = clone $inicio;

        while ($dataAtual <= $fim) {
            $dataSql = $dataAtual->format('Y-m-d');
            $diaSemana = (int)$dataAtual->format('w');

            if ($diaSemana === 0 && !$incluirDomingo) {
                $dataAtual->modify('+1 day');
                continue;
            }

            foreach ($turnos as $turno) {
                $stmtTurnoJaCoberto->execute([(int)$turno['id'], $dataSql]);
                if ((int)$stmtTurnoJaCoberto->fetchColumn() > 0) {
                    $ignorados++;
                    $detalhes[] = "$dataSql - {$turno['nome']}: turno ja possui escala.";
                    continue;
                }

                $candidatos = $funcionarios;
                usort($candidatos, function ($a, $b) use ($contagem) {
                    $totalA = $contagem[(int)$a['id']] ?? 0;
                    $totalB = $contagem[(int)$b['id']] ?? 0;

                    if ($totalA === $totalB) {
                        return strcmp($a['nome'], $b['nome']);
                    }

                    return $totalA <=> $totalB;
                });

                $escalado = null;
                foreach ($candidatos as $funcionario) {
                    $funcionarioId = (int)$funcionario['id'];
                    $stmtJaEscalado->execute([$funcionarioId, $dataSql]);

                    if ((int)$stmtJaEscalado->fetchColumn() > 0) {
                        continue;
                    }

                    if (funcionario_tem_folga($pdo, $funcionarioId, $dataSql)) {
                        continue;
                    }

                    $escalado = $funcionario;
                    break;
                }

                if (!$escalado) {
                    $ignorados++;
                    $detalhes[] = "$dataSql - {$turno['nome']}: sem funcionario disponivel.";
                    continue;
                }

                $stmtInserir->execute([
                    (int)$escalado['id'],
                    $equipeId,
                    (int)$turno['id'],
                    $dataSql,
                    'Gerada automaticamente'
                ]);

                $contagem[(int)$escalado['id']]++;
                $criados++;
            }

            $dataAtual->modify('+1 day');
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

    return [
        'criados' => $criados,
        'ignorados' => $ignorados,
        'detalhes' => $detalhes
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (($_POST['acao'] ?? '') === 'gerar_automatico') {
            $resultado = gerar_escalas_automaticas(
                $pdo,
                $_POST['data_inicio'] ?? '',
                $_POST['data_fim'] ?? '',
                array_map('intval', $_POST['turnos'] ?? []),
                isset($_POST['incluir_domingo']),
                array_map('intval', $_POST['funcionarios_selecionados'] ?? [])
            );

            $mensagem = "Escalas automaticas geradas: {$resultado['criados']} cadastro(s).";
            if ($resultado['ignorados'] > 0) {
                $mensagem .= " {$resultado['ignorados']} turno(s) ficaram sem funcionario disponivel.";
                $detalhesGeracao = $resultado['detalhes'];
            }
        } else {
            $stmt = $pdo->prepare('INSERT INTO escalas (funcionario_id, equipe_id, turno_id, data_escala, tipo, observacoes) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$_POST['funcionario_id'], buscar_equipe_padrao($pdo), $_POST['turno_id'], $_POST['data_escala'], $_POST['tipo'], $_POST['observacoes']]);
            $mensagem = 'Escala cadastrada.';
        }
    } catch (Exception $e) {
        $mensagem = 'Nao foi possivel processar a escala. ' . $e->getMessage();
    }
}
$funcionarios = $pdo->query('SELECT id, nome, cargo FROM funcionarios WHERE ativo = 1 ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$turnos = $pdo->query('SELECT id, nome FROM turnos ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$mesReferencia = $_GET['mes'] ?? (isset($_POST['data_inicio']) ? substr($_POST['data_inicio'], 0, 7) : date('Y-m'));
if (!preg_match('/^\d{4}-\d{2}$/', $mesReferencia)) {
    $mesReferencia = date('Y-m');
}

$primeiroDia = DateTime::createFromFormat('Y-m-d', $mesReferencia . '-01');
$inicioMes = $primeiroDia->format('Y-m-01');
$fimMes = $primeiroDia->format('Y-m-t');
$mesAnterior = (clone $primeiroDia)->modify('-1 month')->format('Y-m');
$proximoMes = (clone $primeiroDia)->modify('+1 month')->format('Y-m');
$nomesMeses = [
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Marco',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
];
$tituloMes = $nomesMeses[(int)$primeiroDia->format('n')] . ' ' . $primeiroDia->format('Y');

$stmtEscalasMes = $pdo->prepare('
    SELECT e.data_escala, f.nome funcionario, t.nome turno, e.tipo, e.observacoes
    FROM escalas e
    JOIN funcionarios f ON f.id = e.funcionario_id
    JOIN turnos t ON t.id = e.turno_id
    WHERE e.data_escala BETWEEN ? AND ?
      AND f.ativo = 1
    ORDER BY e.data_escala, t.hora_inicio, f.nome
');
$stmtEscalasMes->execute([$inicioMes, $fimMes]);
$escalasMes = $stmtEscalasMes->fetchAll(PDO::FETCH_ASSOC);
$escalasPorData = [];
foreach ($escalasMes as $escala) {
    $escalasPorData[$escala['data_escala']][] = $escala;
}

$escalas = $pdo->query('SELECT e.data_escala, f.nome funcionario, t.nome turno, e.tipo, e.observacoes FROM escalas e JOIN funcionarios f ON f.id = e.funcionario_id JOIN turnos t ON t.id = e.turno_id WHERE f.ativo = 1 ORDER BY e.data_escala DESC LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="schedule-hero">
    <div>
        <span>Configuracoes /</span>
        <h1>Escala de trabalho / Sobreaviso</h1>
    </div>
    <a class="btn" href="ponto.php">Bater ponto</a>
</div>
<?php if ($mensagem): ?><div class="alert sucesso"><?= h($mensagem) ?></div><?php endif; ?>
<?php if ($detalhesGeracao): ?>
    <div class="alert erro">
        <?php foreach ($detalhesGeracao as $detalhe): ?>
            <div><?= h($detalhe) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="post" class="schedule-workspace">
    <input type="hidden" name="acao" value="gerar_automatico">

    <aside class="employee-panel">
        <div class="employee-panel-header">
            <h2>Escala de Trabalho</h2>
            <a href="escalas.php?mes=<?= h($mesReferencia) ?>">Visualizar escala</a>
        </div>
        <label class="search-label">
            <span>Nome</span>
            <input type="search" placeholder="Pesquisar funcionario">
        </label>
        <label class="employee-check select-all">
            <input type="checkbox" checked onclick="document.querySelectorAll('.employee-check input[name=&quot;funcionarios_selecionados[]&quot;]').forEach(item => item.checked = this.checked)">
            <strong>Selecionar todos</strong>
        </label>
        <div class="employee-list">
            <?php foreach ($funcionarios as $f): ?>
                <label class="employee-check">
                    <input type="checkbox" name="funcionarios_selecionados[]" value="<?= $f['id'] ?>" checked>
                    <span>
                        <?= h($f['nome']) ?>
                        <small><?= h($f['cargo'] ?? 'Turno regular') ?></small>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>
    </aside>

    <section class="calendar-panel">
        <div class="movement-tabs">
            <button type="button" class="tab ativo">Movimentacao de turno</button>
            <button type="button" class="tab">Sobreaviso</button>
        </div>

        <div class="movement-bar">
            <label>
                <span>Movimentar selecionados para o turno</span>
                <select name="turnos[]" multiple required size="3">
                    <?php foreach ($turnos as $t): ?>
                        <option value="<?= $t['id'] ?>" selected><?= h($t['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>Data inicial</span>
                <input type="date" name="data_inicio" value="<?= h($inicioMes) ?>" required>
            </label>
            <label>
                <span>Data final</span>
                <input type="date" name="data_fim" value="<?= h($fimMes) ?>" required>
            </label>
            <button type="button" class="btn ghost">Simular</button>
            <button class="btn apply">Aplicar</button>
        </div>

        <div class="calendar-toolbar">
            <div>
                <strong><?= h($tituloMes) ?></strong>
                <span>Calendario mensal de escalas</span>
            </div>
            <div class="calendar-actions">
                <a href="escalas.php?mes=<?= h($mesAnterior) ?>">‹</a>
                <a href="escalas.php?mes=<?= h(date('Y-m')) ?>">Hoje</a>
                <a href="escalas.php?mes=<?= h($proximoMes) ?>">›</a>
            </div>
            <label class="mini-check"><input type="checkbox" checked> Turno</label>
            <label class="mini-check"><input type="checkbox" checked> Feriado</label>
            <label class="mini-check"><input type="checkbox" name="incluir_domingo"> Sobreaviso</label>
        </div>

        <div class="calendar-grid">
            <?php foreach (['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'] as $diaNome): ?>
                <div class="calendar-head"><?= h($diaNome) ?></div>
            <?php endforeach; ?>

            <?php
            $espacosAntes = (int)$primeiroDia->format('w');
            $diasNoMes = (int)$primeiroDia->format('t');
            $totalCelulas = (int)(ceil(($espacosAntes + $diasNoMes) / 7) * 7);
            for ($i = 0; $i < $totalCelulas; $i++):
                $numeroDia = $i - $espacosAntes + 1;
                $dentroDoMes = $numeroDia >= 1 && $numeroDia <= $diasNoMes;
                $dataCelula = $dentroDoMes ? sprintf('%s-%02d', $mesReferencia, $numeroDia) : '';
                $itensDia = $dataCelula ? ($escalasPorData[$dataCelula] ?? []) : [];
            ?>
                <div class="calendar-day <?= $dentroDoMes ? '' : 'muted-day' ?>">
                    <?php if ($dentroDoMes): ?>
                        <strong><?= $numeroDia ?></strong>
                        <?php foreach ($itensDia as $item): ?>
                            <div class="shift-pill tipo-<?= h($item['tipo']) ?>">
                                <span><?= h($item['turno']) ?></span>
                                <?= h($item['funcionario']) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </section>
</form>

<section class="panel">
    <h2>Nova escala</h2>
    <form method="post" class="grid-form">
        <input type="hidden" name="acao" value="manual">
        <select name="funcionario_id" required><?php foreach ($funcionarios as $f): ?><option value="<?= $f['id'] ?>"><?= h($f['nome']) ?></option><?php endforeach; ?></select>
        <select name="turno_id" required><?php foreach ($turnos as $t): ?><option value="<?= $t['id'] ?>"><?= h($t['nome']) ?></option><?php endforeach; ?></select>
        <input type="date" name="data_escala" required>
        <select name="tipo"><option>normal</option><option>plantao</option><option>remoto</option><option>folga</option></select>
        <input name="observacoes" placeholder="Observacoes">
        <button>Cadastrar escala</button>
    </form>
</section>
<section class="panel">
    <h2>Ultimas escalas cadastradas</h2>
    <table>
        <tr><th>Data</th><th>Funcionario</th><th>Turno</th><th>Tipo</th><th>Obs.</th></tr>
        <?php foreach ($escalas as $e): ?>
            <tr><td><?= h($e['data_escala']) ?></td><td><?= h($e['funcionario']) ?></td><td><?= h($e['turno']) ?></td><td><?= h($e['tipo']) ?></td><td><?= h($e['observacoes'] ?? '-') ?></td></tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once 'footer.php'; ?>

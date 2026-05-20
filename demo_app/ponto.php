<?php
$titulo = 'Relogio de Ponto';
require_once 'header.php';
$pdo = db();
$mensagem = '';
$funcionarioId = (int)($_POST['funcionario_id'] ?? $_GET['funcionario_id'] ?? 1);
$acao = $_POST['acao'] ?? '';
$funcionarioLogadoId = usuario_funcionario_id();

if (!usuario_admin() && $funcionarioLogadoId) {
    $funcionarioId = $funcionarioLogadoId;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $acao) {
    $hoje = data_hoje_sql();
    $agora = agora_sql();

    $stmt = $pdo->prepare('SELECT * FROM registros_ponto WHERE funcionario_id = ? AND data_ponto = ?');
    $stmt->execute([$funcionarioId, $hoje]);
    $ponto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ponto) {
        $pdo->prepare('INSERT INTO registros_ponto (funcionario_id, data_ponto) VALUES (?, ?)')->execute([$funcionarioId, $hoje]);
    }

    $colunas = [
        'entrada' => 'entrada',
        'inicio_intervalo' => 'inicio_intervalo',
        'fim_intervalo' => 'fim_intervalo',
        'saida' => 'saida'
    ];

    if (isset($colunas[$acao])) {
        $coluna = $colunas[$acao];
        $status = $coluna === 'saida' ? 'fechado' : 'aberto';
        $stmt = $pdo->prepare("UPDATE registros_ponto SET $coluna = ?, status = ? WHERE funcionario_id = ? AND data_ponto = ? AND $coluna IS NULL");
        $stmt->execute([$agora, $status, $funcionarioId, $hoje]);
        $mensagem = $stmt->rowCount() ? 'Registro realizado com sucesso.' : 'Este ponto ja foi registrado hoje.';
    }
}

$funcionarios = $pdo->query('SELECT id, nome FROM funcionarios WHERE ativo = 1 ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
$idsFuncionarios = array_map(fn($funcionario) => (int)$funcionario['id'], $funcionarios);
if ($funcionarios && !in_array($funcionarioId, $idsFuncionarios, true)) {
    $funcionarioId = (int)$funcionarios[0]['id'];
}

$stmt = $pdo->prepare('SELECT * FROM registros_ponto WHERE funcionario_id = ? AND data_ponto = ?');
$stmt->execute([$funcionarioId, data_hoje_sql()]);
$pontoHoje = $stmt->fetch(PDO::FETCH_ASSOC);

$funcionarioAtual = null;
foreach ($funcionarios as $funcionario) {
    if ((int)$funcionario['id'] === $funcionarioId) {
        $funcionarioAtual = $funcionario;
        break;
    }
}

$dataReferenciaTexto = $_GET['data'] ?? data_hoje_sql();
$dataReferencia = DateTime::createFromFormat('Y-m-d', $dataReferenciaTexto) ?: new DateTime();
$inicioSemana = (clone $dataReferencia)->modify('monday this week');
$fimSemana = (clone $inicioSemana)->modify('+6 days');
$semanaAnterior = (clone $inicioSemana)->modify('-7 days')->format('Y-m-d');
$proximaSemana = (clone $inicioSemana)->modify('+7 days')->format('Y-m-d');
$stmtSemana = $pdo->prepare('
    SELECT data_ponto, entrada, inicio_intervalo, fim_intervalo, saida, horas_trabalhadas, status
    FROM registros_ponto
    WHERE funcionario_id = ? AND data_ponto BETWEEN ? AND ?
    ORDER BY data_ponto
');
$stmtSemana->execute([$funcionarioId, $inicioSemana->format('Y-m-d'), $fimSemana->format('Y-m-d')]);
$pontosSemana = [];
foreach ($stmtSemana->fetchAll(PDO::FETCH_ASSOC) as $registro) {
    $pontosSemana[$registro['data_ponto']] = $registro;
}

function hora_curta(?string $valor): string {
    return $valor ? date('H:i', strtotime($valor)) : '-';
}

$diasSemana = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'];
?>
<div class="punch-hero">
    <div>
        <span>Pontos / Jornada semanal</span>
        <h1>Super Punch</h1>
    </div>
    <a class="btn" href="relatorios.php?mes=<?= h($dataReferencia->format('Y-m')) ?>&funcionario_id=<?= $funcionarioId ?>">Folha de Frequencia</a>
</div>

<?php if ($mensagem): ?>
    <div class="alert sucesso"><?= h($mensagem) ?></div>
<?php endif; ?>

<section class="punch-workspace">
    <div class="punch-main panel">
        <div class="punch-filters">
            <?php if (usuario_admin()): ?>
                <label>
                    <span>Colaborador</span>
                    <select onchange="location.href='ponto.php?funcionario_id=' + this.value + '&data=<?= h($dataReferencia->format('Y-m-d')) ?>'">
                        <?php foreach ($funcionarios as $f): ?>
                            <option value="<?= $f['id'] ?>" <?= $funcionarioId === (int)$f['id'] ? 'selected' : '' ?>><?= h($f['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            <?php else: ?>
                <div class="readonly-filter">
                    <span>Colaborador</span>
                    <strong><?= h($funcionarioAtual['nome'] ?? 'Funcionario') ?></strong>
                </div>
            <?php endif; ?>
            <form method="get" class="date-jump-form">
                <input type="hidden" name="funcionario_id" value="<?= $funcionarioId ?>">
                <a class="btn ghost" href="ponto.php?funcionario_id=<?= $funcionarioId ?>&data=<?= h($semanaAnterior) ?>">Semana anterior</a>
                <label>
                    <span>Escolher data</span>
                    <input type="date" name="data" value="<?= h($dataReferencia->format('Y-m-d')) ?>" onchange="this.form.submit()">
                </label>
                <a class="btn ghost" href="ponto.php?funcionario_id=<?= $funcionarioId ?>&data=<?= h($proximaSemana) ?>">Proxima semana</a>
                <div class="week-range">
                    <?= h($inicioSemana->format('d/m/Y')) ?> - <?= h($fimSemana->format('d/m/Y')) ?>
                </div>
            </form>
        </div>

        <table class="punch-table">
            <tr>
                <th>Data</th>
                <th>Jornada</th>
                <th>Planejado</th>
                <th>Entrada</th>
                <th>Inicio int.</th>
                <th>Volta int.</th>
                <th>Saida</th>
                <th>Trabalhado</th>
                <th>Status</th>
            </tr>
            <?php for ($i = 0; $i < 7; $i++):
                $dia = (clone $inicioSemana)->modify("+$i days");
                $dataSql = $dia->format('Y-m-d');
                $registro = $pontosSemana[$dataSql] ?? [];
            ?>
                <tr class="<?= $dataSql === data_hoje_sql() ? 'today-row' : '' ?>">
                    <td><strong><?= h($diasSemana[$i]) ?></strong> <?= h($dia->format('d/m')) ?></td>
                    <td>Comercial 08h - 18h</td>
                    <td>08:00</td>
                    <td><?= h(hora_curta($registro['entrada'] ?? null)) ?></td>
                    <td><?= h(hora_curta($registro['inicio_intervalo'] ?? null)) ?></td>
                    <td><?= h(hora_curta($registro['fim_intervalo'] ?? null)) ?></td>
                    <td><?= h(hora_curta($registro['saida'] ?? null)) ?></td>
                    <td><?= h((string)($registro['horas_trabalhadas'] ?? '0.00')) ?></td>
                    <td><?= h($registro['status'] ?? 'planejado') ?></td>
                </tr>
            <?php endfor; ?>
        </table>
    </div>

    <aside class="phone-card">
        <div class="phone-header">
            <span><?= h(date('d/m/Y')) ?></span>
            <strong id="clock" data-server-time="<?= h(date('c')) ?>">--:--:--</strong>
        </div>
        <div class="phone-body">
            <h2><?= h($funcionarioAtual['nome'] ?? 'Funcionario') ?></h2>
            <p>Agenda de hoje</p>

            <form method="post" class="phone-form">
                <input type="hidden" name="funcionario_id" value="<?= $funcionarioId ?>">
                <button class="phone-action green" name="acao" value="entrada">Entrada <strong><?= h(hora_curta($pontoHoje['entrada'] ?? null)) ?></strong></button>
                <button class="phone-action" name="acao" value="inicio_intervalo">Inicio de Intervalo <strong><?= h(hora_curta($pontoHoje['inicio_intervalo'] ?? null)) ?></strong></button>
                <button class="phone-action" name="acao" value="fim_intervalo">Volta do Intervalo <strong><?= h(hora_curta($pontoHoje['fim_intervalo'] ?? null)) ?></strong></button>
                <button class="phone-action red" name="acao" value="saida">Saida <strong><?= h(hora_curta($pontoHoje['saida'] ?? null)) ?></strong></button>
            </form>
        </div>
        <div class="phone-footer">
            <span>Painel</span>
            <span>Calendario</span>
            <strong>Hoje</strong>
            <span>Perfil</span>
        </div>
    </aside>
</section>
<?php require_once 'footer.php'; ?>
 

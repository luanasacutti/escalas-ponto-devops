<?php
require_once 'config.php';
exigir_login();

$pdo = db();
$mes = $_GET['mes'] ?? date('Y-m');
if (!preg_match('/^\d{4}-\d{2}$/', $mes)) {
    $mes = date('Y-m');
}

$funcionarioId = (int)($_GET['funcionario_id'] ?? 0);
if (!usuario_admin() && usuario_funcionario_id()) {
    $funcionarioId = usuario_funcionario_id();
}
$inicioMes = $mes . '-01';
$fimMes = date('Y-m-t', strtotime($inicioMes));

if (!usuario_admin() && usuario_funcionario_id()) {
    $stmtFuncionarios = $pdo->prepare('SELECT id, nome FROM funcionarios WHERE ativo = 1 AND id = ? ORDER BY nome');
    $stmtFuncionarios->execute([usuario_funcionario_id()]);
    $funcionarios = $stmtFuncionarios->fetchAll(PDO::FETCH_ASSOC);
} else {
    $funcionarios = $pdo->query('SELECT id, nome FROM funcionarios WHERE ativo = 1 ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
}

$params = [$inicioMes, $fimMes];
$filtroFuncionario = '';
if ($funcionarioId > 0) {
    $filtroFuncionario = ' AND f.id = ?';
    $params[] = $funcionarioId;
}

$stmtResumo = $pdo->prepare("
    SELECT
        f.id,
        f.nome,
        COUNT(r.id) dias_com_ponto,
        COALESCE(SUM(r.horas_trabalhadas), 0) horas,
        SUM(CASE WHEN r.status = 'aberto' THEN 1 ELSE 0 END) pontos_abertos,
        SUM(CASE WHEN r.status = 'fechado' THEN 1 ELSE 0 END) pontos_fechados
    FROM funcionarios f
    LEFT JOIN registros_ponto r
        ON r.funcionario_id = f.id
       AND r.data_ponto BETWEEN ? AND ?
    WHERE f.ativo = 1
    $filtroFuncionario
    GROUP BY f.id, f.nome
    ORDER BY f.nome
");
$stmtResumo->execute($params);
$resumo = $stmtResumo->fetchAll(PDO::FETCH_ASSOC);

$stmtDetalhes = $pdo->prepare("
    SELECT
        f.nome,
        r.data_ponto,
        r.entrada,
        r.inicio_intervalo,
        r.fim_intervalo,
        r.saida,
        r.horas_trabalhadas,
        r.status
    FROM registros_ponto r
    JOIN funcionarios f ON f.id = r.funcionario_id
    WHERE r.data_ponto BETWEEN ? AND ?
    " . ($funcionarioId > 0 ? ' AND f.id = ?' : '') . "
    ORDER BY f.nome, r.data_ponto
");
$stmtDetalhes->execute($params);
$detalhes = $stmtDetalhes->fetchAll(PDO::FETCH_ASSOC);

if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="relatorio-ponto-' . $mes . '.csv"');

    $saida = fopen('php://output', 'w');
    fputcsv($saida, ['Funcionario', 'Data', 'Entrada', 'Inicio intervalo', 'Fim intervalo', 'Saida', 'Horas', 'Status'], ';');
    foreach ($detalhes as $linha) {
        fputcsv($saida, [
            $linha['nome'],
            $linha['data_ponto'],
            $linha['entrada'],
            $linha['inicio_intervalo'],
            $linha['fim_intervalo'],
            $linha['saida'],
            $linha['horas_trabalhadas'],
            $linha['status']
        ], ';');
    }
    fclose($saida);
    exit;
}

$titulo = 'Relatorios';
require_once 'header.php';
?>
<h1>Relatorios</h1>

<section class="panel">
    <h2>Relatorio mensal de ponto</h2>
    <form method="get" class="report-filters">
        <label>
            <span>Mes</span>
            <input type="month" name="mes" value="<?= h($mes) ?>">
        </label>
        <?php if (usuario_admin()): ?>
            <label>
                <span>Funcionario</span>
                <select name="funcionario_id">
                    <option value="0">Todos</option>
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <option value="<?= $funcionario['id'] ?>" <?= $funcionarioId === (int)$funcionario['id'] ? 'selected' : '' ?>><?= h($funcionario['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        <?php else: ?>
            <input type="hidden" name="funcionario_id" value="<?= h((string)$funcionarioId) ?>">
        <?php endif; ?>
        <button>Gerar relatorio</button>
        <a class="btn secundario" href="relatorios.php?mes=<?= h($mes) ?>&funcionario_id=<?= $funcionarioId ?>&export=csv">Exportar CSV</a>
    </form>
</section>

<section class="cards">
    <?php
    $totalDias = array_sum(array_map(fn($item) => (int)$item['dias_com_ponto'], $resumo));
    $totalHoras = array_sum(array_map(fn($item) => (float)$item['horas'], $resumo));
    $totalAbertos = array_sum(array_map(fn($item) => (int)$item['pontos_abertos'], $resumo));
    ?>
    <article class="card"><span>Dias com ponto</span><strong><?= h((string)$totalDias) ?></strong></article>
    <article class="card"><span>Horas registradas</span><strong><?= h(number_format($totalHoras, 2, ',', '.')) ?></strong></article>
    <article class="card"><span>Pontos abertos</span><strong><?= h((string)$totalAbertos) ?></strong></article>
    <article class="card"><span>Periodo</span><strong><?= h(date('m/Y', strtotime($inicioMes))) ?></strong></article>
</section>

<section class="panel">
    <h2>Resumo por funcionario</h2>
    <table>
        <tr><th>Funcionario</th><th>Dias com ponto</th><th>Horas registradas</th><th>Pontos abertos</th><th>Pontos fechados</th></tr>
        <?php foreach ($resumo as $linha): ?>
            <tr>
                <td><?= h($linha['nome']) ?></td>
                <td><?= h((string)$linha['dias_com_ponto']) ?></td>
                <td><?= h(number_format((float)$linha['horas'], 2, ',', '.')) ?></td>
                <td><?= h((string)($linha['pontos_abertos'] ?? 0)) ?></td>
                <td><?= h((string)($linha['pontos_fechados'] ?? 0)) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>

<section class="panel">
    <h2>Detalhamento do mes</h2>
    <table>
        <tr><th>Funcionario</th><th>Data</th><th>Entrada</th><th>Inicio intervalo</th><th>Fim intervalo</th><th>Saida</th><th>Horas</th><th>Status</th></tr>
        <?php foreach ($detalhes as $linha): ?>
            <tr>
                <td><?= h($linha['nome']) ?></td>
                <td><?= h(date('d/m/Y', strtotime($linha['data_ponto']))) ?></td>
                <td><?= h($linha['entrada'] ? date('H:i', strtotime($linha['entrada'])) : '-') ?></td>
                <td><?= h($linha['inicio_intervalo'] ? date('H:i', strtotime($linha['inicio_intervalo'])) : '-') ?></td>
                <td><?= h($linha['fim_intervalo'] ? date('H:i', strtotime($linha['fim_intervalo'])) : '-') ?></td>
                <td><?= h($linha['saida'] ? date('H:i', strtotime($linha['saida'])) : '-') ?></td>
                <td><?= h(number_format((float)$linha['horas_trabalhadas'], 2, ',', '.')) ?></td>
                <td><?= h($linha['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$detalhes): ?>
            <tr><td colspan="8">Nenhum ponto registrado neste periodo.</td></tr>
        <?php endif; ?>
    </table>
</section>
<?php require_once 'footer.php'; ?>
 

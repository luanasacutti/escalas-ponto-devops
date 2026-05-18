<?php
$titulo = 'Dashboard';
require_once 'header.php';
$pdo = db();
$funcionarioLogadoId = usuario_funcionario_id();

if (usuario_admin()) {
    $funcionarios = $pdo->query('SELECT COUNT(*) FROM funcionarios WHERE ativo = 1')->fetchColumn();
    $escalas = $pdo->query('SELECT COUNT(*) FROM escalas')->fetchColumn();
    $pontosHoje = $pdo->query('SELECT COUNT(*) FROM registros_ponto WHERE data_ponto = CURDATE()')->fetchColumn();
    $folgasPendentes = $pdo->query("SELECT COUNT(*) FROM folgas WHERE status = 'pendente'")->fetchColumn();
} else {
    $funcionarios = 1;
    $stmtEscalas = $pdo->prepare('SELECT COUNT(*) FROM escalas WHERE funcionario_id = ?');
    $stmtEscalas->execute([$funcionarioLogadoId]);
    $escalas = $stmtEscalas->fetchColumn();

    $stmtPontos = $pdo->prepare('SELECT COUNT(*) FROM registros_ponto WHERE data_ponto = CURDATE() AND funcionario_id = ?');
    $stmtPontos->execute([$funcionarioLogadoId]);
    $pontosHoje = $stmtPontos->fetchColumn();
    $folgasPendentes = 0;
}
?>
<h1>Dashboard</h1>
<section class="cards">
    <article class="card"><span><?= usuario_admin() ? 'Funcionarios ativos' : 'Meu cadastro' ?></span><strong><?= $funcionarios ?></strong></article>
    <article class="card"><span><?= usuario_admin() ? 'Escalas cadastradas' : 'Minhas escalas' ?></span><strong><?= $escalas ?></strong></article>
    <article class="card"><span>Pontos hoje</span><strong><?= $pontosHoje ?></strong></article>
    <article class="card"><span><?= usuario_admin() ? 'Folgas pendentes' : 'Solicitacoes pendentes' ?></span><strong><?= $folgasPendentes ?></strong></article>
</section>
<section class="panel">
    <h2>Resumo do sistema</h2>
    <p>Esta aplicacao permite gerenciar escalas de trabalho e registrar o ponto dos funcionarios de uma empresa DevOps.</p>
    <div class="actions">
        <a class="btn" href="ponto.php">Registrar ponto</a>
        <a class="btn secundario" href="escalas.php">Ver escalas</a>
    </div>
</section>
<?php require_once 'footer.php'; ?>

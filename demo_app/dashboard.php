<?php
$titulo = 'Dashboard';
require_once 'header.php';
$pdo = db();
$funcionarios = $pdo->query('SELECT COUNT(*) FROM funcionarios WHERE ativo = 1')->fetchColumn();
$escalas = $pdo->query('SELECT COUNT(*) FROM escalas')->fetchColumn();
$pontosHoje = $pdo->query('SELECT COUNT(*) FROM registros_ponto WHERE data_ponto = CURDATE()')->fetchColumn();
$folgasPendentes = $pdo->query("SELECT COUNT(*) FROM folgas WHERE status = 'pendente'")->fetchColumn();
?>
<h1>Dashboard</h1>
<section class="cards">
    <article class="card"><span>Funcionarios ativos</span><strong><?= $funcionarios ?></strong></article>
    <article class="card"><span>Escalas cadastradas</span><strong><?= $escalas ?></strong></article>
    <article class="card"><span>Pontos hoje</span><strong><?= $pontosHoje ?></strong></article>
    <article class="card"><span>Folgas pendentes</span><strong><?= $folgasPendentes ?></strong></article>
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

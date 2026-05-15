<?php
$titulo = 'Funcionarios';
require_once 'header.php';
$pdo = db();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO funcionarios (nome, email, telefone, cargo) VALUES (?, ?, ?, ?)');
    $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['cargo']]);
}
$funcionarios = $pdo->query('SELECT * FROM funcionarios WHERE ativo = 1 ORDER BY nome')->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Funcionarios</h1>
<section class="panel">
    <h2>Novo funcionario</h2>
    <form method="post" class="grid-form">
        <input name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input name="telefone" placeholder="Telefone">
        <input name="cargo" placeholder="Cargo">
        <button>Cadastrar</button>
    </form>
</section>
<section class="panel">
    <h2>Lista</h2>
    <table>
        <tr><th>ID</th><th>Nome</th><th>Email</th><th>Cargo</th><th>Ativo</th></tr>
        <?php foreach ($funcionarios as $f): ?>
            <tr><td><?= $f['id'] ?></td><td><?= h($f['nome']) ?></td><td><?= h($f['email']) ?></td><td><?= h($f['cargo'] ?? '-') ?></td><td><?= $f['ativo'] ? 'Sim' : 'Nao' ?></td></tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once 'footer.php'; ?>

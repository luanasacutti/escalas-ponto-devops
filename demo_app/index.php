<?php
require_once 'config.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = db()->prepare('SELECT * FROM usuarios WHERE email = ? AND ativo = 1');
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email'],
            'tipo' => $usuario['tipo_usuario'],
            'funcionario_id' => $usuario['funcionario_id'] ?? null
        ];
        header('Location: dashboard.php');
        exit;
    }

    $erro = 'Email ou senha invalidos.';
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Escalas e Ponto DevOps</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <main class="login-card">
        <h1>Escalas e Ponto DevOps</h1>
        <p>Acesso ao sistema interno</p>

        <?php if ($erro): ?>
            <div class="alert erro"><?= h($erro) ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Email</label>
            <input type="email" name="email" value="admin@devops.com" required>

            <label>Senha</label>
            <input type="password" name="senha" value="123456" required>

            <button type="submit">Entrar</button>
        </form>

        <small>Admin: admin@devops.com / 123456</small>
        <small>Funcionarios: use o email cadastrado / 123456</small>
    </main>
</body>
</html>
 

<?php
require_once 'config.php';
exigir_login();
$current = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $titulo ?? 'Sistema DevOps' ?></title>
    <link rel="stylesheet" href="style.css?v=20260515-2">
</head>
<body>
<header class="topbar">
    <button class="menu-btn" onclick="document.body.classList.toggle('menu-open')">Menu</button>
    <div>
        <strong>Escalas e Ponto DevOps</strong>
        <span><?= date('d/m/Y H:i') ?></span>
    </div>
    <a href="logout.php" class="sair">Sair</a>
</header>
<aside class="sidebar">
    <div class="perfil"><?= h($_SESSION['usuario']['nome']) ?><br><small><?= h($_SESSION['usuario']['tipo']) ?></small></div>
    <a class="<?= $current === 'dashboard.php' ? 'ativo' : '' ?>" href="dashboard.php">Dashboard</a>
    <a class="<?= $current === 'ponto.php' ? 'ativo' : '' ?>" href="ponto.php">Relogio de Ponto</a>
    <?php if (usuario_admin()): ?>
    <a class="<?= $current === 'funcionarios.php' ? 'ativo' : '' ?>" href="funcionarios.php">Funcionarios</a>
    <?php endif; ?>
    <a class="<?= $current === 'escalas.php' ? 'ativo' : '' ?>" href="escalas.php">Escalas</a>
    <?php if (usuario_admin()): ?>
    <a class="<?= $current === 'relatorios.php' ? 'ativo' : '' ?>" href="relatorios.php">Relatorios</a>
    <?php endif; ?>
</aside>
<main class="content">
 

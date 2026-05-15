<?php
$titulo = 'Relatorios';
require_once 'header.php';
$pdo = db();
$pontos = $pdo->query('SELECT f.nome, COUNT(r.id) total_pontos, SUM(r.horas_trabalhadas) horas FROM funcionarios f LEFT JOIN registros_ponto r ON r.funcionario_id = f.id GROUP BY f.id, f.nome ORDER BY f.nome')->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Relatorios</h1>
<section class="panel">
    <h2>Resumo de ponto por funcionario</h2>
    <table>
        <tr><th>Funcionario</th><th>Dias com ponto</th><th>Horas registradas</th></tr>
        <?php foreach ($pontos as $p): ?>
            <tr><td><?= h($p['nome']) ?></td><td><?= h((string)$p['total_pontos']) ?></td><td><?= h((string)($p['horas'] ?? 0)) ?></td></tr>
        <?php endforeach; ?>
    </table>
</section>
<?php require_once 'footer.php'; ?>

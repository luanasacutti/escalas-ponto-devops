<?php
session_start();

function db(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=mysql_ponto;dbname=escala_ponto_devops_php;charset=utf8mb4',
            'root',
            'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        preparar_dados_demo($pdo);
    }

    return $pdo;
}

function preparar_dados_demo(PDO $pdo): void {
    $total = (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    if ($total === 0) {
        $senha = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, ?)');
        $stmt->execute(['Luana Admin', 'admin@devops.com', $senha, 'admin']);
    }

    $funcionarios = (int) $pdo->query('SELECT COUNT(*) FROM funcionarios')->fetchColumn();
    if ($funcionarios === 0) {
        $dados = [
            ['Maria Eduarda Ferraz da Silva', 'maria.eduarda@devops.com', '11999990001', 'Analista de Sistemas'],
            ['Luana Dias da Silva Sacutti', 'luana.sacutti@devops.com', '11999990002', 'DevOps Engineer'],
            ['Guilherme Almeida da Silva', 'guilherme.almeida@devops.com', '11999990003', 'Analista de Suporte']
        ];
        $stmt = $pdo->prepare('INSERT INTO funcionarios (nome, email, telefone, cargo) VALUES (?, ?, ?, ?)');
        foreach ($dados as $item) {
            $stmt->execute($item);
        }
    }

    $turnos = (int) $pdo->query('SELECT COUNT(*) FROM turnos')->fetchColumn();
    if ($turnos === 0) {
        $stmt = $pdo->prepare('INSERT INTO turnos (nome, hora_inicio, hora_fim, carga_horaria) VALUES (?, ?, ?, ?)');
        $stmt->execute(['Manha', '08:00:00', '12:00:00', 4]);
        $stmt->execute(['Tarde', '13:00:00', '18:00:00', 5]);
        $stmt->execute(['Plantao', '18:00:00', '23:00:00', 5]);
    }

    $equipes = (int) $pdo->query('SELECT COUNT(*) FROM equipes')->fetchColumn();
    if ($equipes === 0) {
        $pdo->exec("INSERT INTO equipes (nome, descricao, lider_id) VALUES ('Plataforma', 'Equipe responsavel por infraestrutura e automacao', 1)");
    }
}

function usuario_logado(): bool {
    return isset($_SESSION['usuario']);
}

function exigir_login(): void {
    if (!usuario_logado()) {
        header('Location: index.php');
        exit;
    }
}

function h(string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

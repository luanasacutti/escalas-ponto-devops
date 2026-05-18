<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

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
    garantir_coluna_funcionario_usuario($pdo);

    $total = (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    if ($total === 0) {
        $senha = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo_usuario, funcionario_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(['Luana Admin', 'admin@devops.com', $senha, 'admin', null]);
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
        $stmt->execute(['Noite', '18:00:00', '23:00:00', 5]);
    }

    $equipes = (int) $pdo->query('SELECT COUNT(*) FROM equipes')->fetchColumn();
    if ($equipes === 0) {
        $pdo->exec("INSERT INTO equipes (nome, descricao, lider_id) VALUES ('Plataforma', 'Equipe responsavel por infraestrutura e automacao', 1)");
    }

    criar_usuarios_funcionarios($pdo);
}

function garantir_coluna_funcionario_usuario(PDO $pdo): void {
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'usuarios'
          AND COLUMN_NAME = 'funcionario_id'
    ");

    if ((int)$stmt->fetchColumn() === 0) {
        $pdo->exec('ALTER TABLE usuarios ADD COLUMN funcionario_id INT NULL AFTER senha');
        $pdo->exec('ALTER TABLE usuarios ADD CONSTRAINT fk_usuario_funcionario FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }
}

function criar_usuarios_funcionarios(PDO $pdo): void {
    $senha = password_hash('123456', PASSWORD_DEFAULT);
    $funcionarios = $pdo->query('SELECT id, nome, email FROM funcionarios WHERE ativo = 1')->fetchAll(PDO::FETCH_ASSOC);
    $stmtUsuario = $pdo->prepare('SELECT id, tipo_usuario FROM usuarios WHERE email = ? LIMIT 1');
    $stmtInserir = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo_usuario, funcionario_id) VALUES (?, ?, ?, "funcionario", ?)');
    $stmtAtualizar = $pdo->prepare('UPDATE usuarios SET funcionario_id = ?, tipo_usuario = "funcionario" WHERE id = ? AND tipo_usuario <> "admin"');

    foreach ($funcionarios as $funcionario) {
        $stmtUsuario->execute([$funcionario['email']]);
        $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $stmtAtualizar->execute([(int)$funcionario['id'], (int)$usuario['id']]);
            continue;
        }

        $stmtInserir->execute([
            $funcionario['nome'],
            $funcionario['email'],
            $senha,
            (int)$funcionario['id']
        ]);
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

function usuario_admin(): bool {
    return ($_SESSION['usuario']['tipo'] ?? '') === 'admin';
}

function usuario_funcionario_id(): ?int {
    $funcionarioId = $_SESSION['usuario']['funcionario_id'] ?? null;
    return $funcionarioId ? (int)$funcionarioId : null;
}

function exigir_admin(): void {
    exigir_login();

    if (!usuario_admin()) {
        header('Location: ponto.php');
        exit;
    }
}

function h(string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function data_hoje_sql(): string {
    return date('Y-m-d');
}

function agora_sql(): string {
    return date('Y-m-d H:i:s');
}

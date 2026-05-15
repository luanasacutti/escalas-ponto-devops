CREATE DATABASE IF NOT EXISTS escala_devops_php
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE escala_devops_php;

CREATE TABLE permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    descricao VARCHAR(255)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('admin', 'gestor', 'funcionario') NOT NULL DEFAULT 'funcionario',
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    cargo VARCHAR(100),
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE equipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    lider_id INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_equipes_lider
        FOREIGN KEY (lider_id) REFERENCES funcionarios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE equipes_membros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipe_id INT NOT NULL,
    funcionario_id INT NOT NULL,
    data_entrada DATE NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    CONSTRAINT fk_membro_equipe
        FOREIGN KEY (equipe_id) REFERENCES equipes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_membro_funcionario
        FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE turnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL UNIQUE,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    carga_horaria INT NOT NULL
);

CREATE TABLE escalas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    equipe_id INT,
    turno_id INT NOT NULL,
    data_escala DATE NOT NULL,
    tipo ENUM('normal', 'plantao', 'folga', 'ferias', 'remoto') NOT NULL DEFAULT 'normal',
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_escala_funcionario
        FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_escala_equipe
        FOREIGN KEY (equipe_id) REFERENCES equipes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_escala_turno
        FOREIGN KEY (turno_id) REFERENCES turnos(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT uq_funcionario_data UNIQUE (funcionario_id, data_escala)
);

CREATE TABLE folgas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    funcionario_id INT NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    tipo ENUM('ferias', 'folga_compensatoria', 'licenca', 'atestado') NOT NULL,
    status ENUM('pendente', 'aprovado', 'recusado') NOT NULL DEFAULT 'pendente',
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_folga_funcionario
        FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE trocas_escala (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solicitante_id INT NOT NULL,
    receptor_id INT NOT NULL,
    escala_solicitante_id INT NOT NULL,
    escala_receptor_id INT NOT NULL,
    status ENUM('pendente', 'aprovado', 'recusado', 'cancelado') NOT NULL DEFAULT 'pendente',
    motivo TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_troca_solicitante
        FOREIGN KEY (solicitante_id) REFERENCES funcionarios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_troca_receptor
        FOREIGN KEY (receptor_id) REFERENCES funcionarios(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_troca_escala_solicitante
        FOREIGN KEY (escala_solicitante_id) REFERENCES escalas(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_troca_escala_receptor
        FOREIGN KEY (escala_receptor_id) REFERENCES escalas(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(150) NOT NULL,
    tabela_afetada VARCHAR(100),
    registro_id INT,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

INSERT INTO permissoes (nome, descricao) VALUES
('admin', 'Acesso total ao sistema'),
('gestor', 'Gerencia equipes, escalas e solicitacoes'),
('funcionario', 'Consulta escalas e solicita folgas ou trocas');

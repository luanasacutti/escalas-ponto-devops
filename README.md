# Escalas e Ponto DevOps

Sistema escolar para gerenciamento de escalas de trabalho e relogio de ponto, desenvolvido com PHP, MySQL, HTML, CSS e JavaScript.

## Funcionalidades

- Login de administrador
- Dashboard com resumo do sistema
- Cadastro e listagem de funcionarios
- Geracao automatica de escalas
- Calendario mensal de escalas
- Registro de ponto com entrada, intervalo e saida
- Tela Super Punch com jornada semanal
- Relatorios de horas registradas

## Tecnologias

- PHP 8.2
- MySQL 8
- Apache
- HTML5
- CSS3
- JavaScript
- Docker Compose

## Como rodar

Na pasta do projeto:

```bash
docker compose -f docker-compose.php.yml up --build
```

Depois acesse:

```txt
http://localhost:8080
```

## Login de teste

```txt
Email: admin@devops.com
Senha: 123456
```

## Funcionarios cadastrados

- Maria Eduarda Ferraz da Silva
- Luana Dias da Silva Sacutti
- Guilherme Almeida da Silva

## Estrutura principal

```txt
demo_app/
  config.php
  dashboard.php
  funcionarios.php
  escalas.php
  ponto.php
  relatorios.php
  style.css
  script.js
schema_mysql_escalas_e_ponto.sql
docker-compose.php.yml
```

## Banco de dados

O MySQL e iniciado pelo Docker Compose. O schema inicial fica em:

```txt
schema_mysql_escalas_e_ponto.sql
```

O container cria o banco automaticamente na primeira subida.

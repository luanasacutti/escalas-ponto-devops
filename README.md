# Escalas e Ponto DevOps

Sistema para gerenciamento de escalas de trabalho e relogio de ponto, desenvolvido com PHP, MySQL, HTML, CSS e JavaScript.

## Funcionalidades

- Login de administrador e funcionarios
- Dashboard com resumo do sistema
- Cadastro e listagem de funcionarios
- Geracao automatica de escalas
- Calendario mensal de escalas
- Registro de ponto com entrada, intervalo e saida
- Tela Super Punch com jornada semanal
- Relatorios de horas registradas
- Permissoes por perfil: admin gerencia tudo, funcionario consulta a propria escala e registra o proprio ponto

## Tecnologias

- PHP 8.2
- MySQL 8
- Apache
- HTML5
- CSS3
- JavaScript
- Docker Compose

## Como rodar

### Requisitos

Para rodar em qualquer computador, e necessario ter:

- Docker Desktop instalado
- Docker Desktop aberto/rodando
- Acesso ao terminal na pasta do projeto

### Rodando pelo GitHub ou pelo ZIP

Se estiver usando o ZIP:

1. Extraia o arquivo `.zip`
2. Abra a pasta extraida no terminal
3. Execute o comando abaixo

Na pasta do projeto:

```bash
docker compose -f docker-compose.php.yml up --build
```

Depois acesse:

```txt
http://localhost:8080
```

Se tudo estiver correto, a tela de login sera exibida.

## Login de teste

```txt
Admin:
Email: admin@devops.com
Senha: 123456

Funcionarios:
Email: maria.eduarda@devops.com
Email: luana.sacutti@devops.com
Email: guilherme.almeida@devops.com
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

## Observacao sobre portas

A aplicacao usa as portas:

```txt
8080 - aplicacao PHP no navegador
3307 - MySQL
```

Se outro programa ja estiver usando alguma dessas portas, o Docker pode mostrar erro ao iniciar.

Nesse caso, altere as portas no arquivo:

```txt
docker-compose.php.yml
```

Exemplo:

```yml
ports:
  - "8081:80"
```

Depois acesse:

```txt
http://localhost:8081
```

## Parar a aplicacao

Para parar os containers, pressione `CTRL + C` no terminal onde o Docker esta rodando.

Se quiser parar em segundo plano, use:

```bash
docker compose -f docker-compose.php.yml down
```
 

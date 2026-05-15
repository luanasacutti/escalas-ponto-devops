# Trabalho Final - Sistema Escalas e Ponto DevOps

## 1. Tema do Projeto

O projeto escolhido foi uma aplicação web para uma empresa fictícia do ramo de tecnologia e DevOps.

A empresa precisa controlar a jornada de trabalho dos colaboradores, registrar pontos, organizar escalas e consultar relatórios de horas. O sistema foi pensado para funcionar pelo navegador em computador, notebook, tablet e celular.

## 2. Objetivo do Sistema

O sistema foi criado para auxiliar uma empresa DevOps no gerenciamento de funcionários, escalas de trabalho e registros de ponto.

### Para que o sistema será criado?

Para controlar os horários dos colaboradores, organizar escalas e registrar entradas, intervalos e saídas.

### Qual problema ele resolve?

Resolve o problema de controle manual de escalas e ponto, reduzindo erros em planilhas, papéis e anotações soltas.

### Quem irá utilizar?

- Administrador do sistema
- Gestores da empresa
- Funcionários da equipe DevOps

### Quais benefícios ele oferece?

- Centralização das informações
- Controle de funcionários ativos
- Registro de ponto por navegador
- Geração automática de escalas
- Consulta de relatórios
- Melhor organização da jornada de trabalho
- Acesso em diferentes dispositivos

## 3. Funcionalidades do Sistema

| Funcionalidade | Descrição |
| --- | --- |
| Login | Permite o acesso seguro ao sistema por email e senha |
| Dashboard | Apresenta resumo de funcionários, escalas, pontos e folgas |
| Cadastro de funcionários | Registra os colaboradores que utilizarão o sistema |
| Listagem de funcionários | Exibe os funcionários ativos cadastrados |
| Geração de escalas | Permite criar escalas automaticamente por período e turno |
| Calendário de escalas | Mostra as escalas distribuídas em um calendário mensal |
| Registro de ponto | Permite registrar entrada, início de intervalo, volta de intervalo e saída |
| Super Punch | Exibe a jornada semanal do funcionário selecionado |
| Relatórios | Mostra resumo de dias com ponto e horas trabalhadas |
| Controle de permissões | Define tipos de usuários como admin, gestor e funcionário |

## 4. Requisitos do Sistema

### Requisitos funcionais

- O sistema deve permitir login de usuários.
- O sistema deve cadastrar e listar funcionários.
- O sistema deve permitir registrar ponto diário.
- O sistema deve impedir duplicidade de ponto do mesmo funcionário no mesmo dia.
- O sistema deve permitir criar escalas manualmente.
- O sistema deve gerar escalas automaticamente.
- O sistema deve exibir escalas em formato de calendário.
- O sistema deve gerar relatórios de horas.
- O sistema deve armazenar dados em banco MySQL.

### Requisitos não funcionais

- A aplicação deve funcionar via navegador.
- A interface deve ser responsiva.
- O backend deve utilizar PHP.
- O banco de dados deve ser MySQL.
- O projeto deve usar HTML, CSS e JavaScript no frontend.
- O sistema deve ser organizado em arquivos separados.
- A navegação deve ser simples e objetiva.
- O sistema deve ser executável com Docker Compose.

## 5. Estrutura das Telas

| Tela | Objetivo |
| --- | --- |
| Login | Validar o acesso do usuário |
| Dashboard | Apresentar resumo geral do sistema |
| Funcionários | Cadastrar e listar colaboradores |
| Escalas | Gerar, visualizar e cadastrar escalas de trabalho |
| Relógio de Ponto | Registrar entrada, intervalo e saída |
| Super Punch | Visualizar jornada semanal e marcações do colaborador |
| Relatórios | Exibir informações sobre pontos e horas trabalhadas |

## 6. Layout e Responsividade

O sistema possui uma interface web organizada com:

- Cabeçalho superior com nome do sistema e opção de sair
- Menu lateral com links para as principais telas
- Área central de conteúdo
- Cards de resumo no dashboard
- Formulários para cadastro e filtros
- Tabelas para listagem de informações
- Calendário mensal para visualização das escalas
- Botões destacados para ações importantes
- Tela de ponto com painel visual semelhante a aplicações profissionais

A responsividade foi planejada para que o sistema se adapte a diferentes tamanhos de tela. Em telas menores, o menu lateral pode ser recolhido e os conteúdos passam a ser exibidos em uma coluna, facilitando o uso em celular e tablet.

## 7. Banco de Dados

O banco de dados utilizado é MySQL. As principais tabelas planejadas e implementadas são:

| Tabela | Finalidade |
| --- | --- |
| usuarios | Armazena os dados dos usuários que acessam o sistema |
| permissoes | Armazena os tipos de acesso ao sistema |
| funcionarios | Armazena os dados dos colaboradores |
| equipes | Armazena as equipes da empresa |
| equipes_membros | Relaciona funcionários às equipes |
| turnos | Armazena os horários de trabalho disponíveis |
| escalas | Armazena as escalas de trabalho dos funcionários |
| registros_ponto | Armazena as marcações de ponto |
| ajustes_ponto | Armazena solicitações de ajuste de ponto |
| folgas | Armazena solicitações e períodos de folga |
| trocas_escala | Armazena solicitações de troca de escala |
| logs | Registra ações importantes no sistema |

### Exemplos de campos

#### usuarios

- id
- nome
- email
- senha
- tipo_usuario
- ativo
- criado_em

#### funcionarios

- id
- nome
- email
- telefone
- cargo
- ativo
- criado_em

#### escalas

- id
- funcionario_id
- equipe_id
- turno_id
- data_escala
- tipo
- observacoes
- criado_em

#### registros_ponto

- id
- funcionario_id
- data_ponto
- entrada
- inicio_intervalo
- fim_intervalo
- saida
- horas_trabalhadas
- status
- criado_em

## 8. Conclusão

O desenvolvimento da aplicação web Escalas e Ponto DevOps demonstrou como um problema real de uma empresa pode ser resolvido com tecnologia web.

O sistema permite organizar funcionários, escalas e registros de ponto de forma centralizada, facilitando a rotina administrativa e melhorando o controle das informações.

Durante o projeto, foram aplicados conceitos de análise de requisitos, criação de telas, banco de dados, programação em PHP, HTML, CSS, JavaScript, segurança básica, responsividade e organização de arquivos. A aplicação foi planejada para funcionar em diferentes dispositivos, atendendo à proposta de uma solução web multiplataforma.

## Resultado Esperado

Ao final do projeto, foi possível demonstrar:

- Identificação de um problema que pode ser resolvido por um sistema
- Definição dos objetivos da aplicação
- Planejamento das funcionalidades
- Organização dos requisitos
- Criação de telas e navegação
- Projeto de banco de dados
- Uso de tecnologias web adequadas
- Desenvolvimento com PHP como backend
- Criação de interface responsiva
- Organização da entrega em arquivos do projeto

# Projeto: Sistema Web de Escalas e Relogio de Ponto para Empresa DevOps

## 1. Tema do Projeto

O projeto escolhido e uma aplicacao web para uma empresa do ramo de tecnologia, especificamente uma empresa de DevOps.

A empresa possui funcionarios que trabalham em equipes, turnos, plantoes e escalas. Alem disso, precisa controlar a entrada, saida, intervalos e horas trabalhadas dos colaboradores.

Por esse motivo, o sistema tera dois modulos principais:

- Gerenciamento de escalas de trabalho
- Relogio de ponto para funcionarios

A aplicacao sera acessada pelo navegador e devera funcionar em computador, notebook, tablet e celular.

Tecnologias planejadas:

- HTML
- CSS
- JavaScript
- PHP
- MySQL
- Design responsivo

## 2. Objetivo do Sistema

O sistema sera criado para organizar as escalas de trabalho e registrar o ponto dos funcionarios de uma empresa DevOps.

### Para que o sistema sera criado?

Para controlar funcionarios, equipes, turnos, escalas, folgas, trocas de plantao e registros de ponto, como entrada, saida, inicio de intervalo e fim de intervalo.

### Qual problema ele resolve?

O sistema resolve dois problemas comuns em empresas:

1. Falta de organizacao das escalas de trabalho, plantoes e folgas.
2. Controle manual do ponto dos funcionarios, feito por papel, planilhas ou mensagens.

Esses metodos manuais podem causar erros, atrasos, perda de informacoes e dificuldade para calcular horas trabalhadas.

### Quem ira utilizar?

- Administrador do sistema
- Gestores de equipes
- Funcionarios
- RH ou setor responsavel por jornada de trabalho

### Quais beneficios ele oferece?

- Organizacao das escalas de trabalho
- Registro de entrada e saida dos funcionarios
- Controle de intervalos
- Calculo de horas trabalhadas
- Consulta de historico de ponto
- Controle de folgas e trocas de plantao
- Acesso por computador, notebook, tablet e celular
- Informacoes centralizadas em banco de dados
- Maior transparencia para funcionarios e gestores

## 3. Funcionalidades do Sistema

| Funcionalidade | Descricao |
|---|---|
| Login | Permite o acesso seguro ao sistema por email e senha |
| Cadastro de usuarios | Registra os usuarios que acessarao o sistema |
| Cadastro de funcionarios | Armazena os dados dos colaboradores |
| Cadastro de equipes | Organiza os funcionarios por equipe |
| Cadastro de turnos | Registra horarios como manha, tarde, noite e plantao |
| Cadastro de escalas | Define funcionario, data, equipe e turno de trabalho |
| Lista de escalas | Mostra as escalas cadastradas |
| Edicao de escalas | Permite alterar uma escala existente |
| Solicitacao de folgas | Permite solicitar ferias, folgas, licencas ou atestados |
| Troca de plantao | Permite solicitar troca de escala com outro funcionario |
| Aprovacao de solicitacoes | Permite aprovar ou recusar folgas e trocas |
| Registro de entrada | Permite que o funcionario registre o inicio do expediente |
| Registro de inicio de intervalo | Permite registrar o inicio do horario de almoco ou pausa |
| Registro de fim de intervalo | Permite registrar o retorno do intervalo |
| Registro de saida | Permite registrar o fim do expediente |
| Historico de ponto | Mostra os registros de ponto do funcionario |
| Relatorio de horas | Calcula horas trabalhadas por periodo |
| Controle de permissoes | Define o que cada tipo de usuario pode acessar |
| Logs do sistema | Registra acoes importantes realizadas no sistema |

## 4. Requisitos do Sistema

### Requisitos funcionais

| Codigo | Requisito |
|---|---|
| RF01 | O sistema deve permitir login de usuarios |
| RF02 | O sistema deve permitir cadastro de funcionarios |
| RF03 | O sistema deve permitir editar e desativar funcionarios |
| RF04 | O sistema deve permitir cadastro de equipes |
| RF05 | O sistema deve permitir vincular funcionarios a equipes |
| RF06 | O sistema deve permitir cadastro de turnos |
| RF07 | O sistema deve permitir cadastro de escalas |
| RF08 | O sistema deve listar escalas por data, funcionario e equipe |
| RF09 | O sistema deve impedir duas escalas para o mesmo funcionario no mesmo dia |
| RF10 | O sistema deve permitir solicitacao de folgas |
| RF11 | O sistema deve permitir troca de plantao |
| RF12 | O sistema deve permitir aprovar ou recusar solicitacoes |
| RF13 | O sistema deve permitir registro de entrada do funcionario |
| RF14 | O sistema deve permitir registro de inicio e fim de intervalo |
| RF15 | O sistema deve permitir registro de saida do funcionario |
| RF16 | O sistema deve impedir registros duplicados de ponto no mesmo periodo |
| RF17 | O sistema deve calcular horas trabalhadas no dia |
| RF18 | O sistema deve exibir historico de ponto |
| RF19 | O sistema deve gerar relatorios de escalas e ponto |
| RF20 | O sistema deve controlar permissoes de acesso |

### Requisitos nao funcionais

| Codigo | Requisito |
|---|---|
| RNF01 | O sistema deve funcionar via navegador |
| RNF02 | O sistema deve ser responsivo para computador, notebook, tablet e celular |
| RNF03 | O sistema deve utilizar PHP no backend |
| RNF04 | O sistema deve utilizar MySQL como banco de dados |
| RNF05 | O sistema deve utilizar HTML, CSS e JavaScript no frontend |
| RNF06 | O sistema deve armazenar senhas com hash |
| RNF07 | O sistema deve ter interface simples e organizada |
| RNF08 | O sistema deve validar os dados dos formularios |
| RNF09 | O sistema deve registrar data e hora dos pontos automaticamente |
| RNF10 | O sistema deve permitir manutencao e expansao futura |

## 5. Estrutura das Telas

| Tela | Objetivo |
|---|---|
| Login | Validar o acesso do usuario |
| Dashboard | Apresentar resumo de escalas, pontos, folgas e trocas |
| Cadastro de usuarios | Registrar usuarios e permissoes |
| Cadastro de funcionarios | Cadastrar e editar colaboradores |
| Cadastro de equipes | Criar equipes e definir lider |
| Cadastro de turnos | Registrar horarios de trabalho |
| Cadastro de escalas | Criar escalas por funcionario, data e turno |
| Lista de escalas | Consultar, editar e excluir escalas |
| Relogio de ponto | Registrar entrada, intervalo, retorno e saida |
| Historico de ponto | Consultar os pontos registrados |
| Ajuste de ponto | Permitir que gestor corrija registros com justificativa |
| Solicitacao de folgas | Registrar pedidos de folga, ferias, licenca ou atestado |
| Trocas de plantao | Solicitar, aprovar ou recusar trocas |
| Relatorios | Exibir indicadores de escalas e horas trabalhadas |
| Configuracoes | Gerenciar permissoes e dados gerais |

## 6. Layout e Responsividade

A aplicacao tera layout simples, moderno e responsivo.

### Cabecalho

O cabecalho exibira o nome do sistema, usuario logado, data atual e botao de sair.

### Menu principal

O menu principal dara acesso a dashboard, funcionarios, equipes, turnos, escalas, relogio de ponto, historico, folgas, trocas e relatorios.

Em computadores, o menu podera ficar na lateral. Em celulares, sera recolhido em um botao.

### Area de conteudo

A area central exibira cards, formularios, tabelas, calendario de escalas e botoes de ponto.

### Relogio de ponto

A tela de relogio de ponto tera destaque para:

- Hora atual
- Data atual
- Botao Entrada
- Botao Inicio do intervalo
- Botao Fim do intervalo
- Botao Saida
- Mensagem informando o ultimo registro

### Botoes

- Azul para salvar ou cadastrar
- Verde para registrar entrada ou aprovar
- Amarelo para editar
- Vermelho para excluir ou recusar
- Cinza para cancelar ou voltar

### Formularios

Os formularios terao labels, campos obrigatorios, mensagens de erro e validacao.

### Tabelas

As tabelas exibirao funcionarios, escalas, registros de ponto, folgas e trocas.

Em celulares, as tabelas poderao usar rolagem horizontal ou formato de cards.

### Cards

O dashboard tera cards com:

- Funcionarios ativos
- Escalas do dia
- Pontos registrados hoje
- Folgas pendentes
- Trocas pendentes
- Total de horas do mes

### Rodape

O rodape exibira nome do sistema, ano do projeto e informacoes da turma.

## 7. Banco de Dados

| Tabela | Finalidade |
|---|---|
| usuarios | Armazena dados de acesso ao sistema |
| funcionarios | Armazena dados dos colaboradores |
| equipes | Armazena equipes da empresa |
| equipes_membros | Relaciona funcionarios com equipes |
| turnos | Armazena horarios de trabalho |
| escalas | Armazena escalas cadastradas |
| registros_ponto | Armazena entrada, intervalo, retorno e saida |
| ajustes_ponto | Armazena solicitacoes ou correcoes de ponto |
| folgas | Armazena solicitacoes de folgas, ferias e licencas |
| trocas_escala | Armazena solicitacoes de troca de plantao |
| permissoes | Controla os tipos de acesso |
| logs | Registra acoes importantes do sistema |

### Campos principais

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

#### equipes

- id
- nome
- descricao
- lider_id
- criado_em

#### turnos

- id
- nome
- hora_inicio
- hora_fim
- carga_horaria

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

#### ajustes_ponto

- id
- registro_ponto_id
- funcionario_id
- tipo_ajuste
- justificativa
- status
- aprovado_por
- criado_em

#### folgas

- id
- funcionario_id
- data_inicio
- data_fim
- tipo
- status
- observacoes
- criado_em

#### trocas_escala

- id
- solicitante_id
- receptor_id
- escala_solicitante_id
- escala_receptor_id
- status
- motivo
- criado_em

## 8. DER Simplificado

Relacionamentos principais:

- Um usuario acessa o sistema com um tipo de permissao.
- Um funcionario pode pertencer a uma ou mais equipes.
- Uma equipe pode possuir varios funcionarios.
- Um funcionario pode possuir varias escalas.
- Uma escala pertence a um turno.
- Um funcionario pode possuir varios registros de ponto.
- Um registro de ponto pertence a um funcionario e a uma data.
- Um funcionario pode solicitar ajustes de ponto.
- Um funcionario pode solicitar folgas.
- Um funcionario pode solicitar troca de plantao com outro funcionario.
- O sistema registra logs das acoes realizadas.

## 9. Tecnologias Utilizadas

| Tecnologia | Uso no projeto |
|---|---|
| HTML | Estrutura das paginas |
| CSS | Estilizacao e responsividade |
| JavaScript | Interacoes, validacoes e exibicao dinamica |
| PHP | Backend, sessoes, regras de negocio e comunicacao com o banco |
| MySQL | Armazenamento dos dados |
| Navegador Web | Plataforma de acesso ao sistema |

## 10. Seguranca

O sistema devera considerar:

- Login com email e senha
- Senhas armazenadas com hash
- Uso de sessoes em PHP
- Validacao dos formularios
- Controle de permissao por tipo de usuario
- Protecao contra acesso nao autorizado
- Registro de logs
- Restricao de alteracao de ponto apenas por gestor ou administrador

## 11. Conclusao

O Sistema Web de Escalas e Relogio de Ponto para uma empresa DevOps e importante porque une o planejamento da jornada de trabalho com o registro real da presenca dos funcionarios.

Com esse sistema, a empresa pode organizar escalas, plantoes, folgas, trocas e tambem controlar entrada, saida, intervalos e horas trabalhadas.

O projeto contribui para o aprendizado de analise de requisitos, banco de dados, programacao web, seguranca, responsividade e organizacao de sistemas.

A aplicacao foi planejada para funcionar em diferentes dispositivos, como computador, notebook, tablet e celular, atendendo a uma necessidade real de uma empresa por meio de tecnologias web como HTML, CSS, JavaScript, PHP e MySQL.

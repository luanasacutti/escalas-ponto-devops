# Projeto: Sistema Web de Gerenciamento de Escalas DevOps

## 1. Tema do Projeto

O tema escolhido para o projeto é uma aplicação web para uma empresa do ramo de tecnologia, especificamente uma empresa de DevOps.

A empresa possui funcionários que trabalham em diferentes equipes, turnos, plantões e escalas. Por isso, existe a necessidade de um sistema capaz de organizar horários, folgas, equipes e trocas de plantão de forma centralizada.

O sistema será desenvolvido para uso via navegador e deverá funcionar em computador, notebook, tablet e celular.

Tecnologias planejadas:

- HTML
- CSS
- JavaScript
- PHP
- MySQL
- Design responsivo

## 2. Objetivo do Sistema

O sistema será criado para gerenciar as escalas de trabalho de uma empresa DevOps.

### Para que o sistema será criado?

Para controlar funcionários, equipes, turnos, escalas, folgas e trocas de plantão.

### Qual problema ele resolve?

Ele resolve o problema de controle manual de escalas feito por planilhas, mensagens ou anotações. Esse tipo de controle pode gerar erros, duplicidade de horários, esquecimentos e dificuldade para consultar informações.

### Quem irá utilizar?

- Administrador do sistema
- Gestores de equipes
- Funcionários da empresa
- Responsáveis pelo controle de escalas

### Quais benefícios ele oferece?

- Organização das escalas de trabalho
- Redução de erros no controle de plantões
- Consulta rápida pelo navegador
- Acesso em diferentes dispositivos
- Controle de funcionários e equipes
- Registro de folgas e trocas
- Melhor comunicação entre gestores e colaboradores
- Armazenamento seguro das informações no banco de dados

## 3. Funcionalidades do Sistema

| Funcionalidade | Descrição |
|---|---|
| Login | Permite o acesso seguro ao sistema por email e senha |
| Cadastro de usuários | Registra os usuários que terão acesso ao sistema |
| Cadastro de funcionários | Armazena os dados dos colaboradores da empresa |
| Cadastro de equipes | Permite criar equipes de trabalho DevOps |
| Vincular funcionário à equipe | Relaciona funcionários com suas equipes |
| Cadastro de turnos | Registra horários como manhã, tarde, noite e plantão |
| Cadastro de escalas | Permite definir qual funcionário trabalhará em determinada data e turno |
| Listagem de escalas | Mostra as escalas cadastradas para consulta |
| Edição de escalas | Permite alterar uma escala já criada |
| Solicitação de folgas | Permite registrar férias, folgas, licenças ou atestados |
| Troca de plantão | Permite solicitar troca de escala entre funcionários |
| Aprovação de solicitações | Permite que gestores aprovem ou recusem folgas e trocas |
| Relatórios | Exibe informações sobre escalas, horas e solicitações |
| Controle de permissões | Define o que cada tipo de usuário pode acessar |
| Logs do sistema | Registra ações importantes realizadas pelos usuários |

## 4. Requisitos do Sistema

### Requisitos funcionais

| Código | Requisito |
|---|---|
| RF01 | O sistema deve permitir login de usuários |
| RF02 | O sistema deve permitir cadastro de funcionários |
| RF03 | O sistema deve permitir edição e desativação de funcionários |
| RF04 | O sistema deve permitir cadastro de equipes |
| RF05 | O sistema deve permitir vincular funcionários a equipes |
| RF06 | O sistema deve permitir cadastro de turnos |
| RF07 | O sistema deve permitir cadastro de escalas |
| RF08 | O sistema deve listar escalas por data, equipe e funcionário |
| RF09 | O sistema deve impedir duas escalas para o mesmo funcionário no mesmo dia |
| RF10 | O sistema deve permitir solicitação de folgas |
| RF11 | O sistema deve permitir solicitação de troca de plantão |
| RF12 | O sistema deve permitir aprovação ou recusa de solicitações |
| RF13 | O sistema deve gerar relatórios básicos |
| RF14 | O sistema deve controlar permissões de acesso |

### Requisitos não funcionais

| Código | Requisito |
|---|---|
| RNF01 | O sistema deve funcionar via navegador |
| RNF02 | O sistema deve ser responsivo para computador, notebook, tablet e celular |
| RNF03 | O sistema deve utilizar PHP no backend |
| RNF04 | O sistema deve utilizar MySQL como banco de dados |
| RNF05 | O sistema deve utilizar HTML, CSS e JavaScript no frontend |
| RNF06 | O sistema deve armazenar senhas de forma segura |
| RNF07 | O sistema deve ter interface simples e organizada |
| RNF08 | O sistema deve validar os dados enviados pelos formulários |
| RNF09 | O sistema deve ter menus e botões de fácil compreensão |
| RNF10 | O sistema deve permitir manutenção e expansão futura |

## 5. Estrutura das Telas

| Tela | Objetivo |
|---|---|
| Login | Validar o acesso do usuário ao sistema |
| Dashboard | Apresentar resumo de funcionários, escalas, folgas e trocas |
| Cadastro de usuários | Registrar usuários e definir permissões |
| Cadastro de funcionários | Cadastrar e editar colaboradores |
| Cadastro de equipes | Criar equipes e definir líder |
| Membros da equipe | Vincular funcionários às equipes |
| Cadastro de turnos | Registrar horários de trabalho |
| Cadastro de escalas | Criar escalas por funcionário, data e turno |
| Lista de escalas | Consultar, editar e excluir escalas |
| Solicitação de folgas | Registrar pedidos de férias, folgas, licenças e atestados |
| Trocas de plantão | Solicitar, aprovar ou recusar trocas de escala |
| Relatórios | Exibir indicadores e informações do sistema |
| Configurações | Gerenciar permissões e dados gerais |

## 6. Layout e Responsividade

A aplicação terá um layout limpo, organizado e responsivo.

### Cabeçalho

O cabeçalho exibirá o nome do sistema, o nome do usuário logado e a opção de sair.

### Menu principal

O menu principal dará acesso às telas de dashboard, funcionários, equipes, turnos, escalas, folgas, trocas e relatórios.

Em telas maiores, o menu poderá aparecer na lateral. Em celulares, o menu será recolhido em um botão.

### Área de conteúdo

A área central exibirá formulários, tabelas, cards, calendários e relatórios.

### Botões

Os botões terão cores padronizadas:

- Azul para cadastrar ou salvar
- Cinza para voltar ou cancelar
- Vermelho para excluir
- Verde para aprovar
- Amarelo para editar

### Formulários

Os formulários serão organizados com campos claros, labels e mensagens de validação.

### Tabelas

As tabelas serão utilizadas para listar funcionários, equipes, escalas, folgas e trocas.

Em celulares, as tabelas poderão se adaptar para cards ou rolagem horizontal.

### Cards

Os cards serão usados no dashboard para mostrar resumos como quantidade de funcionários ativos, folgas pendentes e escalas do dia.

### Rodapé

O rodapé apresentará informações simples sobre o sistema e o ano do projeto.

### Adaptação para celular

No celular:

- O menu será compacto
- Os formulários ocuparão a largura da tela
- Os botões serão maiores
- As tabelas serão adaptadas para melhor leitura
- Os cards ficarão empilhados verticalmente

## 7. Banco de Dados

| Tabela | Finalidade |
|---|---|
| usuarios | Armazena os dados de acesso ao sistema |
| funcionarios | Armazena os dados dos colaboradores |
| equipes | Armazena as equipes da empresa |
| equipes_membros | Relaciona funcionários com equipes |
| turnos | Armazena os horários de trabalho |
| escalas | Armazena as escalas cadastradas |
| folgas | Armazena solicitações de folgas, férias, licenças e atestados |
| trocas_escala | Armazena solicitações de troca de plantão |
| permissoes | Controla os tipos de acesso |
| logs | Registra ações importantes realizadas no sistema |

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

#### equipes_membros

- id
- equipe_id
- funcionario_id
- data_entrada
- ativo

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

#### permissoes

- id
- nome
- descricao

#### logs

- id
- usuario_id
- acao
- tabela_afetada
- registro_id
- data_hora

## 8. DER Simplificado

Relacionamentos principais:

- Um usuário pode acessar o sistema com uma permissão.
- Um funcionário pode pertencer a uma ou mais equipes.
- Uma equipe pode possuir vários funcionários.
- Um funcionário pode possuir várias escalas.
- Uma escala pertence a um turno.
- Uma escala pode estar relacionada a uma equipe.
- Um funcionário pode solicitar várias folgas.
- Um funcionário pode solicitar trocas de plantão com outro funcionário.
- O sistema registra logs das ações dos usuários.

## 9. Tecnologias Utilizadas

| Tecnologia | Uso no projeto |
|---|---|
| HTML | Estrutura das páginas |
| CSS | Estilização e responsividade |
| JavaScript | Interações, validações e requisições assíncronas |
| PHP | Backend, regras de negócio e comunicação com o banco |
| MySQL | Armazenamento dos dados |
| Navegador Web | Plataforma de acesso ao sistema |

## 10. Segurança

O sistema deverá considerar as seguintes práticas:

- Login com email e senha
- Senhas armazenadas com hash no banco de dados
- Validação dos campos dos formulários
- Controle de permissões por tipo de usuário
- Proteção contra acesso não autorizado
- Registro de ações importantes em logs
- Uso de sessões em PHP para manter o usuário autenticado

## 11. Conclusão

O projeto de um Sistema Web de Gerenciamento de Escalas DevOps é importante porque apresenta uma solução para um problema comum em empresas de tecnologia: a organização de horários, equipes, plantões, folgas e trocas de escala.

Com essa aplicação, a empresa poderá reduzir erros, centralizar informações e facilitar o acesso aos dados por meio de computador, notebook, tablet ou celular.

O desenvolvimento deste planejamento contribui para o aprendizado de análise de requisitos, criação de telas, banco de dados, programação web, segurança, responsividade e organização de sistemas.

O projeto demonstra como uma aplicação web pode atender a uma necessidade real de uma empresa, utilizando tecnologias como HTML, CSS, JavaScript, PHP e MySQL.

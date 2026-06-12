# Cadastro de Clientes e Veículos — Especificação

**Escopo avaliado**: Large (multi-componente: 2 entidades, migrations, models, requests, controllers, views, rotas, testes)
**Arquitetura**: MVC com Blade (aplicação web server-rendered, não API JSON — decisão do usuário em 2026-06-12)

## Problema

O estacionamento do shopping precisa identificar quem são os clientes e quais veículos pertencem a cada um, para futuramente controlar entrada/saída e cobrança. Hoje não existe nenhum cadastro — a aplicação está vazia.

## Objetivos

- [x] CRUD completo de clientes via formulários web
- [x] CRUD completo de veículos via formulários web, sempre vinculados a um cliente
- [x] Integridade: CPF único, placa única, veículo nunca órfão

## Fora de Escopo

| Feature | Motivo |
| ------- | ------ |
| Entrada/saída do estacionamento (tickets) | Fase 2 do roadmap |
| Cobrança e tabela de preços | Fase 3 |
| Autenticação (JWT) | Fase 4 |
| Validação de dígito verificador de CPF | Simplicidade — formato apenas |
| API JSON | Arquitetura definida como MVC web; `routes/api.php` reservado para o futuro |

---

## User Stories

### P1: Cadastrar e gerenciar clientes ⭐ MVP

**User Story**: Como operador do estacionamento, quero cadastrar, consultar, atualizar e remover clientes pelas telas do sistema para manter a base de quem usa o estacionamento.

**Acceptance Criteria**:

1. WHEN o operador envia o formulário de novo cliente com nome e CPF válidos THEN o sistema SHALL salvar e redirecionar para a listagem com mensagem de sucesso
2. WHEN o CPF já está cadastrado THEN o sistema SHALL voltar ao formulário com erro de validação no campo CPF
3. WHEN nome ou CPF não são informados THEN o sistema SHALL voltar ao formulário com erros nos campos
4. WHEN o operador acessa `/clientes` THEN o sistema SHALL exibir a listagem paginada com contagem de veículos
5. WHEN o operador edita um cliente mantendo o próprio CPF THEN o sistema SHALL aceitar a atualização
6. WHEN o operador exclui um cliente sem veículos THEN o sistema SHALL remover e exibir mensagem de sucesso

**Independent Test**: pelo navegador, criar, listar, editar e excluir um cliente — sem precisar de veículos.

### P1: Cadastrar e gerenciar veículos ⭐ MVP

**User Story**: Como operador do estacionamento, quero cadastrar os veículos de cada cliente para saber quem é o dono de cada carro no pátio.

**Acceptance Criteria**:

1. WHEN o operador envia o formulário de novo veículo com placa válida e cliente selecionado THEN o sistema SHALL salvar e redirecionar com sucesso
2. WHEN a placa já está cadastrada THEN o sistema SHALL voltar ao formulário com erro no campo placa
3. WHEN o cliente selecionado não existe THEN o sistema SHALL voltar com erro no campo cliente
4. WHEN a placa está fora dos formatos ABC1234/ABC1D23 THEN o sistema SHALL voltar com erro no campo placa
5. WHEN o operador acessa a listagem de veículos THEN o sistema SHALL exibir cada veículo com o nome do dono (link para o cliente)
6. WHEN o operador exclui um veículo THEN o sistema SHALL remover e exibir mensagem de sucesso

**Independent Test**: com um cliente existente, criar veículo pelo formulário, ver na listagem e excluir.

### P2: Consultar veículos de um cliente e buscar por placa

**User Story**: Como operador, quero ver todos os veículos de um cliente e localizar um veículo pela placa para identificar rapidamente o dono de um carro no pátio.

**Acceptance Criteria**:

1. WHEN o operador abre a página de um cliente THEN o sistema SHALL exibir os veículos daquele cliente (e somente dele)
2. WHEN o operador busca por placa na listagem de veículos (`/veiculos?placa=`) THEN o sistema SHALL filtrar os resultados
3. WHEN a placa buscada não existe THEN o sistema SHALL exibir lista vazia com mensagem "nenhum veículo encontrado"
4. WHEN a placa é digitada em minúsculas THEN o sistema SHALL encontrar o veículo mesmo assim (normalização)

---

## Edge Cases

- WHEN o operador exclui cliente que possui veículos THEN o sistema SHALL bloquear, redirecionar com mensagem de erro e manter o registro (FK `restrict` como segunda linha de defesa)
- WHEN o CPF é enviado com máscara (`123.456.789-09`) THEN o sistema SHALL aceitar e armazenar apenas os 11 dígitos
- WHEN a placa é enviada em minúsculas ou com hífen THEN o sistema SHALL armazenar normalizada em maiúsculas
- WHEN o cliente é editado mantendo o próprio CPF THEN o sistema SHALL aceitar (unique ignora o próprio registro)

---

## Rastreabilidade de Requisitos

| Requirement ID | Story | Fase | Status |
| -------------- | ----- | ---- | ------ |
| CAD-01 | P1 Clientes: CRUD completo | Done | Verified |
| CAD-02 | P1 Clientes: CPF único e obrigatório, normalizado | Done | Verified |
| CAD-03 | P1 Veículos: CRUD completo vinculado a cliente | Done | Verified |
| CAD-04 | P1 Veículos: placa única e validada (ABC1234/ABC1D23) | Done | Verified |
| CAD-05 | P2: veículos do cliente na página do cliente | Done | Verified |
| CAD-06 | P2: busca por placa na listagem (case-insensitive) | Done | Verified |
| CAD-07 | Edge: bloquear exclusão de cliente com veículos | Done | Verified |

**Coverage:** 7 total, 7 mapeados em tasks, 0 não mapeados ✅

---

## Critérios de Sucesso

- [x] Todas as telas e fluxos respondem conforme os critérios de aceite
- [x] Suíte de testes de feature cobre os 7 requisitos e passa no container (21 testes)
- [x] Banco íntegro: impossível criar veículo órfão ou duplicar CPF/placa

# Estado do Projeto

## Decisões

| Data | Decisão | Motivo |
| ---- | ------- | ------ |
| 2026-06-12 | Relação Cliente 1:N Veículo | Um cliente pode ter vários carros; um veículo pertence a um único cliente |
| 2026-06-12 | Excluir cliente bloqueado se houver veículos (HTTP 409) | Evita perda silenciosa de dados em cascata; o operador remove os veículos primeiro |
| 2026-06-12 | Placa aceita formato antigo (ABC1234) e Mercosul (ABC1D23), armazenada em maiúsculas | Realidade brasileira atual |
| 2026-06-12 | CPF armazenado só com dígitos (11 chars), validação de formato (não de dígito verificador) | Simplicidade adequada ao projeto de treinamento |
| 2026-06-12 | Sem autenticação na fase 1 | TokenController antigo foi removido; JWT fica para a fase 4 |
| 2026-06-12 | Testes de feature com SQLite em memória (phpunit.xml) | Não polui o banco MySQL de desenvolvimento |
| 2026-06-12 | **Arquitetura MVC com Blade, NÃO API JSON** (correção do usuário) | Rotas web com views server-rendered, formulários HTML, redirects + flash messages. `routes/api.php` fica vazio |
| 2026-06-12 | Busca por placa é um filtro na listagem de veículos (`/veiculos?placa=`) | Mais natural em UI web do que endpoint dedicado |
| 2026-06-12 | Tabela `veiculos` antiga (placa/nome, 2 registros) descartada após backup | Estrutura incompatível; backup em `.specs/features/cadastro-clientes-veiculos/backup_veiculos_antiga_2026-06-12.sql` |

## Bloqueios

Nenhum.

## Lições / Notas

- O repositório tinha um CRUD de "Agenda" que foi apagado do working tree (deleções ainda não commitadas pelo usuário). `routes/api.php` ainda importava controllers apagados — corrigido na fase 1.
- Containers em execução: `parking_teste` (app) e `db_parking_teste` (MySQL).

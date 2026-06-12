# Cadastro de Clientes e Veículos — Tasks

**Design**: `.specs/features/cadastro-clientes-veiculos/design.md`
**Status**: Done ✅

## Plano de Execução

```
Fase 1 (sequencial):   T1 → T2
Fase 2 (paralelo):     T2 ──┬→ T3 [P]
                            └→ T4 [P]
Fase 3 (paralelo):     T3,T4 ─┬→ T5 [P]
                              ├→ T6 [P]
                              └→ T9 [P]
Fase 4 (sequencial):   T5,T6,T9 → T7 → T8
```

---

### T1: Migration de clientes ✅

**What**: Migration `create_clientes_table` com nome, cpf (unique), email, telefone
**Where**: `src/database/migrations/2026_06_12_000001_create_clientes_table.php`
**Depends on**: None · **Requirement**: CAD-01, CAD-02

### T2: Migration de veículos ✅

**What**: Migration `create_veiculos_table` com cliente_id FK (restrict), placa (unique), marca, modelo, cor
**Where**: `src/database/migrations/2026_06_12_000002_create_veiculos_table.php`
**Depends on**: T1 · **Requirement**: CAD-03, CAD-04, CAD-07
**Nota**: havia uma tabela `veiculos` antiga incompatível (placa/nome) no MySQL de dev — descartada após backup (`backup_veiculos_antiga_2026-06-12.sql` nesta pasta).

### T3: Models Cliente e Veiculo + factories [P] ✅

**Where**: `src/app/Models/{Cliente,Veiculo}.php`, `src/database/factories/*Factory.php`
**Depends on**: T2 · **Requirement**: CAD-01, CAD-03

### T4: FormRequests de Cliente e Veiculo [P] ✅

**What**: Store/Update requests com validação e normalização (CPF só dígitos, placa maiúscula)
**Where**: `src/app/Http/Requests/`
**Depends on**: T2 · **Requirement**: CAD-02, CAD-04

### T5: ClienteController (web) [P] ✅

**What**: CRUD com views/redirects/flash + bloqueio de delete com veículos
**Where**: `src/app/Http/Controllers/ClienteController.php`
**Depends on**: T3, T4 · **Requirement**: CAD-01, CAD-05, CAD-07

### T6: VeiculoController (web) [P] ✅

**What**: CRUD com views/redirects/flash + filtro por placa na listagem
**Where**: `src/app/Http/Controllers/VeiculoController.php`
**Depends on**: T3, T4 · **Requirement**: CAD-03, CAD-06

### T9: Views Blade [P] ✅ *(adicionada no pivô para MVC)*

**What**: Layout base (Bootstrap 5, navbar, flash) + index/create/edit/show/_form por entidade; `Paginator::useBootstrap()`
**Where**: `src/resources/views/`, `src/app/Providers/AppServiceProvider.php`
**Depends on**: T3, T4 · **Requirement**: CAD-01..CAD-06

### T7: Rotas web ✅

**What**: `web.php` com `/` → redirect + `Route::resource` de clientes e veículos; `api.php` esvaziado (imports quebrados de Agenda/Token removidos)
**Where**: `src/routes/web.php`, `src/routes/api.php`
**Depends on**: T5, T6, T9 · **Requirement**: CAD-01..CAD-06
**Verificado**: `php artisan route:list` — 16 rotas

### T8: phpunit.xml (SQLite) + testes de feature ✅

**What**: SQLite :memory: + ClienteCadastroTest (9) + VeiculoCadastroTest (10) + ExampleTest ajustado
**Where**: `src/phpunit.xml`, `src/tests/Feature/`
**Depends on**: T7 · **Requirement**: todos
**Verificado**: `php artisan test` — **21 passed**, MySQL de dev intocado

---

## Histórico

- A primeira implementação foi feita como API JSON; o usuário corrigiu o escopo ("é MVC com Laravel, não API"). Controllers, rotas e testes foram convertidos para o fluxo web; migrations, models e FormRequests foram mantidos. T9 (views) foi adicionada.

## Status

| Task | Status |
| ---- | ------ |
| T1–T9 | Done ✅ |

**Gate final**: 21 testes passando · smoke HTTP: `/` 302→/clientes, `/clientes` 200, `/veiculos` 200, forms 200.

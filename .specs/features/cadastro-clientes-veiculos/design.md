# Cadastro de Clientes e Veículos — Design

**Spec**: `.specs/features/cadastro-clientes-veiculos/spec.md`
**Arquitetura**: MVC com Blade (Laravel 8)

## Modelo de Dados

```
clientes                      veiculos
─────────                     ─────────
id (PK)                       id (PK)
nome        string(120)       cliente_id (FK → clientes.id, restrict)
cpf         char(11) UNIQUE   placa      char(7) UNIQUE
email       string nullable   marca      string(60)
telefone    string(20) null.  modelo     string(60)
timestamps                    cor        string(30) nullable
                              timestamps

Cliente 1 ──── N Veiculo   (hasMany / belongsTo)
```

- FK com `restrict` no delete: a exclusão de cliente com veículos falha no banco; o controller intercepta antes e redireciona com flash de erro (CAD-07).
- `cpf`: somente dígitos, normalizado no FormRequest (`prepareForValidation`).
- `placa`: maiúsculas, normalizada no FormRequest; regex `^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$` cobre ABC1234 e ABC1D23.

## Camadas (MVC padrão Laravel, sem camada de serviço — escopo não justifica)

```
Route (web.php)
  → FormRequest (validação + normalização; falha = redirect back + errors na sessão)
    → Controller (orquestra, redirect + flash ou render de view)
      → Model Eloquent (relações, mass assignment)
      → View Blade (layouts/app + views por entidade, Bootstrap 5 via CDN)
```

## Rotas Web

| Método | Rota | Ação | Comportamento |
| ------ | ---- | ---- | ------------- |
| GET | / | closure | redirect → clientes.index |
| GET | /clientes | ClienteController@index | listagem paginada com contagem de veículos |
| GET | /clientes/create | @create | formulário |
| POST | /clientes | @store | salva → redirect index + flash success |
| GET | /clientes/{cliente} | @show | detalhes + veículos do cliente |
| GET | /clientes/{cliente}/edit | @edit | formulário preenchido |
| PUT | /clientes/{cliente} | @update | atualiza → redirect index + flash |
| DELETE | /clientes/{cliente} | @destroy | exclui, ou flash error se tiver veículos |
| GET | /veiculos | VeiculoController@index | listagem paginada com cliente; filtro `?placa=` |
| GET | /veiculos/create | @create | formulário com select de clientes (`?cliente_id=` pré-seleciona) |
| POST | /veiculos | @store | salva → redirect index + flash |
| GET | /veiculos/{veiculo} | @show | detalhes com dono |
| GET | /veiculos/{veiculo}/edit | @edit | formulário preenchido |
| PUT | /veiculos/{veiculo} | @update | atualiza → redirect index + flash |
| DELETE | /veiculos/{veiculo} | @destroy | exclui → redirect index + flash |

`routes/api.php` fica vazio (reservado para integrações futuras).

## Validação

**StoreClienteRequest** — nome: required|string|max:120 · cpf: required|digits:11|unique:clientes · email: nullable|email|max:255 · telefone: nullable|string|max:20
**UpdateClienteRequest** — iguais, com `sometimes` e `unique` ignorando o próprio id
**StoreVeiculoRequest** — cliente_id: required|exists:clientes,id · placa: required|regex|unique:veiculos · marca/modelo: required|string|max:60 · cor: nullable|string|max:30
**UpdateVeiculoRequest** — iguais, com `sometimes` e `unique` ignorando o próprio id

## Arquivos

```
src/database/migrations/2026_06_12_000001_create_clientes_table.php
src/database/migrations/2026_06_12_000002_create_veiculos_table.php
src/app/Models/Cliente.php
src/app/Models/Veiculo.php
src/app/Http/Requests/{Store,Update}ClienteRequest.php
src/app/Http/Requests/{Store,Update}VeiculoRequest.php
src/app/Http/Controllers/ClienteController.php
src/app/Http/Controllers/VeiculoController.php
src/app/Providers/AppServiceProvider.php          (Paginator::useBootstrap())
src/database/factories/{Cliente,Veiculo}Factory.php
src/resources/views/layouts/app.blade.php          (navbar, flash messages, Bootstrap 5 CDN)
src/resources/views/clientes/{index,create,edit,show,_form}.blade.php
src/resources/views/veiculos/{index,create,edit,show,_form}.blade.php
src/routes/web.php                                 (rotas resource)
src/routes/api.php                                 (esvaziado — imports quebrados removidos)
src/phpunit.xml                                    (SQLite :memory: habilitado)
src/tests/Feature/ClienteCadastroTest.php
src/tests/Feature/VeiculoCadastroTest.php
src/tests/Feature/ExampleTest.php                  (ajustado: '/' redireciona p/ clientes)
```

## Testes

- Feature tests (HTTP web: forms, redirects, sessão) com `RefreshDatabase` + SQLite em memória — não tocam o MySQL de dev.
- Gate: `docker exec parking_teste sh -c "cd /var/www/app && php artisan test"` — 21 testes.

## Notas de implementação

- `@selected` não existe no Laravel 8 — usar expressão ternária no Blade.
- `Paginator::useBootstrapFive()` indisponível no vendor instalado — usar `useBootstrap()` (markup compatível com BS5).
- CSRF é ignorado automaticamente em ambiente de teste (`runningUnitTests`).

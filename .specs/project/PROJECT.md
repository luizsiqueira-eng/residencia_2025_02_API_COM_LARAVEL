# Sistema de Estacionamento de Shopping

## Visão

Aplicação web MVC em Laravel (Blade) para gerenciar o estacionamento de um shopping center. A primeira fase cobre o cadastro de clientes e seus veículos — base para fases futuras (entrada/saída, cobrança, mensalistas).

## Objetivos

- Cadastro completo de clientes (CRUD via formulários web)
- Cadastro completo de veículos vinculados a clientes (CRUD via formulários web)
- Base de dados consistente: CPF e placa únicos, vínculo cliente→veículos garantido por FK

## Stack

- PHP 8.0 / Laravel 8.83 (rodando em Docker, container `parking_teste`)
- MySQL 5.7 (container `db_parking_teste`, host interno `db`)
- JWT (firebase/php-jwt) disponível, mas autenticação está fora do escopo da fase 1
- Código-fonte em `src/` (montado em `/var/www/app` no container)

## Roadmap (alto nível)

| Fase | Feature | Status |
| ---- | ------- | ------ |
| 1 | Cadastro de clientes e veículos | Em andamento |
| 2 | Fluxo de entrada/saída (tickets) | Futuro |
| 3 | Cobrança / tabela de preços | Futuro |
| 4 | Autenticação de operadores (JWT) | Futuro |

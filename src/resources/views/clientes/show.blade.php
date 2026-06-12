@extends('layouts.app')

@section('title', $cliente->nome)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ $cliente->nome }}</h1>
        <div>
            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-outline-primary">Editar</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-link">Voltar</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-2">CPF</dt>
                <dd class="col-sm-10">{{ $cliente->cpf }}</dd>
                <dt class="col-sm-2">E-mail</dt>
                <dd class="col-sm-10">{{ $cliente->email ?? '—' }}</dd>
                <dt class="col-sm-2">Telefone</dt>
                <dd class="col-sm-10">{{ $cliente->telefone ?? '—' }}</dd>
            </dl>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 mb-0">Veículos do cliente</h2>
        <a href="{{ route('veiculos.create', ['cliente_id' => $cliente->id]) }}" class="btn btn-sm btn-primary">Adicionar veículo</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Cor</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cliente->veiculos as $veiculo)
                        <tr>
                            <td><span class="badge text-bg-dark">{{ $veiculo->placa }}</span></td>
                            <td>{{ $veiculo->marca }}</td>
                            <td>{{ $veiculo->modelo }}</td>
                            <td>{{ $veiculo->cor ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('veiculos.edit', $veiculo) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhum veículo cadastrado para este cliente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

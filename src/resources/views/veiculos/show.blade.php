@extends('layouts.app')

@section('title', 'Veículo ' . $veiculo->placa)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Veículo {{ $veiculo->placa }}</h1>
        <div>
            <a href="{{ route('veiculos.edit', $veiculo) }}" class="btn btn-outline-primary">Editar</a>
            <a href="{{ route('veiculos.index') }}" class="btn btn-link">Voltar</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-2">Placa</dt>
                <dd class="col-sm-10"><span class="badge text-bg-dark">{{ $veiculo->placa }}</span></dd>
                <dt class="col-sm-2">Marca</dt>
                <dd class="col-sm-10">{{ $veiculo->marca }}</dd>
                <dt class="col-sm-2">Modelo</dt>
                <dd class="col-sm-10">{{ $veiculo->modelo }}</dd>
                <dt class="col-sm-2">Cor</dt>
                <dd class="col-sm-10">{{ $veiculo->cor ?? '—' }}</dd>
                <dt class="col-sm-2">Cliente</dt>
                <dd class="col-sm-10">
                    <a href="{{ route('clientes.show', $veiculo->cliente) }}">{{ $veiculo->cliente->nome }}</a>
                    — CPF {{ $veiculo->cliente->cpf }}
                </dd>
            </dl>
        </div>
    </div>
@endsection

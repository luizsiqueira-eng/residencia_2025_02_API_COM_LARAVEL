@extends('layouts.app')

@section('title', 'Veículos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Veículos</h1>
        <a href="{{ route('veiculos.create') }}" class="btn btn-primary">Novo veículo</a>
    </div>

    <form method="GET" action="{{ route('veiculos.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="placa" class="form-control" placeholder="Buscar por placa"
                   value="{{ request('placa') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-secondary">Buscar</button>
            @if (request('placa'))
                <a href="{{ route('veiculos.index') }}" class="btn btn-link">Limpar</a>
            @endif
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Cor</th>
                        <th>Cliente</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($veiculos as $veiculo)
                        <tr>
                            <td><span class="badge text-bg-dark">{{ $veiculo->placa }}</span></td>
                            <td>{{ $veiculo->marca }}</td>
                            <td>{{ $veiculo->modelo }}</td>
                            <td>{{ $veiculo->cor ?? '—' }}</td>
                            <td>
                                <a href="{{ route('clientes.show', $veiculo->cliente) }}">{{ $veiculo->cliente->nome }}</a>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('veiculos.edit', $veiculo) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="{{ route('veiculos.destroy', $veiculo) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Excluir o veículo {{ $veiculo->placa }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                @if (request('placa'))
                                    Nenhum veículo encontrado para a placa "{{ request('placa') }}".
                                @else
                                    Nenhum veículo cadastrado.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $veiculos->links() }}
    </div>
@endsection

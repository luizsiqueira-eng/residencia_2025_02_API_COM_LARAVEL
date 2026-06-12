@extends('layouts.app')

@section('title', 'Editar veículo')

@section('content')
    <h1 class="h3 mb-3">Editar veículo {{ $veiculo->placa }}</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('veiculos.update', $veiculo) }}" method="POST">
                @csrf
                @method('PUT')
                @include('veiculos._form')
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-link">Cancelar</a>
            </form>
        </div>
    </div>
@endsection

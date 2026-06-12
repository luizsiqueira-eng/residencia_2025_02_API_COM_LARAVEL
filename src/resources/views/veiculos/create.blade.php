@extends('layouts.app')

@section('title', 'Novo veículo')

@section('content')
    <h1 class="h3 mb-3">Novo veículo</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('veiculos.store') }}" method="POST">
                @csrf
                @include('veiculos._form')
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-link">Cancelar</a>
            </form>
        </div>
    </div>
@endsection

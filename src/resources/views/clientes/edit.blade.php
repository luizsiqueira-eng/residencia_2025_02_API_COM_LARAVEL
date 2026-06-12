@extends('layouts.app')

@section('title', 'Editar cliente')

@section('content')
    <h1 class="h3 mb-3">Editar cliente</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                @csrf
                @method('PUT')
                @include('clientes._form')
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-link">Cancelar</a>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Novo cliente')

@section('content')
    <h1 class="h3 mb-3">Novo cliente</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf
                @include('clientes._form')
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('clientes.index') }}" class="btn btn-link">Cancelar</a>
            </form>
        </div>
    </div>
@endsection

<div class="mb-3">
    <label for="cliente_id" class="form-label">Cliente *</label>
    <select name="cliente_id" id="cliente_id" required
            class="form-select @error('cliente_id') is-invalid @enderror">
        <option value="">Selecione…</option>
        @foreach ($clientes as $clienteOption)
            <option value="{{ $clienteOption->id }}"
                {{ old('cliente_id', $veiculo->cliente_id ?? $clienteSelecionado ?? null) == $clienteOption->id ? 'selected' : '' }}>
                {{ $clienteOption->nome }} — CPF {{ $clienteOption->cpf }}
            </option>
        @endforeach
    </select>
    @error('cliente_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="placa" class="form-label">Placa *</label>
    <input type="text" name="placa" id="placa" maxlength="8" placeholder="ABC1234 ou ABC1D23" required
           class="form-control text-uppercase @error('placa') is-invalid @enderror"
           value="{{ old('placa', $veiculo->placa ?? '') }}">
    @error('placa') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="marca" class="form-label">Marca *</label>
    <input type="text" name="marca" id="marca" maxlength="60" required
           class="form-control @error('marca') is-invalid @enderror"
           value="{{ old('marca', $veiculo->marca ?? '') }}">
    @error('marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="modelo" class="form-label">Modelo *</label>
    <input type="text" name="modelo" id="modelo" maxlength="60" required
           class="form-control @error('modelo') is-invalid @enderror"
           value="{{ old('modelo', $veiculo->modelo ?? '') }}">
    @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="cor" class="form-label">Cor</label>
    <input type="text" name="cor" id="cor" maxlength="30"
           class="form-control @error('cor') is-invalid @enderror"
           value="{{ old('cor', $veiculo->cor ?? '') }}">
    @error('cor') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

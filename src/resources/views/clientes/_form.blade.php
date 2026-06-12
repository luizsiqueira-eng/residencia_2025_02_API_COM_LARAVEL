<div class="mb-3">
    <label for="nome" class="form-label">Nome *</label>
    <input type="text" name="nome" id="nome" maxlength="120" required
           class="form-control @error('nome') is-invalid @enderror"
           value="{{ old('nome', $cliente->nome ?? '') }}">
    @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="cpf" class="form-label">CPF *</label>
    <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" required
           class="form-control @error('cpf') is-invalid @enderror"
           value="{{ old('cpf', $cliente->cpf ?? '') }}">
    @error('cpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">E-mail</label>
    <input type="email" name="email" id="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $cliente->email ?? '') }}">
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="text" name="telefone" id="telefone" maxlength="20"
           class="form-control @error('telefone') is-invalid @enderror"
           value="{{ old('telefone', $cliente->telefone ?? '') }}">
    @error('telefone') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

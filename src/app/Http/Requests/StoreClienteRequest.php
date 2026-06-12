<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('cpf')) {
            $this->merge(['cpf' => preg_replace('/\D/', '', (string) $this->cpf)]);
        }
    }

    public function rules()
    {
        return [
            'nome' => ['required', 'string', 'max:120'],
            'cpf' => ['required', 'digits:11', 'unique:clientes,cpf'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
        ];
    }
}

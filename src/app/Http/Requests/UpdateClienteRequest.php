<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
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
            'nome' => ['sometimes', 'required', 'string', 'max:120'],
            'cpf' => [
                'sometimes',
                'required',
                'digits:11',
                Rule::unique('clientes', 'cpf')->ignore($this->route('cliente')),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
        ];
    }
}

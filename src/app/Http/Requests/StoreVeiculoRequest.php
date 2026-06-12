<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVeiculoRequest extends FormRequest
{
    public const PLACA_REGEX = '/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/';

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('placa')) {
            $this->merge(['placa' => strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', (string) $this->placa))]);
        }
    }

    public function rules()
    {
        return [
            'cliente_id' => ['required', 'integer', 'exists:clientes,id'],
            'placa' => ['required', 'regex:' . self::PLACA_REGEX, 'unique:veiculos,placa'],
            'marca' => ['required', 'string', 'max:60'],
            'modelo' => ['required', 'string', 'max:60'],
            'cor' => ['nullable', 'string', 'max:30'],
        ];
    }

    public function messages()
    {
        return [
            'placa.regex' => 'A placa deve estar no formato ABC1234 ou ABC1D23 (Mercosul).',
        ];
    }
}

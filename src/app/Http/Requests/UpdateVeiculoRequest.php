<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVeiculoRequest extends FormRequest
{
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
            'cliente_id' => ['sometimes', 'required', 'integer', 'exists:clientes,id'],
            'placa' => [
                'sometimes',
                'required',
                'regex:' . StoreVeiculoRequest::PLACA_REGEX,
                Rule::unique('veiculos', 'placa')->ignore($this->route('veiculo')),
            ],
            'marca' => ['sometimes', 'required', 'string', 'max:60'],
            'modelo' => ['sometimes', 'required', 'string', 'max:60'],
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

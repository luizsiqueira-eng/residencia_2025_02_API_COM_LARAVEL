<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class ConteudoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'papel' => 'required|string|max:255',
            'conteudo' => 'required|string|min:20',
            'status' => ['required', Rule::in(['aprovado', 'reprovado'])],
            'motivo_reprovacao' => 'nullable|string|min:20|max:255', Rule::requiredIf($this->input('status') === 'reprovado')
        ];
    }
}

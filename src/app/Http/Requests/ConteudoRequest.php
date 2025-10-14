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
         if ($this->isMethod('post')) {
            return [
                'papel' => 'required|string|max:255',
                'conteudo' => 'required|string|min:20',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'papel' => 'sometimes|string|max:255',
                'conteudo' => 'sometimes|string|min:20',
            ];
        }

        return [];
    }
}

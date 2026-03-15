<?php

namespace App\Http\Requests\DiarioBordo;

use Illuminate\Foundation\Http\FormRequest;

class EncerrarDiarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assinante_nome' => ['required', 'string', 'max:255'],
            'assinatura_base64' => ['required', 'string'],
        ];
    }
}


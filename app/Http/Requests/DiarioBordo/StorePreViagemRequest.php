<?php

namespace App\Http\Requests\DiarioBordo;

use Illuminate\Foundation\Http\FormRequest;

class StorePreViagemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'carga_descricao' => ['nullable', 'string', 'max:1000'],
            'peso_carga_kg' => ['nullable', 'numeric', 'min:0'],
            'previsao_saida_em' => ['nullable', 'date'],
            'combustivel_percentual' => ['nullable', 'integer', 'between:0,100'],
            'observacoes' => ['nullable', 'string'],
        ];
    }
}


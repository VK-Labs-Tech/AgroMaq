<?php

namespace App\Http\Requests\DiarioBordo;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransitoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offline_id' => ['nullable', 'string', 'max:120'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'velocidade_kmh' => ['nullable', 'numeric', 'min:0'],
            'precisao_m' => ['nullable', 'numeric', 'min:0'],
            'registrado_em' => ['nullable', 'date'],
        ];
    }
}


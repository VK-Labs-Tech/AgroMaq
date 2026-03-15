<?php

namespace App\Http\Requests\DiarioBordo;

use Illuminate\Foundation\Http\FormRequest;

class StoreChecklistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.item' => ['required', 'string', 'max:255'],
            'items.*.marcado' => ['nullable', 'boolean'],
            'items.*.observacao' => ['nullable', 'string', 'max:1000'],
            'items.*.checado_em' => ['nullable', 'date'],
        ];
    }
}


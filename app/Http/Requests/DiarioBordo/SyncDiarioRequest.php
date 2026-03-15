<?php

namespace App\Http\Requests\DiarioBordo;

use Illuminate\Foundation\Http\FormRequest;

class SyncDiarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'diario_uuid' => ['nullable', 'string', 'max:120'],
            'device_id' => ['required', 'string', 'max:120'],
            'motorista_nome' => ['nullable', 'string', 'max:255'],
            'veiculo_identificacao' => ['nullable', 'string', 'max:255'],
            'origem' => ['nullable', 'string', 'max:255'],
            'destino' => ['nullable', 'string', 'max:255'],
            'actions' => ['required', 'array', 'min:1'],
            'actions.*.type' => ['required', 'string', 'in:pre_viagem,checklist,transito'],
            'actions.*.payload' => ['nullable', 'array'],
        ];
    }
}


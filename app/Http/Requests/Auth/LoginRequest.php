<?php

namespace App\Http\Requests\Auth;

use App\Dtos\Auth\LoginDto;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ];
    }
    public function messages(): array {
        return [
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Informe um e-mail válido',
            'password.required' => 'A senha é obrigatória'
        ];
    }

    public function toDTO(): LoginDto
    {
        return LoginDto::fromArray([
            'email'    => $this->string('email')->value(),
            'password' => $this->string('password')->value(),
            'remember' => $this->boolean('remember'),
        ]);
    }
}

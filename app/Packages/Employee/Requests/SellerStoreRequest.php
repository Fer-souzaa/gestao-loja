<?php

namespace App\Packages\Employee\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerStoreRequest extends FormRequest {

    /**
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255']
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'is_active.required' => 'O campo ativo é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
        ];
    }
}

<?php

namespace App\Packages\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest {
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
            'cpf' => ['nullable', 'string', 'max:14'],
            'registration_date' => ['nullable', 'date'],
            'billing_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'company_id.required' => 'A empresa é obrigatória.',
            'company_id.exists' => 'A empresa informada não existe.',
            'name.required' => 'O campo nome é obrigatório.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'registration_date.date' => 'A data de nascimento deve ser uma data válida.',
            'billing_date.date' => 'A data de cobrança deve ser uma data válida.',
        ];
    }
}

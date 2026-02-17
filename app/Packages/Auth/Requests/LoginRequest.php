<?php

namespace App\Packages\Auth\Requests;

use App\Base\Traits\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {

    use Response;

    public function rules(): array {
        return [
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:150'
        ];
    }

    public function authorize(): bool {
        return true;
    }

    public function attributes(): array {
        return [
            'username' => 'Usuário',
            'password' => 'Senha'
        ];
    }

    protected function failedValidation(Validator $validator) {
        return self::failedValidationResponse($validator);
    }

}

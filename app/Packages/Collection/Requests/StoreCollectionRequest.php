<?php

namespace App\Packages\Collection\Requests;

use App\Base\Traits\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreCollectionRequest extends FormRequest {

    use Response;

    public function rules(): array {
        return [
            'name' => 'required|string|max:100',
            'active' => 'nullable|boolean'
        ];
    }

    public function authorize(): bool {
        return true;
    }

    public function attributes(): array {
        return [
            'name' => 'Nome',
            'active' => 'Ativo'
        ];
    }

    protected function failedValidation(Validator $validator) {
        return self::failedValidationResponse($validator);
    }

}

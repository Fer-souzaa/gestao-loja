<?php

namespace App\Packages\Customer\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerDetailResource extends JsonResource {
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->resource['id'],
            'total_purchases' => 0,
            'total_spent' => 0,
            'pending_value' => 0,
            'name' => $this->resource['name'],
            'phone' => $this->resource['phone'],
            'cpf' => $this->resource['cpf'],
            'birth_date' => $this->resource['registration_date'],
            'billing_date' => $this->resource['billing_date'],
            'address' => $this->resource['address'],
            'purchase_history' => []
        ];
    }
}

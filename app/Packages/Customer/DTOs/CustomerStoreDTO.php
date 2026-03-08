<?php

namespace App\Packages\Customer\DTOs;

class CustomerStoreDTO
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $cpf = null,
        public ?string $registration_date = null,
        public ?string $billing_date = null,
        public ?string $address = null,
    ) {
        $this->phone = removeMask($this->phone);
        if ($this->cpf) {
            $this->cpf = removeMask($this->cpf);
        }
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            cpf: $data['cpf'] ?? null,
            registration_date: $data['registration_date'] ?? null,
            billing_date: $data['billing_date'] ?? null,
            address: $data['address'] ?? null,
        );
    }
}

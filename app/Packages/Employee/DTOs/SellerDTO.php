<?php

namespace App\Packages\Employee\DTOs;

class SellerDTO {
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $email = null
    ) {
        $this->phone = removeMask($this->phone);
        if ($this->email) {
            $this->email = trim(strtolower($this->email));
        }
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromRequest(array $data): self {
        return new self(
            name: $data['name'],
            phone: $data['phone'],
            email: $data['email'] ?? null
        );
    }
}

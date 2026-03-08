<?php

namespace App\Packages\Employee\DTOs;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EmployeeListDTO {
    public function __construct(
        public LengthAwarePaginator $employees
    ) {}

    /**
     * @param LengthAwarePaginator $paginator
     * @return array
     */
    public static function format(LengthAwarePaginator $paginator): array {
        return [
            'data' => collect($paginator->items())->map(function ($item) {
                return [
                    'name' => $item->name,
                    'phone' => $item->phone,
                    'email' => $item->email,
                    'start_at' => $item->start_at,
                ];
            }),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }
}

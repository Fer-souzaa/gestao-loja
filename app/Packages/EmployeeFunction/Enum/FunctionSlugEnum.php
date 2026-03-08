<?php

namespace App\Packages\EmployeeFunction\Enum;

use App\Packages\EmployeeFunction\Models\EmployeeFunction;
use Illuminate\Database\Eloquent\ModelNotFoundException;

enum FunctionSlugEnum: string {

    case GESTOR = 'gestor';
    case VENDEDOR = 'vendedor';

    /**
     * @param $function
     * @return int|mixed
     */
    public static function getId($function): mixed {
        return match ($function) {
            self::GESTOR => EmployeeFunction::firstWhere('slug', self::GESTOR->value)->id,
            self::VENDEDOR => EmployeeFunction::firstWhere('slug', self::VENDEDOR->value)->id,
            default => throw new ModelNotFoundException('Função no encontrada!')
        };
    }
}

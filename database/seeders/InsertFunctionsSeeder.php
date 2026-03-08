<?php

namespace Database\Seeders;

use App\Packages\EmployeeFunction\Models\EmployeeFunction;
use Illuminate\Database\Seeder;
use DB;
use Exception;
class InsertFunctionsSeeder extends Seeder {
    public function run(): void {
        try {
            DB::beginTransaction();
            $functions = [
                [
                    'name' => 'Gestor',
                    'slug' => 'gestor'
                ],
                [
                    'name' => 'Vendedor',
                    'slug' => 'vendedor'
                ]
            ];

            foreach ($functions as $function) {
                EmployeeFunction::firstOrCreate($function);
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}

<?php

namespace Database\Seeders;
use App\Packages\Auth\Models\User;
use App\Packages\Company\Models\Company;
use App\Packages\Employee\Models\Employee;
use App\Packages\EmployeeFunction\Models\EmployeeFunction;
use App\Packages\Person\Models\Person;
use DB;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class InsertUserSeeder extends Seeder {
    public function run(): void {
        try {
            DB::beginTransaction();

            $company = Company::firstOrCreate([
                'name' => 'Empresa Administradora',
                'cnpj' => null,
                'email' => 'fernandorr.fs@gmail.com',
                'phone' => '95988034527',
            ]);

            $person = Person::firstOrCreate([
                'name' => 'Fernando Santos Souza',
                'cpf' => '03368200208',
                'phone' => '95988034527',
                'email' => 'fernandorr.fs@gmail.com',
            ]);

            User::firstOrCreate([
                'person_id' => $person->id,
                'username' => 'fernando',
                'password' => Hash::make('An@andaF1'),
                'active' => true,
            ]);

            Employee::firstOrCreate([
                'person_id' => $person->id,
                'company_id' => $company->id,
                'employee_function_id' => EmployeeFunction::firstWhere('slug', 'gestor')->id,
                'start_at' => now(),
                'ends_at' => null,
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}

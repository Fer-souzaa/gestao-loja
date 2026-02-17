<?php

namespace App\Console\Commands;

use App\Packages\Auth\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command {
    protected $signature = 'create:user';

    protected $description = 'Command description';

    public function handle(): void {
        User::firstOrCreate([
            'username' => 'admin',
            'password' => Hash::make('123456789'),
            'active' => true
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'owner@renatha.test'], [
            'name' => 'Renatha (Owner)',
            'role' => User::ROLE_OWNER,
            'is_active' => true,
            'password' => 'password',
        ]);

        User::updateOrCreate(['email' => 'caretaker@renatha.test'], [
            'name' => 'Caretaker',
            'role' => User::ROLE_CARETAKER,
            'is_active' => true,
            'password' => 'password',
        ]);
    }
}

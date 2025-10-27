<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\SystemUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = SystemUser::actAs(function () {
            return User::create([
                'name' => 'Sergio Rento Aldana Alvarez',
                'email' => 'aldana.sergio@correoe.usac.edu.gt',
                'password' => Hash::make('12345'),
            ]);
        });

        $this->command->info("Usuario creado: {$user->email}");
    }
}

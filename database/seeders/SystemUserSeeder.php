<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{
    public const SYSTEM_USER_ID = 1;

    public const SYSTEM_USER_EMAIL = 'system@archivovertical.local';

    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Eliminar usuario sistema existente si existe
        User::where('id', self::SYSTEM_USER_ID)->forceDelete();

        // Crear usuario sistema con ID fijo
        User::withoutEvents(function () {
            User::create([
                'id' => self::SYSTEM_USER_ID,
                'name' => 'Sistema',
                'email' => self::SYSTEM_USER_EMAIL,
                'password' => Hash::make('system-'.bin2hex(random_bytes(16))),
                'email_verified_at' => now(),
            ]);
        });

        $this->command->info('✓ Usuario sistema creado con ID: '.self::SYSTEM_USER_ID);
    }
}

<?php

namespace App\Support;

use App\Models\User;
use Database\Seeders\SystemUserSeeder;
use Illuminate\Support\Facades\Auth;

class SystemUser
{
    /**
     * ID del usuario sistema.
     */
    public const ID = SystemUserSeeder::SYSTEM_USER_ID;

    /**
     * Email del usuario sistema.
     */
    public const EMAIL = SystemUserSeeder::SYSTEM_USER_EMAIL;

    /**
     * Obtener el ID del usuario sistema o del usuario autenticado.
     */
    public static function id(): int
    {
        return Auth::id() ?? self::ID;
    }

    /**
     * Obtener el usuario sistema.
     */
    public static function get(): ?User
    {
        return User::find(self::ID);
    }

    /**
     * Ejecutar un callback autenticado como usuario sistema.
     */
    public static function actAs(callable $callback): mixed
    {
        $previousUser = Auth::user();
        $systemUser = self::get();

        if (! $systemUser) {
            throw new \RuntimeException('Usuario sistema no encontrado. Ejecute: php artisan db:seed --class=SystemUserSeeder');
        }

        Auth::login($systemUser);

        try {
            return $callback();
        } finally {
            if ($previousUser) {
                Auth::login($previousUser);
            } else {
                Auth::logout();
            }
        }
    }

    /**
     * Verificar si el usuario actual es el usuario sistema.
     */
    public static function isCurrent(): bool
    {
        return Auth::id() === self::ID;
    }

    /**
     * Verificar si existe el usuario sistema.
     */
    public static function exists(): bool
    {
        return User::where('id', self::ID)->exists();
    }
}

<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getBadgeColor($role): string
    {
        return match ($role) {
            self::ADMIN->value => 'purple',
            self::TEACHER->value => 'blue',
            self::STUDENT->value => 'green',
            default => 'gray'
        };
    }
}
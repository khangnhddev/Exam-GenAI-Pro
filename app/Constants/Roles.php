<?php

namespace App\Constants;

class Roles
{
    const SUPER_ADMIN = 'super-admin';
    const ADMIN = 'admin';
    const INSTRUCTOR = 'instructor';
    const STUDENT = 'student';

    public static function all(): array
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::INSTRUCTOR,
            self::STUDENT
        ];
    }
}
<?php

namespace App\DataFixtures\Faker;

use Faker\Provider\Base as BaseProvider;

final class Provider extends BaseProvider
{
    protected static $roles = [
        ['ROLE_USER'],
        ['ROLE_ADMIN'],
    ];

    public static function roles()
    {
        return static::randomElement(static::$roles);
    }
}

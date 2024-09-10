<?php

namespace App\Utils;

enum Roles: string
{
    case ADMIN = 'ROLE_ADMIN';
    case USER = 'ROLE_USER';
}

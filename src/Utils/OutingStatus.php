<?php

namespace App\Utils;

use App\Entity\Outing;

enum OutingStatus: string
{
    case PASSED = 'PASSEE';
    case CLOSED = 'CLOTUREE';
    case ONGOING = 'EN COURS';
    case HISTORY = 'HISTORISEE';
    case OPEN = 'OUVERT';
    case CREATION = 'EN CREATION';
}

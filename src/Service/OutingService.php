<?php

namespace App\Service;

use App\Entity\Outing;
use App\Utils\OutingStatus;

class OutingService
{

    public function calculateOutingState(Outing $outing): string
    {
        $now = new \DateTime();
        $outingDate = $outing->getOutingDate();
        $registrationDeadline = $outing->getRegistrationDeadline();
        $oneMonthAfterOuting = (clone $outingDate)->modify('+1 month');

        if (!$outingDate || !$registrationDeadline) {
            throw new \LogicException('Les dates de la sortie doivent être définies.');
        }

        if ($now > $outingDate) {
            return OutingStatus::PASSED->value;
        } elseif ($now > $registrationDeadline) {
            return OutingStatus::CLOSED->value;
        } elseif ($now > $oneMonthAfterOuting) {
            return OutingStatus::HISTORY->value;
        }
        else {
            return OutingStatus::ONGOING->value;
        }
    }
}

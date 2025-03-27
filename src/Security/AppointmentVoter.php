<?php

namespace App\Security;

use App\Entity\Appointment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AppointmentVoter extends Voter
{
    const VIEW = 'view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if(!in_array($attribute, [self::VIEW])){
            return false;
        }

        
    }
}
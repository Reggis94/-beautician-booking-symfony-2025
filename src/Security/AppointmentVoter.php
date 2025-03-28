<?php

namespace App\Security;

use App\Entity\Appointment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AppointmentVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // dump(!in_array($attribute, [self::VIEW]));exit;
        if(!in_array($attribute, [self::VIEW, self::EDIT])){
            return false;
        }
        // dump("HERE");exit;

        if(!$subject instanceof Appointment){
            return false;
        }
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool 
    {
        $user = $token->getUser();

        if(!$user instanceof User){
            return false;
        }

        $appointment = $subject;
        return match($attribute){
            self::VIEW => $this->canView($appointment, $user),
            self::EDIT => $this->canEdit($appointment, $user),
            default => false
        };
    }

    private function canView(Appointment $appointment, User $user): bool
    {
        // dump('canView');exit;
        return $this->doesBelongToBusiness($appointment, $user);
    }

    private function canEdit(Appointment $appointment, User $user): bool
    {
        // dump('canView');exit;
        return $this->doesBelongToBusiness($appointment, $user);
    }

    private function doesBelongToBusiness(Appointment $appointment, User $user): bool
    {
        // dump('doesBelongToBusiness');exit;
        return $appointment->getBusiness()->getBusinessUser() === $user && $appointment->getDeletedAt() === null;
    }
}
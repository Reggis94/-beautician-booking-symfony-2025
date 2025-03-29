<?php

namespace App\Security;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ServiceVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function supports(string $attribute, mixed $subject): bool
    {
        if(!in_array($attribute, [self::VIEW, self::EDIT])){
            return false;
        }

        if(!$subject instanceof Service){
            return false;
        }
        return true;
    }

    public function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        return match($attribute){
            self::VIEW => $this->canView($attribute, $subject, $user),
            self::EDIT => $this->canEdit($attribute, $subject, $user),
            default => false,
        };
    }

    public function canView($attribute, $subject, $user){
        return $this->doesBelongToBusiness($subject, $subject, $user);
    }

    public function canEdit($attribute, $subject, $user){
        return $this->doesBelongToBusiness($subject, $subject, $user);
    }

    public function doesBelongToBusiness($service, $subject, $user): bool
    {
        $service = $this->em->getRepository(Service::class)->findLatestVersion($subject);
        //TODO: check service is not deleted
        if(!$service){
            return false;
        }

        if(!($service->getBusiness()->getBusinessUser() === $user)){
            return false;
        }
        return true;
    }
}
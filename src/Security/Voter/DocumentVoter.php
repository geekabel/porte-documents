<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class DocumentVoter extends Voter
{
    const CAN_EDIT = 'CAN_EDIT';
    const CAN_CREAT = 'CAN_CREAT';
    const CAN_DELETE = 'CAN_DELETE';
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CAN_CREAT,self::CAN_DELETE,self::CAN_EDIT])
            && $subject instanceof \App\Entity\Document;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case (self::CAN_DELETE || self::CAN_EDIT || self::CAN_DELETE):
                return in_array("ROLE_ADMIN",$user->getRoles());
                break;

             default:
               return false;
                break;
        }

        return false;
    }
}

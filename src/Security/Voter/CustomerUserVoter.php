<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerUserVoter extends Voter
{
    public const VERIFIED = 'customerVerified';
    public const NOT_VERIFIED = 'customerNotVerified';

    protected function supports(string $attribute, $user): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VERIFIED, self::NOT_VERIFIED])
            && $user instanceof User;

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
            case self::VERIFIED:
                if ($this->verified($token->getUser())) {
                    return true;
                }
                break;
            case self::NOT_VERIFIED:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }


    private function verified(User $user): bool
    {
        if ($user->isVerified()) return true;
        return false;
    }

    private function notVerified(User $user): bool
    {
        if ($user->isVerified()) return false;
        return true;
    }
}

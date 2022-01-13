<?php

namespace App\Security\Voter;

use App\Entity\Restaurant;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RestaurantVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['RESTAURANT_EDIT', 'RESTAURANT_DELETE'])
            && $subject instanceof \App\Entity\Restaurant;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /**
         * @var Restaurant $restaurant
         */
        $restaurant = $subject;
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        if ($restaurant->getUser() && $user->getId() === $restaurant->getUser()->getId())
        {
            return true;
        }

        return false;
    }
}

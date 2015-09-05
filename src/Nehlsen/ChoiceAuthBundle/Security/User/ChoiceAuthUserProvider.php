<?php

namespace Nehlsen\ChoiceAuthBundle\Security\User;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ChoiceAuthUserProvider implements UserProviderInterface
{
    /**
     * {@InheritDoc}
     */
    public function loadUserByUsername($username)
    {
        switch ($username) {
            case ChoiceAuthUser::USERNAME_SPECTATOR:
                return ChoiceAuthUser::createSpectator();
                break;
            case ChoiceAuthUser::USERNAME_BIG_SCREEN:
                return ChoiceAuthUser::createBigScreen();
                break;
            case ChoiceAuthUser::USERNAME_REFEREE:
                return ChoiceAuthUser::createReferee();
                break;
            case ChoiceAuthUser::USERNAME_ADMIN:
                return ChoiceAuthUser::createAdmin();
                break;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    /**
     * {@InheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof ChoiceAuthUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@InheritDoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Nehlsen\ChoiceAuthBundle\Security\User\ChoiceAuthUser';
    }
}
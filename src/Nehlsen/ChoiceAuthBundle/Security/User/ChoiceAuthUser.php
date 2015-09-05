<?php

namespace Nehlsen\ChoiceAuthBundle\Security\User;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ChoiceAuthUser implements UserInterface, EquatableInterface
{
    const USERNAME_SPECTATOR  = 'spectator';
    const USERNAME_BIG_SCREEN = 'big_screen';
    const USERNAME_REFEREE    = 'referee';
    const USERNAME_ADMIN      = 'admin';

    const ROLE_USER           = 'ROLE_USER';
    const ROLE_SPECTATOR      = 'ROLE_SPECTATOR';
    const ROLE_BIG_SCREEN     = 'ROLE_BIG_SCREEN';
    const ROLE_REFEREE        = 'ROLE_REFEREE';
    const ROLE_ADMIN          = 'ROLE_ADMIN';

    /** @var string */
    protected $username;
    /** @var string[] */
    protected $roles;

    private function __construct()
    {
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    public static function createSpectator()
    {
        $user = new static();
        $user->username = self::USERNAME_SPECTATOR;
        $user->roles = array(
            self::ROLE_USER,
            self::ROLE_SPECTATOR,
        );

        return $user;
    }

    public static function createBigScreen()
    {
        $user = static::createSpectator();
        $user->username = self::USERNAME_BIG_SCREEN;
        $user->roles = array_merge($user->roles, array(
            self::ROLE_BIG_SCREEN,
        ));

        return $user;
    }

    public static function createReferee()
    {
        $user = static::createSpectator();
        $user->username = self::USERNAME_REFEREE;
        $user->roles = array_merge($user->roles, array(
            self::ROLE_REFEREE,
        ));

        return $user;
    }

    public static function createAdmin()
    {
        $user = new static();
        $user->username = self::USERNAME_ADMIN;
        $user->roles = array(
            self::ROLE_USER,
            self::ROLE_ADMIN,
        );

        return $user;
    }

    /**
     * {@InheritDoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@InheritDoc}
     */
    public function getPassword()
    {
        return $this->getUsername();
    }

    /**
     * {@InheritDoc}
     */
    public function getSalt()
    {
        return $this->getUsername();
    }

    /**
     * {@InheritDoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@InheritDoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * {@InheritDoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof ChoiceAuthUser) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}

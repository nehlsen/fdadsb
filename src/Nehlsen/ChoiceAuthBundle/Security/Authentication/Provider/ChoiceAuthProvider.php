<?php

namespace Nehlsen\ChoiceAuthBundle\Security\Authentication\Provider;

use Nehlsen\ChoiceAuthBundle\Security\Authentication\Token\ChoiceAuthToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ChoiceAuthProvider implements AuthenticationProviderInterface
{
    /** @var UserProviderInterface */
    private $userProvider;

    /** @var string */
    private $providerKey;

    public function __construct(UserProviderInterface $userProvider, $providerKey)
    {
        $this->userProvider = $userProvider;
        $this->providerKey = $providerKey;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if (!$user) {
            throw new AuthenticationException('The authentication failed.');
        }

        $authenticatedToken = new ChoiceAuthToken($user->getRoles());
        $authenticatedToken->setAttributes($token->getAttributes());
        $authenticatedToken->setUser($user);
        $authenticatedToken->setProviderKey($this->providerKey);
        $authenticatedToken->setAuthenticated(true);

        return $authenticatedToken;

    }

    /**
     * {@InheritDoc}
     */
    public function supports(TokenInterface $token)
    {
//        return $token instanceof ChoiceAuthToken && $this->providerKey === $token->getProviderKey();
        return $token instanceof ChoiceAuthToken;
    }
}
<?php

namespace Nehlsen\ChoiceAuthBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ChoiceAuthToken extends AbstractToken
{
    /** @var string */
    protected $username;

    /** @var string */
    protected $providerKey;

    /**
     * @return string
     */
    public function getUsername()
    {
        return null !== $this->getUser() ? (string)$this->getUser() : $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getProviderKey()
    {
        return $this->providerKey;
    }

    /**
     * @param string $providerKey
     */
    public function setProviderKey($providerKey)
    {
        $this->providerKey = $providerKey;
    }

    /**
     * {@InheritDoc}
     */
    public function getCredentials()
    {
        return $this->username.'@'.$this->providerKey;
    }
}
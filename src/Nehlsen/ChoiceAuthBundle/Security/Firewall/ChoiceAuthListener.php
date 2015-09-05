<?php

namespace Nehlsen\ChoiceAuthBundle\Security\Firewall;

use Nehlsen\ChoiceAuthBundle\Security\Authentication\Token\ChoiceAuthToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;

class ChoiceAuthListener extends AbstractAuthenticationListener
{
    /**
     * {@InheritDoc}
     */
    protected function attemptAuthentication(Request $request)
    {
        $username = $request->get('_username', null, true);

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        $token = new ChoiceAuthToken();
        $token->setUsername($username);
        $token->setProviderKey($this->providerKey);
        return $this->authenticationManager->authenticate($token);
    }
}

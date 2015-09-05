<?php

namespace Nehlsen\ChoiceAuthBundle\Controller;

use Nehlsen\ChoiceAuthBundle\Security\User\ChoiceAuthUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $loginPossibilities = array(
            ChoiceAuthUser::USERNAME_SPECTATOR,
            ChoiceAuthUser::USERNAME_BIG_SCREEN,
            ChoiceAuthUser::USERNAME_REFEREE,
            ChoiceAuthUser::USERNAME_ADMIN,
        );

        return $this->render(
            'NehlsenChoiceAuthBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'loginPossibilities' => $loginPossibilities,
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    public function checkAction()
    {
        throw new \RuntimeException('check your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('check your security firewall configuration.');
    }
}

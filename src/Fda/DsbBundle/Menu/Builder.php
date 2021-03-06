<?php

namespace Fda\DsbBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'navbar' => true,
        ));

        $players = $menu->addChild('player.menu', array(
            'icon'  => 'users',
            'route' => 'player',
        ));

        $boards = $menu->addChild('board.menu', array(
            'icon'  => 'bullseye',
            'route' => 'board',
        ));

        $tournaments = $menu->addChild('tournament.menu', array(
            'icon'  => 'beer',
            'route' => 'tournament',
        ));


//        // Create a dropdown with a caret
//        $dropdown = $menu->addChild('Forms', array(
//            'dropdown' => true,
//            'caret' => true,
//        ));
//
//        // Create a dropdown header
//        $dropdown->addChild('Some Header', array('dropdown-header' => true));
//        $dropdown->addChild('Example 1', array('route' => 'some_route'));

        return $menu;
    }

    public function loginMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'navbar'     => true,
            'pull-right' => true,
        ));

        if ($this->isLoggedIn()) {
            $userMenu = $menu->addChild('user.profile.menu', array(
                'icon'  => 'user',
                'dropdown' => true,
                'caret' => true,
            ));

            $authorizationChecker = $this->container->get('security.authorization_checker');
            if ($authorizationChecker->isGranted('ROLE_BIG_SCREEN')) {
                $userMenu->addChild('big screen', array(
                    'icon'  => 'user',
                    'route' => 'BigScreen_index',
                ));
            }

            $userMenu->addChild('user.profile.about', array(
                'icon'  => 'user',
                'route' => 'fos_user_profile_show',
            ));
            $userMenu->addChild('user.profile.edit', array(
                'icon'  => 'user',
                'route' => 'fos_user_profile_edit',
            ));
            $userMenu->addChild('security.logout.btn', array(
                'icon'  => 'user',
                'route' => 'fos_user_security_logout',
            ));
        } else {
            $menu->addChild('security.login.btn', array(
                'icon'  => 'user',
                'route' => 'fos_user_security_login',
            ));
        }

        return $menu;
    }

    protected function isLoggedIn()
    {
        $authorizationChecker = $this->container->get('security.authorization_checker');
        return $authorizationChecker->isGranted('ROLE_USER');
    }
}

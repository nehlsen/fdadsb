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
            $profile = $menu->addChild('security.profile.btn', array(
                'icon'  => 'user',
                'route' => 'fos_user_profile_show',
            ));
            $logout = $menu->addChild('security.logout.btn', array(
                'icon'  => 'user',
                'route' => 'fos_user_security_logout',
            ));
        } else {
            $login = $menu->addChild('security.login.btn', array(
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

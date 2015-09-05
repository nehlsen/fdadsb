<?php

namespace Nehlsen\ChoiceAuthBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class ChoiceAuthFactory extends AbstractFactory
{
//    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
//    {
//        $providerId = 'security.authentication.provider.choice_auth.'.$id;
//        $container
//            ->setDefinition($providerId, new DefinitionDecorator('choice_auth.security.authentication.provider'))
//            ->replaceArgument(0, new Reference($userProvider))
//        ;
//
//        $listenerId = 'security.authentication.listener.choice_auth.'.$id;
//        $listener = $container->setDefinition(
//            $listenerId,
//            new DefinitionDecorator('choice_auth.security.authentication.listener')
//        );
//
//        return array($providerId, $listenerId, $defaultEntryPoint);
//    }

    public function getPosition()
    {
        return 'form';
    }

    public function getKey()
    {
        return 'choice_auth';
    }

    /**
     * {@InheritDoc}
     */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'security.authentication.provider.choice_auth.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('choice_auth.security.authentication.provider'))
            ->replaceArgument(0, new Reference($userProviderId))
            ->replaceArgument(1, $id)
        ;

        return $providerId;
    }

    /**
     * {@InheritDoc}
     */
    protected function getListenerId()
    {
        return 'choice_auth.security.authentication.listener';
    }
}
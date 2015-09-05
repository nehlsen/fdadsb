<?php

namespace Nehlsen\ChoiceAuthBundle;

use Nehlsen\ChoiceAuthBundle\Security\Factory\ChoiceAuthFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NehlsenChoiceAuthBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var SecurityExtension $extension */
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new ChoiceAuthFactory());
    }
}

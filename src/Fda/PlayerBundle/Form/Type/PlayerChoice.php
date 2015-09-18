<?php

namespace Fda\PlayerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerChoice extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'class'      => 'Fda\PlayerBundle\Entity\Player',
            'data_class' => 'Fda\PlayerBundle\Entity\Player',
            'property'   => 'name',
        ));

        parent::setDefaultOptions($resolver);
    }

    /**
     * {@InheritDoc}
     */
    public function getName()
    {
        return 'player_choice';
    }

    /**
     * {@InheritDoc}
     */
    public function getParent()
    {
        return 'entity';
    }
}

<?php

namespace Fda\TournamentBundle\Form\TournamentWizard;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Boards extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('boards', 'entity', array(
                'label'    => 'tournament.board.choose',
                'multiple' => true,
                'expanded' => true,
                'class'    => 'Fda\BoardBundle\Entity\Board',
                'property' => 'name',
            ));
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'nt_boards';
    }
}

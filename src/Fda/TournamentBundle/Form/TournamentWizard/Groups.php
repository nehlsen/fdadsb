<?php

namespace Fda\TournamentBundle\Form\TournamentWizard;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Groups extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groupsChoices = array(
            2 => 2,
            4 => 4,
            8 => 8,
        );

        $fillGroupsChoices = array(
            WizardData::FILL_GROUPS_RANDOM => 'tournament.wizard.fillGroups.random.label',
            WizardData::FILL_GROUPS_MANUAL => 'tournament.wizard.fillGroups.manual.label',
        );

        $builder
            ->add('numberOfGroups', 'choice', array(
                'label'    => 'tournament.wizard.numberOfGroups.choose',
                'expanded' => true,
                'choices'  => $groupsChoices,
            ))
            ->add('fillGroups', 'choice', array(
                'label'    => 'tournament.wizard.fillGroups.choose',
                'expanded' => true,
                'choices'  => $fillGroupsChoices,
            ));
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'nt_groups';
    }
}

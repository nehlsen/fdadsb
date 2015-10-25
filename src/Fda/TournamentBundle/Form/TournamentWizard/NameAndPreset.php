<?php

namespace Fda\TournamentBundle\Form\TournamentWizard;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NameAndPreset extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $setupPresets = array();
        foreach (WizardData::getAllPresets() as $preset) {
            $setupPresets[$preset] = 'tournament.setup_preset.'.$preset.'.label';
        }

        $builder
            ->add('name', 'text', array(
                'label'    => 'tournament.name',
            ))
            ->add('preset', 'choice', array(
                'label'    => 'tournament.setup_preset.choose',
                'expanded' => true,
                'choices'  => $setupPresets,
            ));
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'nt_name_preset';
    }
}

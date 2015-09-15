<?php

namespace Fda\TournamentBundle\Form;

use Fda\TournamentBundle\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TournamentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tournamentModeChoices = array();
        foreach (Tournament::getTournamentModes() as $mode) {
            $tournamentModeChoices[$mode] = 'tournament.tournament_mode.'.$mode.'.label';
        }

        $gameModeChoices = array();
        foreach (Tournament::getGameModes() as $mode) {
            $gameModeChoices[$mode] = 'tournament.game_mode.'.$mode.'.label';
        }

        $legModeChoices = array();
        foreach (Tournament::getLegModes() as $mode) {
            $legModeChoices[$mode] = 'tournament.leg_mode.'.$mode.'.label';
        }

        $builder
            ->add('tournamentMode', 'choice', array(
                'label'    => 'tournament.tournament_mode.choose',
                'expanded' => true,
                'choices'  => $tournamentModeChoices,
            ))
            ->add('tournamentCount', 'integer', array(
                'label'    => 'tournament.tournament_count.choose',
            ))
            ->add('gameMode', 'choice', array(
                'label'    => 'tournament.game_mode.choose',
                'expanded' => true,
                'choices'  => $gameModeChoices,
            ))
            ->add('gameCount', 'integer', array(
                'label'    => 'tournament.game_count.choose',
            ))
            ->add('legMode', 'choice', array(
                'label'    => 'tournament.leg_mode.choose',
                'expanded' => true,
                'choices'  => $legModeChoices,
            ))
            ->add('boards', 'entity', array(
                'label'    => 'tournament.board.choose',
                'multiple' => true,
                'expanded' => true,
                'class'    => 'Fda\BoardBundle\Entity\Board',
                'property' => 'name',
            ))
            ->add('players', 'entity', array(
                'label'    => 'tournament.player.choose',
                'multiple' => true,
                'expanded' => true,
                'class'    => 'Fda\PlayerBundle\Entity\Player',
                'property' => 'name',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fda\TournamentBundle\Entity\Tournament'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fda_tournamentbundle_tournament';
    }
}

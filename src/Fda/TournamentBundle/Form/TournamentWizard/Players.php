<?php

namespace Fda\TournamentBundle\Form\TournamentWizard;

use Fda\PlayerBundle\Manager\PlayerManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class Players extends AbstractType
{
    /** @var PlayerManager */
    protected $playerManager;

    /**
     * Players constructor.
     * @param PlayerManager $playerManager
     */
    public function __construct(PlayerManager $playerManager)
    {
        $this->playerManager = $playerManager;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var WizardData $data */
            $data = $event->getData();
            $form = $event->getForm();

            $groupChoices = array();
            $numberOfGroups = $data->getNumberOfGroups();
            for($group = 0; $group < $numberOfGroups; ++$group) {
                $groupChoices[$group] = 'group '.($group+1);
            }

            foreach ($this->playerManager->getPlayers() as $player) {
                $form->add('player_'.$player->getId(), 'choice', array(
                    'property_path' => 'players['.$player->getId().']',
                    'choices' => $groupChoices,
                    'empty_value' => 'no group',
//                    'empty_data' => 'none',
                    'required' => false,
                    'label' => $player->getName(),
                ));
            }

        });
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'nt_players';
    }
}

<?php

namespace Fda\TournamentBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;
use Fda\PlayerBundle\Manager\PlayerManager;
use Fda\TournamentBundle\Form\TournamentWizard\Boards;
use Fda\TournamentBundle\Form\TournamentWizard\Groups;
use Fda\TournamentBundle\Form\TournamentWizard\NameAndPreset;
use Fda\TournamentBundle\Form\TournamentWizard\Players;

class TournamentWizard extends FormFlow
{
    /** @var PlayerManager */
    protected $playerManager;

    /**
     * @param PlayerManager $playerManager
     */
    public function setPlayerManager($playerManager)
    {
        $this->playerManager = $playerManager;
    }

    /**
     * @inheritDoc
     */
    protected function loadStepsConfig()
    {
        return array(
            array(
                'label'     => 'tournament.wizard.name_and_preset.label',
                'form_type' => new NameAndPreset(),
            ),
            array(
                'label'     => 'tournament.wizard.boards.label',
                'form_type' => new Boards(),
            ),
            array(
                'label'     => 'tournament.wizard.groups.label',
                'form_type' => new Groups(),
                // TODO skip if league (league has 1 group, has to be filled manual to select who's playing)
//                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
//                    return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->canHaveEngine();
//                },
            ),
            array(
                'label'     => 'tournament.wizard.players.label',
                'form_type' => new Players($this->playerManager),
                // TODO skip if fill random
            ),
            // TODO add summary
        );
    }

    /**
     * @inheritDoc
     */
    function getName()
    {
        return 'new_tournament_wizard';
    }
}

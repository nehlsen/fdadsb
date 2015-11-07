<?php

namespace Fda\TournamentBundle\Engine\Gears;
use Fda\TournamentBundle\Engine\Bolts\GameMode;

/**
 * simple game gears support game modes first-to and ahead
 */
class GameGearsSimple extends AbstractGameGears
{
    /** @var LegGearsInterface */
    protected $currentLegGears;

    public static function getSupportedModes()
    {
        return array(
            GameMode::FIRST_TO,
            GameMode::AHEAD,
        );
    }

    /**
     * @inheritDoc
     */
    public function getCurrentLegGears()
    {
        if (null === $this->currentLegGears) {
            // do we have enough legs?
            // call the factory to create the leg
            $this->currentLegGears = $this->legGearsFactory->create($this->getGame());
//            throw new \Exception('TODO');
        }

        return $this->currentLegGears;
    }
}

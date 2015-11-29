<?php

namespace Fda\TournamentBundle\Engine\Setup;

use Fda\TournamentBundle\Engine\Gears\RoundGearsInterface;

class InputStep implements InputInterface
{
    /** @var int */
    protected $steps = 1;

    /**
     * InputStep constructor.
     * @param int $steps
     */
    public function __construct($steps = 1)
    {
        $this->steps = $steps;
    }

    /**
     * @inheritDoc
     */
    public function getModeLabel()
    {
        return 'step';
    }

    /**
     * @return int
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param int $steps
     * @return InputStep this
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function filter(RoundGearsInterface $previousRoundGears)
    {
        $advance = $previousRoundGears->getRound()->getSetup()->getAdvance(); // -1 = all
        $leaderBoard = $previousRoundGears->getLeaderBoard();

        $result = array();
        $groupNumbers = $leaderBoard->getGroupNumbers();
        foreach ($groupNumbers as $groupNumber) {
            $result[$groupNumber] = array();
        }

        $groupNumberStepped = function ($step) use ($groupNumbers) {
            $it = new \ArrayIterator($groupNumbers);
            $it->seek($step % $it->count());
            return $it->current();
        };

        $players = $leaderBoard->getEntries($advance);

        $step = 0;
        foreach ($groupNumbers as $groupNumber) {
            $groupAdvance = -1 == $advance ? count($players[$groupNumber]) : $advance;
            for ($i = 0; $i < $groupAdvance; ++$i) {
                $result[$groupNumberStepped($step+$i)][] = $players[$groupNumber][$i]->getPlayer();
            }

            $step += $this->getSteps();
        }
//        $step = 0;
//        for ($i = 0; $i < $advance; ++$i) {
//            foreach ($groupNumbers as $groupNumber) {
//                $result[$groupNumberStepped($step+1)][] = $players[$groupNumber][$i];
//            }
//
//            $step += $this->getSteps();
//        }

//        dump($players, $result);

        // TODO implement input step : filter
//        throw new \Exception('TODO');

        return $result;
    }
}

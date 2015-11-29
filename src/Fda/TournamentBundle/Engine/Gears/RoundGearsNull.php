<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\LeaderBoard\BasicLeaderBoard;
use Fda\TournamentBundle\Entity\Group;

class RoundGearsNull extends AbstractRoundGears
{
    /**
     * @inheritDoc
     */
    public function isRoundCompleted()
    {
        // null is not playable and therefore always completed
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function initGameGearsForGroup(Group $group)
    {
//        throw new \RuntimeException('Null has no Games and should not be initialized!');
        return array();
    }

    /**
     * @inheritDoc
     */
    public function getLeaderBoard()
    {
        $leaderBoard = new BasicLeaderBoard();

        foreach ($this->getRound()->getGroups() as $group) {
            $points = count($group->getPlayers());
            foreach ($group->getPlayers() as $player) {
                $leaderBoard->update(
                    $group->getNumber(),
                    $player,
                    $points--
                );
            }
        }

        return $leaderBoard;
    }
}

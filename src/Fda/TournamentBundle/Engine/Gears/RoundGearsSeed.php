<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\LeaderBoard\BasicLeaderBoard;
use Fda\TournamentBundle\Engine\Setup\RoundSetupSeed;
use Fda\TournamentBundle\Entity\Group;
use Fda\TournamentBundle\Entity\Round;

class RoundGearsSeed extends AbstractRoundGears
{
    /**
     * @inheritDoc
     */
    protected function initializeRoundEntity(Round $round)
    {
        // TODO: Implement initializeRoundEntity() method.
        // foreach setup-group check for and/or create group-entity
        // just associate them properly, cascade operations should take care of persistence

        $tournament = $round->getTournament();
        $getPlayer = function ($playerId) use ($tournament) {
            foreach ($tournament->getPlayers() as $player) {
                if ($player->getId() == $playerId) {
                    return $player;
                }
            }

            throw new \RuntimeException(sprintf('failed to fetch player by id %d from tournament', $playerId));
        };

        /** @var RoundSetupSeed $roundSetup */
        $roundSetup = $this->setup;
        foreach ($roundSetup->getPlayerIdsGrouped() as $groupNumber => $groupPlayers) {
            $group = $round->getGroupByNumber($groupNumber);
            if (null === $group) {
                $group = $round->createGroup($groupNumber); // this could create the game-entities too...
            }

            $players = array();
            foreach ($groupPlayers as $playerId) {
                $players[] = $getPlayer($playerId);
            }
            $group->setPlayers($players);
        }
    }

    /**
     * @inheritDoc
     */
    public function isRoundCompleted()
    {
        // seed is not playable and therefore always completed
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function initGameGearsForGroup(Group $group)
    {
//        throw new \RuntimeException('Seed has no Games and should not be initialized!');
        return array();
    }

    /**
     * @inheritDoc
     */
    public function getLeaderBoard()
    {
        $leaderBoard = new BasicLeaderBoard();

        foreach ($this->getRound()->getGroups() as $group) {
            foreach ($group->getPlayers() as $player) {
                $leaderBoard->update(
                    $group->getNumber(),
                    $player,
                    0
                );
            }
        }

        return $leaderBoard;
    }
}

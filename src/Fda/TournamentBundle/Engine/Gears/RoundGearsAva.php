<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Doctrine\Common\Collections\Collection;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\LeaderBoard\BasicLeaderBoard;
use Fda\TournamentBundle\Entity\Group;

class RoundGearsAva extends AbstractRoundGears
{
//    /**
//     * @inheritDoc
//     */
//    public function isRoundCompleted()
//    {
//        if (parent::isRoundCompleted()) {
//            return true;
//        }
//
//        // the round entity says it is not yet closed...
//        throw new \Exception('TODO: determine if this round is closed');
//    }

    /**
     * @inheritdoc
     */
    protected function initGameGearsForGroup(Group $group)
    {
        // this is AvA, create one game for each player vs each other player

        $players = $group->getPlayers();
        $players = $players instanceof Collection ? $players->toArray() : $players;

        $gameGears = array();
        while (count($players) > 1) {
            $player = array_shift($players);
            $gameGears = array_merge(
                $gameGears,
                $this->createGameGears($group, $player, $players)
            );
        }

        return $gameGears;
    }

    /**
     * create a list of game-gears for games between player and all provided contestants
     *
     * @param Group    $group       the group the games shall belong to
     * @param Player   $player      the one player
     * @param Player[] $contestants who plays against these
     *
     * @return GameGearsInterface[]
     */
    protected function createGameGears(Group $group, $player, $contestants)
    {
        $gameGears = array();
        foreach ($contestants as $contestant) {
            $gears = $this->gameGearsFactory->create($group, $player, $contestant);

            $gameGears[] = $gears;
        }

        return $gameGears;
    }

    /**
     * @inheritDoc
     */
    public function getLeaderBoard()
    {
        $leaderBoard = new BasicLeaderBoard();

        foreach ($this->getGameGearsGrouped() as $groupNumber => $gamesGears) {
            foreach ($gamesGears as $gameGears) {
                /** @var GameGearsInterface $gameGears */
                $game = $gameGears->getGame();
                $player1 = $game->getPlayer1();
                $player2 = $game->getPlayer2();
                $winner = $game->getWinner();

                $player1entry = $leaderBoard->update(
                    $groupNumber,
                    $player1,
                    ($winner == $player1 ? 1 : 0)
                );
                $player2entry = $leaderBoard->update(
                    $groupNumber,
                    $player2,
                    ($winner == $player2 ? 1 : 0)
                );

                if (!$game->isClosed()) {
                    $player1entry->setFinal(false);
                    $player2entry->setFinal(false);
                }
            }
        }

        return $leaderBoard;
    }
}

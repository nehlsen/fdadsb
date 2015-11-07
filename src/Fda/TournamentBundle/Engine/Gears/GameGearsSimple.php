<?php

namespace Fda\TournamentBundle\Engine\Gears;
use Fda\PlayerBundle\Entity\Player;
use Fda\TournamentBundle\Engine\Bolts\GameMode;
use Fda\TournamentBundle\Entity\Game;
use Fda\TournamentBundle\Entity\Leg;

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
            // TODO prevent creation of too much legs
            // do we have enough legs?
            // call the factory to create the leg
            $this->currentLegGears = $this->legGearsFactory->create($this->getGame());
        }

        return $this->currentLegGears;
    }

    /**
     * @inheritDoc
     */
    protected function handleLegCompleted(Leg $leg)
    {
        // check if this completes the game

//        $game = $this->getGame(); -> does not yet know that leg is closed

        $game = $leg->getGame();
        $game->updateWonLegs();
        $winner = $this->checkForWinner($game);
        if (null !== $winner) {
            $game->setWinner($winner);
            $this->setGameCompleted($game);
        }
    }

    /**
     * check if game has been won by either player
     *
     * returns a player if game is over (the winner is returned)
     * else returns null, game is not over yet
     *
     * @param Game $game
     *
     * @return Player|null the winner or null if ame is not over
     *
     * @throws \Exception
     */
    protected function checkForWinner(Game $game)
    {
        $gameMode = $game->getGameMode();

        switch ($gameMode->getMode()) {
            case GameMode::AHEAD:
                $player1count = $game->getLegsWonPlayer1() - $game->getLegsWonPlayer2();
                $player2count = $game->getLegsWonPlayer2() - $game->getLegsWonPlayer1();
                break;

            case GameMode::FIRST_TO:
                $player1count = $game->getLegsWonPlayer1();
                $player2count = $game->getLegsWonPlayer2();
                break;

            default:
                throw new \Exception('invalid game mode');
                break;
        }

        if ($player1count == $gameMode->getCount()) {
            return $game->getPlayer1();
        } elseif ($player2count == $gameMode->getCount()) {
            return $game->getPlayer2();
        }

        return null;
    }
}

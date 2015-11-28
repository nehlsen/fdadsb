<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\Factory\GameGearsFactory;
use Fda\TournamentBundle\Engine\LeaderBoard\LeaderBoardInterface;
use Fda\TournamentBundle\Engine\Setup\RoundSetupInterface;
use Fda\TournamentBundle\Entity\Group;
use Fda\TournamentBundle\Entity\Round;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface RoundGearsInterface extends EventSubscriberInterface
{
    /**
     * @param GameGearsFactory $gameGearsFactory
     */
    public function setGameGearsFactory(GameGearsFactory $gameGearsFactory);

    /**
     * @return Round
     */
    public function getRound();

    /**
     * @return RoundSetupInterface
     */
    public function getSetup();

    /**
     * @param RoundGearsInterface $previousRound
     */
    public function setPreviousRoundGears(RoundGearsInterface $previousRound);

    /**
     * @return RoundGearsInterface
     */
    public function getPreviousRoundGears();

    /**
     * @return bool
     */
    public function hasPreviousRoundGears();

    /**
     * whether the associated round is closed
     *
     * a round can be closed for various reasons (including but not limited to)
     *  - all games for this round completed, round is over
     *  - previous round not completed, round not yet open
     *
     * @return bool
     */
    public function isRoundClosed();

    /**
     * whether all games for this round have been completed and this round is not playable anymore
     *
     * @return bool
     */
    public function isRoundCompleted();

    /**
     * @return bool
     */
    public function isRoundOpen();

    /**
     * get all games by group-number
     *
     * @return GameGearsInterface[][]
     */
    public function getGameGearsGrouped();

    /**
     * get all games in group
     *
     * @param Group $group
     *
     * @return GameGearsInterface[]
     */
    public function getGameGearsForGroup(Group $group);

    /**
     * get leader board for this round
     *
     * the board has to contain every player of this round
     * even if unplaced/undetermined/not-final...
     *
     * @return LeaderBoardInterface
     */
    public function getLeaderBoard();
}

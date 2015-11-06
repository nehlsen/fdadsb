<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Engine\Factory\GameGearsFactory;
use Fda\TournamentBundle\Engine\Scoreboard\Scoreboard;
use Fda\TournamentBundle\Engine\Setup\RoundSetupInterface;
use Fda\TournamentBundle\Entity\Group;
use Fda\TournamentBundle\Entity\Round;

interface RoundGearsInterface
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
     * get the rounds scoreboard
     *
     * players grouped by group ordered by score
     * all players in this round must be in the result,
     * players with undetermined scores shall be appended to the appropriate list
     *
     * if round-is-closed because all matches are played, the list shall be properly ordered
     *
     * e.g.:
     * [
     *  0 => [        // first group
     *      PlayerB,  // players ordered by score
     *      PlayerA,
     *      PlayerC,
     *      ],
     *  1 => [...],  // second group
     *  ...          // remaining groups
     * ]
     *
     * @return Scoreboard
     */
    public function getScoreboard();
}

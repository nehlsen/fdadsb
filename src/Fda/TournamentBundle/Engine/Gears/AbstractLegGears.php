<?php

namespace Fda\TournamentBundle\Engine\Gears;

use Fda\TournamentBundle\Entity\Leg;

abstract class AbstractLegGears implements LegGearsInterface
{
    /** @var Leg */
    protected $leg;

    /**
     * AbstractLegGears constructor.
     * @param Leg $leg
     */
    public function __construct(Leg $leg)
    {
        $this->leg = $leg;
    }
}

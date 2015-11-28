<?php

namespace Fda\TournamentBundle\Engine\Events;

use Fda\TournamentBundle\Entity\Group;
use Symfony\Component\EventDispatcher\GenericEvent;

class GroupEvent extends GenericEvent
{
    /**
     * @return Group
     */
    public function getGroup()
    {
        $group = $this->getArgument('group');
        return $group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->setArgument('group', $group);
    }
}

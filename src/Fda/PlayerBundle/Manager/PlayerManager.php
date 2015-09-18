<?php

namespace Fda\PlayerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Fda\PlayerBundle\Entity\Player;

class PlayerManager
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $playerId
     * @return Player
     */
    public function getPlayer($playerId)
    {
        return $this->getRepository()->find($playerId);
    }

    /**
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository('FdaPlayerBundle:Player');
    }
}

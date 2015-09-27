<?php

namespace Fda\TournamentBundle\Manager;

use Doctrine\ORM\EntityManager;
use Fda\TournamentBundle\Entity\Tournament;

class TournamentManager
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
     * @param int $tournamentId
     * @return Tournament
     */
    public function getTournament($tournamentId)
    {
        return $this->getRepository()->find($tournamentId);
    }

    /**
     * @return Tournament[]
     */
    public function getTournaments()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository('FdaTournamentBundle:Tournament');
    }
}

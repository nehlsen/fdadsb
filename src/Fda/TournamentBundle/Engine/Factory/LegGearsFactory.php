<?php

namespace Fda\TournamentBundle\Engine\Factory;

use Doctrine\ORM\EntityManager;

class LegGearsFactory
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}

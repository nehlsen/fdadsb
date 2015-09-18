<?php

namespace Fda\BoardBundle\Manager;

use Doctrine\ORM\EntityManager;
use Fda\BoardBundle\Entity\Board;

class BoardManager
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
     * @param int $boardId
     * @return Board
     */
    public function getBoard($boardId)
    {
        return $this->getRepository()->find($boardId);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository('FdaBoardBundle:Board');
    }
}

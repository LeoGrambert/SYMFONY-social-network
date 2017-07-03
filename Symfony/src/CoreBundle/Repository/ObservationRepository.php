<?php

namespace CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\Entity\Observation;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends EntityRepository
{
    /**
     * Method to get all observations (for a user who is logged)
     * @param $userId
     * @return array
     */
    public function findByIdUserWithSpecies($userId)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.user = :userId')
                ->setParameter('userId', $userId)
            ->orderBy('o.date', 'desc')
            ->leftJoin('o.bird', 's')
            ->addSelect('s');

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findLastObservations(){
        $qd = $this->createQueryBuilder('o');

        $qd
            ->orderBy('o.date', 'desc')
            ->setMaxResults(3);

        return $qd
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Observation $observation
     * @param bool $flush
     */
    public function add(Observation $observation, $flush = true)
    {
        $this->getEntityManager()->persist($observation);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

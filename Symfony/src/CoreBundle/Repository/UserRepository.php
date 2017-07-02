<?php

namespace CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * We use this method in order to find all professional users who are not yet accredited to validate amateurs observations
     * @return array
     */
    public function findUsersNotAccredit()
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->where('u.isAccredit = :isAccredit')
            ->andWhere('u.roles = :roles')
            ->setParameters([
                'isAccredit'=> false,
                'roles'=> 'a:1:{i:0;s:8:"ROLE_PRO";}'
            ]);

        return $qb
            ->getQuery()
            ->getResult();
    }
}

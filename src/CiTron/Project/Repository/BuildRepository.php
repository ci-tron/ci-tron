<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Repository;

use CiTron\Project\Entity\Project;
use Doctrine\ORM\EntityRepository;

/**
 * Class BuildRepository
 */
class BuildRepository extends EntityRepository
{
    public function count(Project $project)
    {
        $qb = $this->createQueryBuilder('build');
        
        $qb
            ->select('COUNT(build.id) as total')
            ->innerJoin('build.project', 'project')
            ->where($qb->expr()->eq('project', ':project'))
            ->setParameter('project', $project)
        ;
        
        return $qb->getQuery()->getResult()[0]['total'];
    }
}

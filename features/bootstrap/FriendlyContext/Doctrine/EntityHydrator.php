<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Behat\FriendlyContext\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Knp\FriendlyContexts\Doctrine\EntityHydrator as BaseEntityHydrator;

class EntityHydrator extends BaseEntityHydrator
{
    /**
     * This little addition allow the hydrator to hydrate ids !
     *
     * @param ObjectManager $em
     * @param object        $entity
     * @param array         $values
     * @return EntityHydrator
     * @throws \Exception
     */
    public function hydrate(ObjectManager $em, $entity, $values)
    {
        if (isset($values['id'])) {
            $ref = new \ReflectionObject($entity);
            $idProperty = $ref->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($entity, $values['id']);
            $idProperty->setAccessible(false);

            unset($values['id']);
        }

        return parent::hydrate($em, $entity, $values);
    }
}

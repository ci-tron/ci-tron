<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Behat;


use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Knp\FriendlyContexts\Context\EntityContext;

class CitronEntityContext extends EntityContext
{
    /**
     * This is a performance improvement.
     * https://github.com/KnpLabs/FriendlyContexts/issues/195
     *
     * @BeforeScenario
     */
    public function beforeScenario($event)
    {
        $this->storeTags($event);

        if ($this->hasTags([ 'reset-schema', '~not-reset-schema' ])) {
            $em = $this->get('doctrine')->getManager();
            $em->getConnection()->executeUpdate("SET FOREIGN_KEY_CHECKS=0;");

            $purger = new ORMPurger($em);
            $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
            $purger->purge();

            $em->getConnection()->executeUpdate("SET FOREIGN_KEY_CHECKS=1;");
        }
    }
}

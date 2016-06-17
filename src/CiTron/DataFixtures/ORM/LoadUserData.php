<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\DataFixtures\ORM;

use CiTron\User\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setPassword('admin')
            ->setEmail('admin@ci-tron.org')
            ->addRole(User::ROLE_SUPER_ADMIN)
        ;

        $normalUser = new User();
        $normalUser
            ->setUsername('user')
            ->setPassword('user')
            ->setEmail('user@ci-tron.org')
            ->addRole(User::ROLE_USER)
        ;

        $manager->persist($admin);
        $manager->persist($normalUser);
        $manager->flush();
        
        $this->setReference('user_admin', $admin);
    }

    public function getOrder()
    {
        return 10;
    }
}

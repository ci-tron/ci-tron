<?php
/**
 * This file is a part of a nekland library
 *
 * (c) Nekland <nekland.fr@gmail.fr>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\DataFixtures\ORM;


use CiTron\Project\Entity\Configuration;
use CiTron\Project\Entity\Project;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function load(ObjectManager $manager)
    {
        $project = new Project();
        $project->setUser($this->getReference('user_admin'));
        $project->setName('Test Project');
        $project->setConfiguration($this->container->get('app.project.factory.configuration')->create());
        $project->setRepository('http://gl.nekland.fr/Nek/Woketo.git');
        
        $manager->persist($project);
        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

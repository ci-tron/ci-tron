<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace CiTron\Project\Entity;

use Doctrine\ORM\Mapping as ORM;
use CiTron\User\Entity\User;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass="CiTron\Project\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="repository", type="string", length=255, unique=false)
     */
    private $repository;

    /**
     * @var string
     *
     * @ORM\Column(name="visibility", type="boolean")
     */
    private $visibility;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="CiTron\User\Entity\User", inversedBy="projects")
     */
    private $user;

    public function __construct()
    {
        $this->visibility = true;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name) : Project
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param string $repository
     * @return $this
     */
    public function setRepository(string $repository) : Project
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return $this
     */
    public function setUser(User $user) : Project
    {
        $this->user = $user;

        return $this;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility) : Project
    {
        $this->visibility = $visibility;

        return $this;
    }
}

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

use CiTron\User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="CiTron\Project\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Project
{
    const VISIBILITY_PUBLIC = 2;
    const VISIBILITY_RESTRICTED = 1;
    const VISIBILITY_PRIVATE = 0;

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
     * @ORM\Column(name="name", type="string", length=255, unique=false)
     * @JMS\Groups({"standard", "current"})
     * @JMS\Expose
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column
     * @Gedmo\Slug(fields={"name"})
     * @JMS\Groups({"standard", "current"})
     * @JMS\Expose
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="repository", type="string", length=255, unique=false)
     * @JMS\Groups({"standard", "current"})
     * @JMS\Expose
     */
    private $repository = '';

    /**
     * @var int
     *
     * @ORM\Column(name="visibility", type="integer")
     */
    private $visibility;

    /**
     * @var Configuration
     * @ORM\Embedded(class = "Configuration")
     * @JMS\Groups({"standard", "current"})
     * @JMS\Expose
     */
    private $configuration;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="CiTron\User\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CiTron\Project\Entity\Build", mappedBy="project", cascade={"remove"})
     */
    private $builds;

    public function __construct()
    {
        $this->setVisibility(self::VISIBILITY_PRIVATE);
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
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Project
     */
    public function setName(string $name) : Project
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getRepository() : string
    {
        return $this->repository;
    }

    /**
     * @param string $repository
     * @return Project
     */
    public function setRepository(string $repository) : Project
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Project
     */
    public function setUser(User $user) : Project
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getVisibility() : int
    {
        return $this->visibility;
    }

    /**
     * @param int $visibility
     * @return Project
     */
    public function setVisibility(int $visibility) : Project
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return mixed
     */
    public function getBuilds()
    {
        return $this->builds;
    }

    /**
     * @param Build $build
     * @return $this
     */
    public function addBuild(Build $build)
    {
        $this->builds->add($build);

        return $this;
    }

    /**
     * @param Build $build
     * @return $this
     */
    public function removeBuild(Build $build)
    {
        return $this->builds->removeElement($build);

        return $this;
    }

    /**
     * @param ArrayCollection $builds
     */
    public function setBuilds(ArrayCollection $builds)
    {
        $this->builds = $builds;
    }
}

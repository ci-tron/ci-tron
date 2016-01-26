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
     * @ORM\Column
     */
    private $name;

    /**
     * @var \CiTron\User\Entity\User
     * @ORM\ManyToOne(targetEntity="CiTron\User\Entity\User", inversedBy="projects")
     */
    private $owner;

    /**
     * @return int
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
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \CiTron\User\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param \CiTron\User\Entity\User $owner
     * @return Project
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }
}

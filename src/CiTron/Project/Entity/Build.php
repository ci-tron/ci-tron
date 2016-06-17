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
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="CiTron\Project\Repository\BuildRepository")
 * @ORM\Table(name="build")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Build
{
    const STATE_WAITING = 'WAITING';
    const STATE_PENDING = 'PENDING';
    const STATE_ABORTED = 'ABORTED';
    const STATE_FAILED = 'FAILED';
    const STATE_SUCCESS = 'SUCCESS';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Groups({"standard"})
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="number", type="integer", nullable=true)
     * @JMS\Groups({"standard", "partial"})
     * @JMS\Expose()
     */
    private $number;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="CiTron\Project\Entity\Project", inversedBy="builds")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @JMS\Groups({"standard"})
     * @JMS\Expose()
     * @JMS\Type("CiTron\Project\Entity\Project")
     */
    private $project;

    /**
     * @var string
     * @ORM\Column
     */
    private $commit;

    /**
     * @var string
     *
     * @ORM\Column(name="logs", type="text", nullable=true)
     * @JMS\Groups({"standard"})
     * @JMS\Expose()
     */
    private $logs;

    /**
     * @var string
     *
     * @ORM\Column(name="state", nullable=true)
     * @JMS\Expose()
     * @JMS\Groups({"standard", "partial"})
     */
    private $state;

    /**
     * @var \DateTime
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     * @JMS\Expose()
     * @JMS\Groups({"standard"})
     */
    private $startedAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="finished_at", type="datetime", nullable=true)
     * @JMS\Expose()
     * @JMS\Groups({"standard"})
     */
    private $finishedAt;

    public function __construct()
    {
        $this->state = Build::STATE_WAITING;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getCommit()
    {
        return $this->commit;
    }

    /**
     * @param string $commit
     */
    public function setCommit($commit)
    {
        $this->commit = $commit;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     */
    public function setStartedAt($startedAt)
    {
        $this->startedAt = $startedAt;
    }

    /**
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @param \DateTime $finishedAt
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;
    }

    /**
     * @return string
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param string $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }
}

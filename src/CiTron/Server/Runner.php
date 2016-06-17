<?php
/**
 * This file is a part of a nekland library
 *
 * (c) Nekland <nekland.fr@gmail.fr>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Server;


use CiTron\Project\Entity\Build;
use CiTron\Project\Entity\Project;
use Ratchet\ConnectionInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Runner
 *
 * @JMS\ExclusionPolicy("all")
 */
class Runner
{
    const STATE_RUNNING = "RUNNING";
    const STATE_WAITING = "WAITING";

    /**
     * @var string
     * @JMS\Expose
     */
    private $state;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     * @JMS\Expose
     */
    private $type;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var Build
     */
    private $currentBuild;

    public function __construct(ConnectionInterface $connection = null)
    {
        $this->connection = $connection;
        $this->state = Runner::STATE_WAITING;
    }

    /**
     * @param Build $build
     * @param ConnectionInterface $client
     */
    public function runProject(Build $build, ConnectionInterface $client)
    {
        if ($this->connection === null) {
            throw new \LogicException('You need to add the related connection first.');
        }
        $this->state = Runner::STATE_RUNNING;
        $this->currentBuild = $build;

        $client->send('run:' . json_encode([
            'repo' => $build,
            'script' => array_merge(
                $build->getProject()->getConfiguration()->getPreparationScript(),
                $build->getProject()->getConfiguration()->getLaunchScript()
            )
        ]));
    }

    /**
     * @param ConnectionInterface $connection
     * @return self
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return Build
     */
    public function getCurrentBuild()
    {
        return $this->currentBuild;
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
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * @return Runner
     */
    public function setState(string $state)
    {
        if (!in_array($state, [Runner::STATE_RUNNING, Runner::STATE_WAITING])) {
            throw new \InvalidArgumentException(sprintf('The state %s is not valid', $state));
        }

        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}

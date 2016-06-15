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


use CiTron\Project\Entity\Project;
use Ratchet\ConnectionInterface;

class Runner
{
    const STATE_RUNNING = 1;
    const STATE_WAITING = 2;

    /**
     * @var int
     */
    private $state;

    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @var Project
     */
    private $currentProject;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->state = Runner::STATE_WAITING;
    }

    /**
     * @param Build $project
     * @param ConnectionInterface $client
     */
    public function runProject(Build $project, ConnectionInterface $client)
    {
        $this->state = Runner::STATE_RUNNING;
        $this->currentProject = $project;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }
}

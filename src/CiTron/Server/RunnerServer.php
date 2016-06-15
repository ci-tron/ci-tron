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


use CiTron\Server\Exception\InvalidMessageException;
use CiTron\Server\Exception\NoAvailableRunnerException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

class RunnerServer
{
    /**
     * @var array
     */
    private $runners;

    public function __construct()
    {
        $this->runners = [];
    }

    /**
     * Redirect to other methods to process the action required.
     *
     * @param Message $message
     * @throws InvalidMessageException
     */
    public function process(Message $message)
    {
        $method = 'process' . ucfirst($message->getAction());

        if (!method_exists($this, $method)) {
            throw new InvalidMessageException;
        }

        $this->$method($message);
    }

    protected function processInit(Message $message)
    {
        foreach ($this->runners as $runner) {
            if ($runner->getConnection() == $message->getFrom()) {
                return;
            }
        }

        $runner = new Runner($message->getFrom());

        $this->runners[] = $runner;
        echo "One new runner registered\n";
    }

    protected function processUnregister(Message $message)
    {
        $runner = null;
        $key = null;

        foreach ($this->runners as $key => $runner) {
            if ($runner->getConnection() == $message->getFrom()) {
                unlink($this->runners[$key]);

                return;
            }
        }
    }

    /**
     * @return Runner
     * @throws NoAvailableRunnerException
     */
    public function getFreeRunner()
    {
        foreach($this->runners as $runner) {
            if ($runner->getState() === Runner::STATE_WAITING) {
                return $runner;
            }
        }

        throw new NoAvailableRunnerException;
    }

    /**
     * Get the runner for the current message.
     *
     * @param Message $message
     * @return Runner|null
     */
    private function getRunner(Message $message)
    {
        foreach ($this->runners as $runner) {
            if ($runner->getConnection() == $message->getFrom()) {
                return $runner;
            }
        }

        return null;
    }
}

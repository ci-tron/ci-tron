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

    /**
     * @var Registry
     */
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->runners = [];
        $this->doctrine = $doctrine;
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
        $runner->setType($message->getContent()['type']);

        $this->runners[] = $runner;
        echo "One new runner registered\n";
    }

    protected function processProcess(Message $message)
    {
        $runner = $this->getRunner($message);
        $data = $message->getContent();
        if (!$data['finished']) {
            $runner->getCurrentBuild()->addLog($data['log']);
            return;
        }
        
        $runner->getCurrentBuild()->setState($data['result'] === 'success' ? Build::STATE_SUCCESS : Build::STATE_FAILED);
        
        $this->getEntityManager()->merge($runner->getCurrentBuild());
        $this->getEntityManager()->flush();
            
        $runner->setState(Runner::STATE_WAITING);
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

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    private function getEntityManager()
    {
        return $this->doctrine->getManager();
    }
}

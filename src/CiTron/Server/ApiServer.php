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


use CiTron\Server\Exception\NoAvailableRunnerException;

class ApiServer
{
    /**
     * @var RunnerServer
     */
    private $runnerServer;

    public function __construct(RunnerServer $runnerServer)
    {
        $this->runnerServer = $runnerServer;
    }

    /**
     * @param Message $message
     */
    public function process(Message $message)
    {
        switch ($message) {
            case 'run':
                try {
                    $runner = $this->runnerServer->getFreeRunner();
                    $runner->runProject($message->getContent(), $message->getFrom());
                } catch (NoAvailableRunnerException $e) {
                    $message->getFrom()->send(Message::FAILURE);
                }
            default:
                // Do nothing
        }
        $message->getFrom()->close();
    }
}

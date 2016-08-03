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
use JMS\Serializer\Serializer;

class ApiServer
{
    /**
     * @var RunnerServer
     */
    private $runnerServer;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(RunnerServer $runnerServer, Serializer $serializer)
    {
        $this->runnerServer = $runnerServer;
        $this->serializer = $serializer;
    }

    /**
     * @param Message $message
     */
    public function process(Message $message)
    {
        switch ($message->getAction()) {
            case 'run':
                echo "Start a build !\n";
                $build = $this->serializer->deserialize($message->getRawContent(), 'CiTron\\Project\\Entity\\Build', 'json');
                try {
                    $runner = $this->runnerServer->getFreeRunner();
                    $runner->runProject($build, $message->getFrom());
                } catch (NoAvailableRunnerException $e) {
                    $message->getFrom()->send(Message::FAILURE);
                    echo "No runner is free \n";
                }
                break;
            default:
                // Do nothing
                echo "Wrong message received...\n";
        }
        $message->getFrom()->close();
    }
}

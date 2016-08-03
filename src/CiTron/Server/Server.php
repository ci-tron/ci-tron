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

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Server implements MessageComponentInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $clients;

    /**
     * @var RunnerServer
     */
    private $runnerServer;

    /**
     * @var WebServer
     */
    private $webServer;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var ApiServer
     */
    private $apiServer;


    public function __construct(RunnerServer $runnerServer = null, ApiServer $apiServer = null, WebServer $webServer = null)
    {
        $this->clients = new \SplObjectStorage;
        $this->runnerServer = $runnerServer ?: new RunnerServer();
        $this->apiServer = $apiServer ?: new ApiServer($this->runnerServer);
        $this->webServer = $webServer ?: new WebServer();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $message = MessageFactory::createMessage($msg);
        $message->setFrom($from);

        switch ($message->getType()) {
            case Message::API:
                $this->processApiMessage($message);
                break;
            case Message::RUNNER:
                $this->processRunnerMessage($message);
                break;
            default:
                echo "Wrong message type: " . $message->getType() . "\n";
        }
    }

    private function processApiMessage(Message $message)
    {
        $this->apiServer->process($message);
    }

    private function processRunnerMessage(Message $message)
    {
        $this->runnerServer->process($message);
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->output->write(sprintf('<error>An error occured: %s</error>', $e->getMessage()));
        $conn->close();
    }

    public function write(string $message)
    {
        if (null !== $this->output) {
            $this->output->writeln($message);

            return;
        }

        echo $message . "\n";
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }
}

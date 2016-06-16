<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\WebsocketCommunication;


use CiTron\Tools\ArrayCollection;
use CiTron\WebsocketCommunication\Tools\Client;
use CiTron\WebsocketCommunication\Tools\MessageFactory;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MainServer implements MessageComponentInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $clients;

    public function __construct(MessageFactory $messageFactory)
    {
        $this->clients = new ArrayCollection;
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->clients[] = new Client($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->removeElementThat(function ($item) use ($conn) {
            if ($conn == $item->getConnection()) {
                return true;
            }

            return false;
        });
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // This is tomporary, we need to handle errors ^^ .
        throw $e;
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        
    }
}

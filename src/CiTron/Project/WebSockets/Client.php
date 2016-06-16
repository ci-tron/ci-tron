<?php

/**
 * This file is a part of a nekland library
 *
 * (c) Nekland <nekland.fr@gmail.fr>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace CiTron\Project\WebSockets;

use JMS\Serializer\Serializer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Client
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $host;

    /**
     * Client constructor.
     *
     * @param Serializer $serializer
     * @param int        $port
     * @param string     $host
     */
    public function __construct(Serializer $serializer, int $port, string $host)
    {
        $this->serializer = $serializer;
    }

    public function getRunnersAsJson()
    {
        $runners = '';
        \Ratchet\Client\connect('ws://' . $this->port . ':' . $this->host . '/runner')->then(function($conn) use (&$runners) {
            $conn->on('message', function ($message) use ($conn, &$runners) {
                $runners = $message;
                $conn->close();
            });
            $conn->write('API:get_runners:');
        }, function ($e) {
            throw new \Exception($e->getMessage());
        });

        return $runners;
    }

    public function getRunners()
    {
        return $this->serializer->deserialize($this->getRunnersAsJson(), 'ArrayCollection<CiTron\Server\Runner>', 'json');
    }
}

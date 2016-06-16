<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\WebsocketCommunication\Tools;


use Nekland\Tools\EqualableInterface;
use Ratchet\ConnectionInterface;

class Client implements EqualableInterface
{
    const TYPE_RUNNER = 0;
    const TYPE_STATUS_CHECKER = 1;

    private $connection;
    private $type;

    public function __construct(ConnectionInterface $connection, int $type = null)
    {
        $this->connection = $connection;

        if ($type !== null) {
            $this->setType($type);
        }
    }
    
    /**
     * @return ConnectionInterface
     */
    public function getConnection ()
    {
        return $this->connection;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        if (null !== $this->type) {
            throw new \LogicException(sprintf('A client cannot change his type. You can only set it once.'));
        }
        $types = [Client::TYPE_RUNNER, Client::TYPE_STATUS_CHECKER];

        if (!in_array($type, $types)) {
            throw new \InvalidArgumentException(sprintf('Clients of type %s does not exists.', $type));
        }

        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($item)
    {
        if (!$item instanceof Client) {
            return false;
        }

        return $this->connection === $item->getConnection();
    }
}

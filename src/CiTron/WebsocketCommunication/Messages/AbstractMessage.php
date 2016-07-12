<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\WebsocketCommunication\Messages;

use CiTron\WebsocketCommunication\Tools\MessageFactory;

/**
 * Class AbstractMessage
 *
 * Useless for now. But well, it's useful for type hinting :-) .
 */
abstract class AbstractMessage
{
    /**
     * @var MessageFactory
     */
    private $factory;

    /**
     * AbstractMessage constructor.
     * @param MessageFactory $factory
     */
    public function __construct(MessageFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return MessageFactory
     */
    protected function getFactory() : MessageFactory
    {
        return $this->factory;
    }

    /**
     * @return string
     */
    public function toJson() : string
    {
        return $this->factory->createJsonMessage($this);
    }
}

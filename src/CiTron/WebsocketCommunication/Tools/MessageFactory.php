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

use CiTron\WebsocketCommunication\Messages\AbstractMessage;
use Nekland\Tools\StringTools;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class MessageFactory
 *
 * The main purpose of this class is to generate messages for server and client.
 * This will avoid to have differences between possible requests/responses.
 */
class MessageFactory
{
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    public function __construct(PropertyAccessor $propertyAccessor)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * Transform json entry to message data object. This will throw error when the values are not conform.
     *
     * @param string $json
     * @return bool
     * @throws \Throwable
     * @throws \TypeError
     */
    public function createMessageObject(string $json)
    {
        $message = json_decode($json, true);
        if (null === $message) {
            throw new \InvalidArgumentException(
                sprintf("The following json is invalid.\n%s", $json)
            );
        }

        if (!isset($message['type'])) {
            return false;
        }

        $class = 'CiTron\\WebsocketCommunication\\Messages\\' . StringTools::camelize($message['type']) . 'Message';
        unset($message['type']);
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                sprintf("Impossible to create a message of the following type: %s", $class)
            );
        }

        $messageObject = new $class($this);
        foreach ($message as $key => $item) {
            $this->propertyAccessor->setValue($messageObject, $key, $item);
        }

        return $messageObject;
    }

    public function createJsonMessage(AbstractMessage $message) : string
    {

    }
}

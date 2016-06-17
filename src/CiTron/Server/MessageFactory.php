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

class MessageFactory
{
    public static function createMessage($str)
    {
        $message = new Message();
        preg_match('/^([A-Z]+):([a-z\_]+):(.*)/', $str, $matches);
        
        switch ($matches[1]) {
            case Message::RUNNER:
            case Message::WEB:
                $message->setType($matches[1]);
                break;
            default:
                throw new InvalidMessageException();
        }
        
        $message->setAction($matches[2]);
        
        $json = json_decode($matches[3], true);
        if ($json === null && $matches[3] !== null && $matches[3] !== '') {
            throw new InvalidMessageException;
        }
        $message->setContent($json);
        $message->setRawContent($matches[3]);
        
        return $message;
    }
}

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

class Message
{
    const RUNNER = 'RUNNER';
    const API = 'API';
    const WEB = 'WEB';
    const FAILURE = 'FAILURE';
    const SUCCESS = 'SUCCESS';

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $content;

    /**
     * @var string
     */
    private $action;

    /**
     * @var ConnectionInterface
     */
    private $from;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return ConnectionInterface
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param ConnectionInterface $from
     * @return self
     */
    public function setFrom(ConnectionInterface $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }
}

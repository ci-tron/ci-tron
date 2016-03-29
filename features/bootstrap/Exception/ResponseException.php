<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Behat\Exception;

class ResponseException extends \Exception
{
    public function __construct($response)
    {
        parent::__construct(sprintf(
            "The login failed with, status code %s and content:\n%s",
            $response->getStatusCode(),
            (string) $response->getBody()
        ));
    }
}

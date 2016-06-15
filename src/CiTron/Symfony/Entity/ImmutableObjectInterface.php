<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Symfony\Entity;

interface ImmutableObjectInterface
{
    /**
     * This function will create a value object
     * @return object
     */
    static public function create();
}

<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Factory;

use CiTron\Project\Entity\Configuration;

class ConfigurationFactory
{
    public function create()
    {
        return Configuration::create();
    }
}

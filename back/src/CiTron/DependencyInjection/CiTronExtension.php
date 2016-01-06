<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class CiTronExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    public function getAlias()
    {
        return 'ci_tron';
    }
}

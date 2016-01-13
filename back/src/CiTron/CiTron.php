<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron;

use CiTron\DependencyInjection\Compiler\DoctrineMappingCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CiTron extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineMappingCompilerPass());
    }
}

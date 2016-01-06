<?php

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

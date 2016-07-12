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


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CiTronExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $kernelRootDir = $container->getParameter('kernel.root_dir');
        $environment = $container->getParameter('kernel.environment');
        
        if ($environment === 'test') {
            $loader = new YamlFileLoader($container, new FileLocator($kernelRootDir . '/config/services'));
            $loader->load('mocks.yml');
        }
    }

    public function getAlias()
    {
        return 'ci_tron';
    }
}

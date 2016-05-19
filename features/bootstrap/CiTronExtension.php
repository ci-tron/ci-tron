<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Behat;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * The CiTron extension for behat
 */
class CiTronExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->addCompilerPass(new Compiler\HttpClientWithCookiesCompiler);
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $hydratorDefinition = $container->getDefinition('friendly.entity.hydrator');
        $hydratorDefinition->setClass('CiTron\Behat\FriendlyContext\Doctrine\EntityHydrator');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'citron_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        // Nothing to do for now.
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        // Nothing to do for now.
    }
}

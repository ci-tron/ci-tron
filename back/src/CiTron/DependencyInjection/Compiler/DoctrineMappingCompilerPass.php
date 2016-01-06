<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DoctrineMappingCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $ormConfigDef = $container->getDefinition('doctrine.orm.default_configuration');

        $projectDir = __DIR__ . '/../..';
        $iterator = $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($projectDir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST
        );

        // Guessing entity folders and defining mapping
        $mapping = [];
        $folders = [];
        foreach ($iterator as $file) {
            if ($file->isDir() && $file->getBasename() === 'Entity') {
                if ('..' === $alias = basename($file->getPath())) {
                    continue;
                }

                $folder = $file->getRealPath();
                $namespace = explode('CiTron/', $folder);
                $namespace = 'CiTron\\' . str_replace('/', '\\', $namespace[count($namespace)-1]);

                $mapping[$alias] = $namespace;
                $folders[$namespace] = $folder;
            }
        }

        // Setting the mapping alias -> namespace
        foreach($mapping as $alias => $namespace) {
            var_dump($alias);
            $ormConfigDef->addMethodCall('addEntityNamespace', [$alias, $namespace]);
        }

        // Setting what folder goes with what driver (for doctrine mapping)
        // for us it will only be *annotation*
        $driver = $this->getAnnotationDriver($container);
        $driver->addMethodCall('addPaths', [array_values($folders)]);

        $chainDriverDef = $container->getDefinition('doctrine.orm.default_metadata_driver');

        foreach (array_keys($folders) as $namespace) {
            $chainDriverDef->addMethodCall('addDriver', [
                new Reference('doctrine.orm.default_annotation_metadata_driver'),
                $namespace,
            ]);
        }

        // Adding folders to driver
    }

    /**
     * In the case of there is no « standard » entity folder in any bundle with no annotation, then the doctrine
     * bundle never create the driver service we need, so let's do it, just in case.
     *
     * @param ContainerBuilder $container
     * @return Definition
     */
    private function getAnnotationDriver(ContainerBuilder $container)
    {

        if ($container->hasDefinition('doctrine.orm.default_annotation_metadata_driver')) {
            $driver = $container->getDefinition('doctrine.orm.default_annotation_metadata_driver');
        } else {
            $driver = new Definition('%doctrine.orm.metadata.annotation.class%', [
                new Reference('doctrine.orm.metadata.annotation_reader')
            ]);
            $driver->setPublic(false);
            $container->setDefinition('doctrine.orm.default_annotation_metadata_driver', $driver);
        }

        return $driver;
    }
}

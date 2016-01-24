<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Behat\Compiler;


use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Modify the friendly http client (which is guzzle3) to supports cookies between requests.
 * We need that because we use standard session auth via cookie.
 */
class HttpClientWithCookiesCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // Adding the array cookie jar as service because we need to get it later.
        $cookieJarDefinition = new Definition('Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar');
        $container->setDefinition('citron.guzzle.array_cookie_jar', $cookieJarDefinition);

        $cookiePluginDefinition = new Definition('Guzzle\Plugin\Cookie\CookiePlugin', [new Reference('citron.guzzle.array_cookie_jar')]);
        $container->setDefinition('citron.guzzle.cookie_plugin', $cookiePluginDefinition);

        $definition  = $container->getDefinition('friendly.http_client');
        $definition->addMethodCall('addSubscriber', [new Reference('citron.guzzle.cookie_plugin')]);
    }
}

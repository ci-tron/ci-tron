<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

class ServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('citron:server')
            ->setDescription('Starts a WebSockets server that listen for runners and can send to the client progression on build in real time')
            ->addOption(
                'port',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Change the port where will be bind the server.',
                8282
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $router = new Router();
        $transportProvider = new RatchetTransportProvider("127.0.0.1", $input->getOption('port'));
        $router->addTransportProvider($transportProvider);
        $router->start();
    }
}

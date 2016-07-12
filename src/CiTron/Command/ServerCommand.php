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


use CiTron\WebsocketCommunication\MainServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;

class ServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('citron:server')
            ->setDescription('Websocket based server for communication with runners.')
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
        $output->writeln('Running ci-tron server on port ' . $input->getOption('port'));

        try {
            $app = new \Ratchet\App('localhost', 8080);
            $app->route('/chat', $this->getContainer()->get('main_server'));
            //$app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
            $app->run();
        } catch (ContextErrorException $e) {
            if (
                $e->getMessage() === 'User Warning: XDebug extension detected. Remember to disable this if performance testing or going live!'
                && !$this->getContainer()->getParameter('kernel.debug')
            ) {
                throw $e;
            }
        }
    }
}

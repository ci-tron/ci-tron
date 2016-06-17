<?php

namespace CiTron\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CitronServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('citron:server')
            ->setDescription('Launch a server waiting for runners.')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
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
        $port = $input->getOption('port');
        $server = new \Ratchet\App(
            '127.0.0.1',
            $port,
            '127.0.0.1',
            null,
            $this->getContainer()->get('kernel')->getEnvironment() !== 'prod'
        );

        $serverListener = $this->getContainer()->get('citron.server.main');
        $serverListener->setOutput($output);

        $server->route('/runner', $serverListener, ['*']);

        $output->writeln('<info>Starting server on port '. $port .'</info>');
        $server->run();
    }

}

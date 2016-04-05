<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Nekland <nekland.fr@gmail.fr>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Transport\PawlTransportProvider;

class CitronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('citron:client')
            ->setDescription('FOOBAR')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client("citron");
        $client->addTransportProvider(new PawlTransportProvider("ws://127.0.0.1:8282/"));

        $client->on('open', function (ClientSession $session) use ($output) {
            // 1) subscribe to a topic
            $session->subscribe('org.citron.client.build', function ($args) use ($session, $output) {
                $session->call('org.citron.runner', ['foo', 'bar'])->then(
                    function ($res)  use ($output) {
                        var_dump($res);
                        $output->write('<info>runned</info>');
                    },
                    function ($error) {
                        echo "Call Error: {$error}\n";
                    }
                );
            });

            /*
            // 2) publish an event
            $session->publish('com.myapp.hello', ['Hello, world from PHP!!!'], [], ["acknowledge" => true])->then(
                function () {
                    echo "Publish Acknowledged!\n";
                },
                function ($error) {
                    // publish failed
                    echo "Publish Error {$error}\n";
                }
            );
            */

            /*
            // 3) register a procedure for remoting
            $add2 = function ($args) {
                return $args[0] + $args[1];
            };
            $session->register('com.myapp.add2', $add2);
            */

            /*
            // 4) call a remote procedure
            $session->call('com.myapp.add2', [2, 3])->then(
                function ($res) {
                    echo "Result: {$res}\n";
                },
                function ($error) {
                    echo "Call Error: {$error}\n";
                }
            );
            */
        });

        $client->start();
    }
}

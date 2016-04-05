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

class RunnerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('citron:runner')
            ->setDescription('FOOBAR')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client("citron");
        $client->addTransportProvider(new PawlTransportProvider("ws://127.0.0.1:8282/"));

        $client->on('open', function (ClientSession $session) {

            /*
            // 1) subscribe to a topic
            $onevent = function ($args) {
                echo "Event {$args[0]}\n";
            };
            $session->subscribe('org.citron.client', $onevent);
            */


            /*
            // 2) publish an event
            $session->publish('org.citron.client.build', ['Hello, world from PHP!!!'], [], ["acknowledge" => true])->then(
                function () use ($session) {
                    echo "Publish Acknowledged!\n";
                    $session->close();
                    $session->getLoop()->stop();
                },
                function ($error) {
                    // publish failed
                    echo "Publish Error {$error}\n";
                }
            );
            */

            //$session->shutdown();
            //return false;

            //*
            // 3) register a procedure for remoting
            $session->register('org.citron.runner', function ($args) {
                var_dump($args);
                sleep(20);
                return 'hello';
            });
            //*/

            /*
            // 4) call a remote procedure
            $session->call('org.citron.runner', ['foo', 'bar'])->then(
                function ($res) {
                    echo "Result: {$res}\n";
                },
                function ($error) {
                    echo "Call Error: {$error}\n";
                }
            );
            //*/
        });

        $client->start();
    }
}

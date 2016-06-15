<?php

namespace spec\CiTron\Server;

use CiTron\Server\ApiServer;
use CiTron\Server\Message;
use CiTron\Server\Runner;
use CiTron\Server\RunnerServer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ratchet\ConnectionInterface;
use spec\Prophecy\Doubler\Generator\Node\ArgumentNodeSpec;

class ApiServerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ApiServer::class);
    }

    public function let(RunnerServer $runnerServer)
    {
        $this->beConstructedWith($runnerServer);
    }

    function it_should_close_connection_on_wrong_action(Message $message, ConnectionInterface $connection)
    {
        $message->getFrom()->willReturn($connection);
        $message->getAction()->willReturn('random_wrong_action');
        $connection->close()->shouldBeCalled();

        $this->process($message);
    }

    function it_should_get_and_run_runner_when_run_action_called(Message $message, RunnerServer $runnerServer, Runner $runner, ConnectionInterface $connection)
    {
        $message->getAction()->willReturn('run');
        $message->getFrom()->willReturn($connection);
        $runnerServer->getFreeRunner()->willReturn($runner);
        $runner->runProject(Argument::type('CiTron\Project\Entity\Build'), $connection)->shouldBeCalled();

        $this->process($message);
    }
}

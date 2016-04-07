<?php

namespace spec\CiTron\WebsocketCommunication\Tools;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder;

class MessageFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CiTron\WebsocketCommunication\Tools\MessageFactory');
    }

    function let()
    {
        $builder = new PropertyAccessorBuilder();
        $this->beConstructedWith($builder->getPropertyAccessor());
    }

    function it_is_should_throw_exception_when_argument_is_wrong()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringCreateMessageObject('yolo message');
    }
    
    function it_should_transform_json_data_to_message()
    {
        $json = '{"type": "runner_hello", "name": "foobar"}';
        
        $this->createMessageObject($json)->shouldBeRunnerHelloMessage('foobar');
    }

    public function getMatchers()
    {
        return [
            'beRunnerHelloMessage' => function ($res, $name) {
                if (!$res instanceof \CiTron\WebsocketCommunication\Messages\RunnerHelloMessage) {
                    return false;
                }

                if ($res->getName() !== $name) {
                    return false;
                }

                return true;
            }
        ];
    }
}

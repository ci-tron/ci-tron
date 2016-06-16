<?php

namespace spec\CiTron\Server;

use CiTron\Server\Message;
use CiTron\Server\MessageFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MessageFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(MessageFactory::class);
    }
    
    function it_should_guess_message_type()
    {
        $this::shouldThrow()->duringCreateMessage('RUNNER:init:Hello world');
    }
    
    function it_should_create_message()
    {
        $this::createMessage('RUNNER:test_init:{"foo": "bar"}')->shouldReturnMessageThat(Message::RUNNER, 'test_init', ['foo' => 'bar']);
    }
    
    function it_should_work_without_content()
    {
        $this::createMessage('RUNNER:init:')->shouldReturnMessageThat(Message::RUNNER, 'init', null);
    }
    
    public function getMatchers()
    {
        return [
            'returnMessageThat' => function ($subject, $type, $action, $content) {
                if (!$subject instanceof Message) {
                    return false;
                }
                
                if ($subject->getType() !== $type) {
                    return false;
                }
                
                if ($subject->getContent() !== $content) {
                    return false;
                }
                
                if ($subject->getAction() !== $action) {
                    return false;
                }
                
                return true;
            }
        ];
    }
}

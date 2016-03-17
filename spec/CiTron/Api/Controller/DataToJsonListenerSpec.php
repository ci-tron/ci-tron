<?php

namespace spec\CiTron\Api\Controller;

use CiTron\Symfony\HttpFoundation\JsonResponse;
use JMS\Serializer\Serializer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class DataToJsonListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CiTron\Api\Controller\DataToJsonListener');
    }

    function let (Serializer $serializer)
    {
        $this->beConstructedWith($serializer);
    }

    function it_should_transform_controller_result_to_json(Serializer $serializer, GetResponseForControllerResultEvent $event)
    {
        $event->getControllerResult()->willReturn(['foo' => 'bar']);
        $serializer->serialize(['foo' => 'bar'], Argument::cetera())->willReturn('["json"]');
        $event->setResponse(Argument::that(function ($item) {
            if (!$item instanceof JsonResponse) {
                return false;
            }

            if ($item->getContent() !== '["json"]') {
                return false;
            }

            return true;
        }))->shouldBeCalled();

        $this->onView($event);
    }

    function it_should_return_new_response_when_controller_return_string(Serializer $serializer, GetResponseForControllerResultEvent $event)
    {
        $event->getControllerResult()->willReturn('random string');
        $event->setResponse(Argument::type('Symfony\Component\HttpFoundation\Response'));
    }
}

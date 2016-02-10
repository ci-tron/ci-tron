<?php

namespace spec\CiTron\Api\Controller;

use CiTron\Symfony\HttpFoundation\JsonResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class JsonErrorsListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('prod');
        $this->shouldHaveType('CiTron\Api\Controller\JsonErrorsListener');
    }

    function it_should_transform_errors_to_json_responses(FilterResponseEvent $event, Response $response)
    {
        $this->beConstructedWith('prod');
        $event->getResponse()->willReturn($response);
        $response->getStatusCode()->willReturn(404);
        $event->setResponse(Argument::type('Symfony\Component\HttpFoundation\JsonResponse'))->shouldBeCalled();

        $this->onKernelResponse($event);
    }

    function it_should_not_transform_response_if_not_prod_env(FilterResponseEvent $event, Response $response)
    {
        $this->beConstructedWith('dev');
        $event->getResponse()->willReturn($response);
        $event->setResponse()->shouldNotBeCalled();

        $this->onKernelResponse($event);
    }

    function it_should_not_transform_response_if_200_response(FilterResponseEvent $event, Response $response)
    {
        $this->beConstructedWith('prod');
        $event->getResponse()->willReturn($response);
        $event->setResponse()->shouldNotBeCalled();
        $response->getStatusCode()->willReturn(200);

        $this->onKernelResponse($event);
    }

    function it_should_not_transform_if_response_is_json(FilterResponseEvent $event, JsonResponse $response)
    {
        $this->beConstructedWith('prod');
        $event->getResponse()->willReturn($response);
        $event->setResponse()->shouldNotBeCalled();
        $response->getStatusCode()->willReturn(200);

        $this->onKernelResponse($event);
    }
}

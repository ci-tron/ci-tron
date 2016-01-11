<?php

namespace spec\CiTron\Symfony\HttpFoundation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonResponseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CiTron\Symfony\HttpFoundation\JsonResponse');
    }

    function it_should_supports_json_addition_without_reparsing_it()
    {
        $this->setJsonData(json_encode(['foo' => 'bar']));
        $this->getContent()->shouldReturn('{"foo":"bar"}');
    }
}

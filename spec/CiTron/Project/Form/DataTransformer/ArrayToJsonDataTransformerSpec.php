<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace spec\CiTron\Project\Form\DataTransformer;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\Exception\TransformationFailedException;


class ArrayToJsonDataTransformerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CiTron\Project\Form\DataTransformer\ArrayToJsonDataTransformer');
    }

    function it_should_transform_array_to_json()
    {
        $this->reverseTransform([1, 2, 'foo'])->shouldReturn("[1,2,\"foo\"]");
        $this->reverseTransform(null)->shouldReturn("");
    }

    function it_should_transform_json_to_array()
    {
        $this->transform("[1,2,\"foo\"]")->shouldReturn([1, 2, 'foo']);
    }
}

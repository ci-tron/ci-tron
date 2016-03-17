<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */


namespace spec\CiTron\Project\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProjectVoterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CiTron\Project\Security\ProjectVoter');
    }
}

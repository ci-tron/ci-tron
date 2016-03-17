<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */


namespace spec\CiTron\Project\Request\ParamConverter;

use CiTron\Project\Entity\Project;
use CiTron\Project\Repository\ProjectRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class ProjectParamConverterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('CiTron\Project\Request\ParamConverter\ProjectParamConverter');
    }

    function let(ProjectRepository $projectRepository)
    {
        $this->beConstructedWith($projectRepository);
    }

    function it_should_supports_class(ParamConverter $paramConverter)
    {
        $paramConverter->getClass()->willReturn('CiTron\Project\Entity\Project');

        $this->supports($paramConverter)->shouldReturn(true);
    }

    function it_should_properly_convert_parameter(
        Request $request,
        ParamConverter $paramConverter,
        Project $project,
        ProjectRepository $projectRepository,
        ParameterBag $parameterBag
    ) {
        $parameterBag->has('projectSlug')->willReturn(true);
        $parameterBag->get('projectSlug')->willReturn('foobar');
        $parameterBag->set(Argument::type('string'), Argument::any())->shouldBeCalled();

        $request->attributes = $parameterBag;

        $projectRepository->findOneBy(['slug' => 'foobar'])->willReturn($project);

        $paramConverter->getClass()->willReturn('CiTron\Project\Entity\Project');
        $paramConverter->getName()->willReturn('project');

        $this->apply($request, $paramConverter)->shouldReturn(true);
    }
}

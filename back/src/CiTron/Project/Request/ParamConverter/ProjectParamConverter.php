<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Request\ParamConverter;

use CiTron\Project\Entity\Project;
use CiTron\Project\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectParamConverter implements ParamConverterInterface
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * ProjectParamConverter constructor.
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $parameter = 'projectSlug';

        if (!$request->attributes->has($parameter)) {
            return false;
        }

        $projectSlug = $request->attributes->get($parameter);

        $project = $this->projectRepository->findOneBy(['slug' => $projectSlug]);

        if (null === $project || !($project instanceof Project)) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $project);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {

        if (null === $configuration->getClass()) {
            return false;
        }

        return 'CiTron\Project\Entity\Project' === $configuration->getClass();
    }

}

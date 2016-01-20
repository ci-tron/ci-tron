<?php

/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Controller;

use CiTron\Controller\Controller;
use CiTron\Project\Entity\Project;
use CiTron\Project\Form\ProjectType;
use CiTron\Symfony\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProjectController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/secured/projects/new", name="project_creation")
     * @Method({"POST"})
     */
    public function createProjectAction(Request $request)
    {
        if (!$this->getUser()) {
            return new JsonResponse([
                'errors' => 'Unauthorized action',
            ], 401);
        }

        $project = new Project();

    	$form = $this->createNamedForm('', ProjectType::class, $project);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return new JsonResponse([
                'errors' => $this->getFormErrors($form),
            ], 400);
        }

        $project->setUser($this->getUser());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($project);
        $entityManager->flush();

        return new JsonResponse([
            'project' => [
                'id'   => $project->getId(),
                'name' => $project->getName(),
            ],
        ], 200);
    }
}

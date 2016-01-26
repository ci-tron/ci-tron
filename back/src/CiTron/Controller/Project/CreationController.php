<?php
/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Controller\Project;


use CiTron\Controller\Controller;
use CiTron\Project\Entity\Project;
use CiTron\Project\Form\ProjectType;
use Symfony\Component\HttpFoundation\Request;

class CreationController extends Controller
{
    /**
     * Allow creation of a new project.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Request $request)
    {
        $project = new Project;
        $form = $this->createNamedForm('', ProjectType::class, $project);

        if ($form->isValid()) {
            $project->setOwner($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->successResponse('Successfully saved project.');
        }

        return $this->formErrorResponse($form);
    }
}

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
use CiTron\User\Entity\User;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

    /**
     * @param User $user
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects.json", name="user_projects")
     * @Route("/users/{slug}/projects.json", name="public_user_projects")
     * @Method({"GET"})
     */
    public function getProjectsAction(User $user)
    {
        $visibilityPolicy = [2];

        if ($this->getUser() instanceof User) {
            $visibilityPolicy[] = 1;

            if ($this->getUser() === $user) {
                $visibilityPolicy[] = 0;
            }
        }

        $projects = $this->getDoctrine()->getRepository('Project:Project')->findBy([
            'user' => $user->getId(),
            'visibility' => $visibilityPolicy,
        ]);

        if (empty($projects)) {
            return $this->errorResponse('No project found', 404);
        }

        $list = [];

        foreach ($projects as $project) {
            $list[] = $this->get('jms_serializer')->serialize(
                $project,
                'json',
                SerializationContext::create()->setGroups(['standard'])
            );
        }

        return new JsonResponse([
            'projects' => $list,
        ]);
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}.json", name="user_project")
     * @Route("/users/{slug}/projects/{projectSlug}.json", name="public_user_project")
     * @Method({"GET"})
     * @Security("is_granted('read', project)")
     */
    public function getProjectAction(Project $project)
    {
        return $this->get('jms_serializer')->serialize(
            $project,
            'json',
            SerializationContext::create()->setGroups(['standard'])
        );
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}/delete", name="user_project_deletion")
     * @Method({"DELETE"})
     * @Security("is_granted('delete', project)")
     */
    public function deleteProjectAction(Project $project)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($project);
        $entityManager->flush();

        return $this->successResponse('Project deleted');
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}/edit", name="user_project_edition")
     * @Method({"POST"})
     * @Security("is_granted('edit', project)")
     */
    public function editProjectAction(Request $request, Project $project)
    {
        $form = $this->createNamedForm('', ProjectType::class, $project);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return new JsonResponse([
                'errors' => $this->getFormErrors($form),
            ], 400);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse([
            'project' => [
                'id'   => $project->getId(),
                'name' => $project->getName(),
            ],
        ], 200);
    }
}

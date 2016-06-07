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
use CiTron\Project\Entity\Build;
use CiTron\Project\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BuildController extends Controller
{
    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}/builds/new", name="user_project_builds_new")
     * @Method({"GET"})
     * @Security("is_granted('read', project)")
     */
    public function newBuildAction(Project $project)
    {
        //starting build
        $em = $this->getDoctrine()->getEntityManager();

        $build = new Build();

        $em->persist($build);
        $em->flush();

        return new $build;
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}/builds.json", name="user_project_builds")
     * @Route("/users/{slug}/projects/{projectSlug}/builds.json", name="public_user_project_builds")
     * @Method({"GET"})
     * @Security("is_granted('read', project)")
     */
    public function getBuildsAction(Project $project)
    {
        return $project->getBuilds();
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}/builds.json", name="user_project_builds")
     * @Route("/users/{slug}/projects/{projectSlug}/builds.json", name="public_user_project_builds")
     * @Method({"GET"})
     * @Security("is_granted('read', project)")
     */
    public function getBuildAction(Project $project, Build $build)
    {
        return $build;
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}/builds/{buildId}/relaunch", name="user_project_builds_relaunch")
     * @Method({"POST"})
     * @Security("is_granted('edit', project)")
     */
    public function relaunchBuildAction(Project $project, Build $build)
    {
        //relaunching build
        $em = $this->getDoctrine()->getEntityManager();

        $newBuild = clone $build;

        $em->persist($newBuild);
        $em->flush();

        return new $newBuild;
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/users/{slug}/projects/{projectSlug}/builds/{buildId}/log.json", name="user_project_builds_relaunch")
     * @Method({"GET"})
     * @Security("is_granted('read', project)")
     */
    public function getBuildLogAction(Project $project, Build $build)
    {
        return $build->getLog();
    }
}

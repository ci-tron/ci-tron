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
     * @return Build
     *
     * @Route("/projects/{slug}/builds/new/{commit}", name="user_project_builds_new")
     * @Method({"GET"})
     * Security("is_granted('read', project)")
     */
    public function newBuildAction(Project $project, $commit)
    {
        //starting build
        $em = $this->getDoctrine()->getEntityManager();

        $build = new Build();
        $build->setProject($project);
        $build->setCommit($commit);
        $build->setNumber(count($project->getBuilds()) + 1); // yolo

        $em->persist($build);
        $em->flush();

        $this->get('citron.project.websocket.client')->run($build);

        return $this->serializeWithGroups($build, ['partial']);
    }

    /**
     * @param Project $project
     * @return JsonResponse
     *
     * @Route("/secured/projects/{slug}/{projectSlug}/builds.json", name="user_project_builds")
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
     * @Route("/secured/users/{slug}/projects/{projectSlug}/builds/{buildId}/log.json", name="user_project_builds_relaunch")
     * @Method({"GET"})
     * @Security("is_granted('read', project)")
     */
    public function getBuildLogAction(Project $project, Build $build)
    {
        return $build->getLog();
    }
}

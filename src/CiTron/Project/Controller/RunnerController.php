<?php
/**
 * This file is a part of a nekland library
 *
 * (c) Nekland <nekland.fr@gmail.fr>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

namespace CiTron\Project\Controller;


use CiTron\Controller\Controller;
use CiTron\Symfony\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RunnerController extends Controller
{
    /**
     * @return JsonResponse
     * 
     * @Route("/secured/runners", name="runners_status")
     * @Method({"GET"})
     */
    public function getRunnersAction()
    {
        $runners = $this->get('citron.project.websocket.client')->getRunnersAsJson();
        
        $response = new JsonResponse();
        $response->setJsonData($runners);
        
        return $response;
    }
}

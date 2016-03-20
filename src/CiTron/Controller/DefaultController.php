<?php

namespace CiTron\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return array
     *
     * @Route("/", name="home")
     */
    public function helloAction(Request $request)
    {
        // replace this example code with whatever you need
        return ['hello world'];
    }


    public function frontAction(Request $request)
    {
        $rootDir = $this->container->getParameter('kernel.root_dir');
        if ($this->container->has('profiler')) {
            $this->container->get('profiler')->disable();
        }

        if (in_array($this->container->getParameter('kernel.environment'), ['dev', 'test'])) {
            return new Response(file_get_contents($rootDir . '/../src-js/dev.html'));
        }
        if ($this->container->getParameter('kernel.environment') === 'prod') {
            return new Response(file_get_contents($rootDir . '/../src-js/prod.html'));
        }

        throw new \Exception ('This part is not done for now: there is code to write.');
    }

    public function notFoundAction()
    {
        throw $this->createNotFoundException('JS File not found. (And you should not create .js URIs with the front app !)');
    }
}

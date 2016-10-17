<?php

namespace CiTron\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SfController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends SfController
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
}

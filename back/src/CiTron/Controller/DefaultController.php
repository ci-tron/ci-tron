<?php

namespace CiTron\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
}

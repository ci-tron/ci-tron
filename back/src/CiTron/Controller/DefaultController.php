<?php

namespace CiTron\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function helloAction(Request $request)
    {
        // replace this example code with whatever you need
        return ['hello world'];
    }
}

<?php

namespace Undf\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UndfDemoBundle:Default:index.html.twig', array('name' => $name));
    }
}

<?php

namespace PlSharer\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PlSharerAuthBundle:Default:index.html.twig', array('name' => $name));
    }
}

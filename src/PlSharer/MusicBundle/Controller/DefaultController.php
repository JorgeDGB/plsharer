<?php

namespace PlSharer\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PlSharerMusicBundle:Default:index.html.twig', array('name' => $name));
    }
}

<?php

namespace AngryProgrammers\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        return $this->render('AngryProgrammersBlogBundle:Blog:index.html.twig');
    }
}

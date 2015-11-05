<?php

namespace AngryProgrammers\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    public function indexAction()
    {
        return $this->render('AngryProgrammersBlogBundle:Blog:index.html.twig');
    }
	
	public function postAction($id)
    {
        return $this->render('AngryProgrammersBlogBundle:Blog:post.html.twig', array('id' => $id));
    }

    	public function adminAction()
    {
        return $this->render('AngryProgrammersBlogBundle:Blog:admin.html.twig');
    }
}

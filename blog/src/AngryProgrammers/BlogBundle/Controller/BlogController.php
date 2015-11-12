<?php

namespace AngryProgrammers\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AngryProgrammers\BlogBundle\Entity\Billet;
use AngryProgrammers\BlogBundle\Entity\LikeBillet;
use AngryProgrammers\BlogBundle\Form\BilletType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function indexAction()
    {
		//rend la liste des billets
		$em = $this->getDoctrine()->getManager(); 
		$listeBillet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findAll();   
		$user = $this->getUser();
		
		$listeVide = "Aucun article n'a été publié !";
		$nbLikeBillet;
		
		for ($i = 0; $i < sizeOf($listeBillet); $i++) {
			$nbLikeBillet[$i] = count($em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findByBillet(array('billet' => $listeBillet[$i])));
		}
		
		if ($user != NULL) 
		{
			if (count($listeBillet) > 0) 
			{
				$likeBillet;
				
				for ($i = 0; $i < sizeOf($listeBillet); $i++) {
					if ($em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findBy(array('billet' => $listeBillet[$i], 'auteur' => $user)) != NULL) {
						$likeBillet[$i] = $em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findBy(array('billet' => $listeBillet[$i], 'auteur' => $user));
					}
					else {
						$likeBillet[$i] = NULL;
					}
				}
				
				if ($likeBillet == NULL)
				{
					return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("liste" => $listeBillet, "user" => $user, "nbLikeBillet" => $nbLikeBillet));
				}
				else
				{
					return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("liste" => $listeBillet, "user" => $user, "likeBillet" => $likeBillet, "nbLikeBillet" => $nbLikeBillet));
				}
			}
			else
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("listeVide" => $listeVide, "user" => $user));
			}
		}
		else 
		{
			if (count($listeBillet) > 0)
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("liste" => $listeBillet, "nbLikeBillet" => $nbLikeBillet));
			}
			else
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("listeVide" => $listeVide));
			}
		}
    }
	
	public function postAction($slug)
    {
		$em = $this->getDoctrine()->getManager();
		$array = explode("-", $slug);
		$id = $array[0];
		$slugWithoutFirstTwoCaracters = substr($slug, 2);
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);   
		
		if ($slugWithoutFirstTwoCaracters != $billet->getSlug()) {
			return $this->redirect($this->generateUrl('angry_programmers_blog_homepage'));
		}
		
		$user = $this->getUser();
		
		$listeVide = "Aucun article n'a été publié !";
		$nbLikeBillet = count($em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findByBillet(array('billet' => $billet)));
		
		if ($user != NULL) {
			if (count($billet) > 0)
			{
				$likeBillet = $em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findBy(array('billet' => $billet, 'auteur' => $user));
				
				if ($likeBillet == NULL)
				{
					return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("billet" => $billet, "user" => $user, "nbLikeBillet" => $nbLikeBillet));
				}
				else
				{
					return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("billet" => $billet, "user" => $user, "likeBillet" => $likeBillet, "nbLikeBillet" => $nbLikeBillet));
				}
			}
			else
			{
				return $this->redirect($this->generateUrl('angry_programmers_blog_homepage'));
			}
		}
		else {
			if (count($billet) > 0)
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("billet" => $billet, "nbLikeBillet" => $nbLikeBillet));
			}
			else
			{
				return $this->redirect($this->generateUrl('angry_programmers_blog_homepage'));
			}
		}
    }

    public function adminAction()
    {
        //rend la liste des billets
		$em = $this->getDoctrine()->getManager(); 
		$user = $this->getUser();
		$listeBillet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findAll();   
		
		$listeVide = "Aucun article n'a été publié !";
		
		if (count($listeBillet) > 0)
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:admin.html.twig",array("liste" => $listeBillet, "user" => $user));
		}
		else
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:admin.html.twig",array("listeVide" => $listeVide, "user" => $user));
		}
    }
	
	public function likeAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager(); 

		header("Pragma: no-cache");

		if(isset($_SERVER['HTTP_REFERER'])) 
		{
        	$adrAppelante=$_SERVER['HTTP_REFERER']; 
   		} 
   		else
   		{
   			$adrAppelante = "/";
   		}
		
		$user = $this->getUser();
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);

		if($billet == NULL)
		{
			return $this->redirect($adrAppelante);
		}
		
		$likeBillet = $em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findOneBy(array('billet' => $billet, 'auteur' => $user));
		
		$slug = $billet->getId().'-'.$billet->getSlug();
		
		// si l'utilisateur n'a pas encore aimé le billet et qu'il l'aime
		if ($likeBillet == NULL)
		{
			$likeBillet = new LikeBillet();
			$likeBillet->setAuteur($user);
			$likeBillet->setBillet($billet);
		
			$em->persist($likeBillet);
			$em->flush();
		}
		else //sinon si il a aimé et qu'il ne l'aime plus 
		{
			$em->remove($likeBillet);
			$em->flush();
		}
		
		return $this->redirect($adrAppelante);
	}
}

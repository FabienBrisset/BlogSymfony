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
		
		if ($user != NULL) {
			if (count($listeBillet) > 0)
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("liste" => $listeBillet, "user" => $user));
			}
			else
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("listeVide" => $listeVide, "user" => $user));
			}
		}
		else {
			if (count($listeBillet) > 0)
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("liste" => $listeBillet));
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
		$array = explode("_",$slug);
		$id = $array[0];
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);   
		
		$user = $this->getUser();
		
		$listeVide = "Aucun article n'a été publié !";
		
		if ($user != NULL) {
			if (count($billet) > 0)
			{
				$likeBillet = $em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findBy(array('billet' => $billet, 'auteur' => $user));
				$nbLikeBillet = count($em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findByBillet(array('billet' => $billet)));
				
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
				return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("listeVide" => $listeVide, "user" => $user));
			}
		}
		else {
			if (count($billet) > 0)
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("billet" => $billet));
			}
			else
			{
				return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("listeVide" => $listeVide));
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
	
	//se reporter à "http://localhost:8000/admin/ajoutBillet" (droit réservé à l'admin uniquement)
	public function ajoutBilletAction(Request $request)
	{
		$billet = new Billet();
		$user = $this->getUser();

		//instanciation de l'objet formulaire dans lequel on ajoute le bouton submit d'ajout du billet
		$form = $this->createForm(new BilletType(), $billet)
			->add('Ajouter le billet', 'submit');
		
		

		if ($form->handleRequest($request)->isValid()) {
			//obtenir l'utilisateur courant
			$billet->setAuteur($this->getUser());
			//obtenir la date courante			
			$billet->setDate(new \Datetime());
			$billet->setModifie(false);
			//création du slug (il faudra gérer les possibles doublons)
			$billet->setSlug($billet->slugify($billet->getTitre()));
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($billet);
			$em->flush();
			
			$request->getSession()->getFlashBag()->add('notice', 'Billet bien enregistré.');

			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}

		return $this->render('AngryProgrammersBlogBundle:Blog:ajoutBillet.html.twig', array('form' => $form->createView(), "user" => $user));
	}
	
	//se reporter à "http://localhost:8000/admin/modifierBillet" (droit réservé à l'admin uniquement)
	public function modifierBilletAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager(); 
		$user = $this->getUser();
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);   

		//instanciation de l'objet formulaire dans lequel on ajoute le bouton submit d'ajout du billet
		$form = $this->createForm(new BilletType(), $billet)
			->add('Modifier le billet', 'submit');
		
		

		if ($form->handleRequest($request)->isValid()) {
			//obtenir l'utilisateur courant
			$billet->setAuteur($this->getUser());
			//obtenir la date courante			
			$billet->setDate(new \Datetime());
			//création du slug (il faudra gérer les possibles doublons)
			$billet->setSlug($billet->slugify($billet->getTitre()));
			$billet->setModifie(true);
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($billet);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Billet bien modifié.');

			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}

		return $this->render('AngryProgrammersBlogBundle:Blog:modifierBillet.html.twig', array('form' => $form->createView(), "user" => $user));
	}
	
	//se reporter à "http://localhost:8000/admin/supprimerBillet" (droit réservé à l'admin uniquement)
	public function supprimerBilletAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager(); 
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);   
			
		$em->remove($billet);
		$em->flush();

		$request->getSession()->getFlashBag()->add('notice', 'Billet bien supprimé.');

		return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
	}
	
	public function likeBilletAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();   
		
		$user = $this->getUser();
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);
		
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
		
		return $this->redirect('/post/'.$slug);
	}
}

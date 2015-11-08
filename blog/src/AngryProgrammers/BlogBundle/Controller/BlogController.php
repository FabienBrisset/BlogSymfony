<?php

namespace AngryProgrammers\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AngryProgrammers\BlogBundle\Entity\Billet;
use AngryProgrammers\BlogBundle\Form\BilletType;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends Controller
{
    public function indexAction()
    {
		//rend la liste des billets
		$em = $this->getDoctrine()->getManager(); 
		$listeBillet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findAll();   
		
		if (count($listeBillet) > 0)
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("liste" => $listeBillet));
		}
		else
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:index.html.twig",array("listeVide" => "Il n'y a pas d'article disponible"));
		}
		
    }
	
	public function postAction($id)
    {
		//rend la liste des billets
		$em = $this->getDoctrine()->getManager(); 
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);   
		
		if (count($billet) > 0)
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("billet" => $billet));
		}
		else
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:post.html.twig",array("listeVide" => "Il n'y a pas d'article disponible"));
		}
    }

    public function adminAction()
    {
        //rend la liste des billets
		$em = $this->getDoctrine()->getManager(); 
		$listeBillet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findAll();   
		
		if (count($listeBillet) > 0)
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:admin.html.twig",array("liste" => $listeBillet));
		}
		else
		{
			return $this->render("AngryProgrammersBlogBundle:Blog:admin.html.twig",array("listeVide" => "Il n'y a pas d'article disponible"));
		}
    }
	
	//se reporter à "http://localhost:8000/admin/ajoutBillet" (droit réservé à l'admin uniquement)
	public function ajoutBilletAction(Request $request)
	{
		$billet = new Billet();

		//instanciation de l'objet formulaire dans lequel on ajoute le bouton submit d'ajout du billet
		$form = $this->createForm(new BilletType(), $billet)
			->add('Ajouter le billet', 'submit');
		
		

		if ($form->handleRequest($request)->isValid()) {
			//obtenir l'utilisateur courant
			$billet->setAuteur($this->getUser());
			//obtenir la date courante			
			$billet->setDate(new \Datetime());
			//création du slug (il faudra gérer les possibles doublons)
			$billet->setSlug(str_replace(' ', '_', $billet->getTitre()));
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($billet);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Billet bien enregistré.');

			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}

		return $this->render('AngryProgrammersBlogBundle:Blog:ajoutBillet.html.twig', array('form' => $form->createView()));
	}
	
	//se reporter à "http://localhost:8000/admin/modifierBillet" (droit réservé à l'admin uniquement)
	public function modifierBilletAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager(); 
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
			$billet->setSlug(str_replace(' ', '_', $billet->getTitre()));
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($billet);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Billet bien modifié.');

			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}

		return $this->render('AngryProgrammersBlogBundle:Blog:modifierBillet.html.twig', array('form' => $form->createView()));
	}
	
	//se reporter à "http://localhost:8000/admin/supprimerBillet" (droit réservé à l'admin uniquement)
	public function supprimerBilletAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager(); 
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);   
			
		$em = $this->getDoctrine()->getManager();
		$em->remove($billet);
		$em->flush();

		$request->getSession()->getFlashBag()->add('notice', 'Billet bien supprimé.');

		return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
	}
}

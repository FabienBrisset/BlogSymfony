<?php

namespace AngryProgrammers\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AngryProgrammers\BlogBundle\Entity\Billet;
use AngryProgrammers\BlogBundle\Entity\LikeBillet;
use AngryProgrammers\BlogBundle\Form\BilletType;
use Symfony\Component\HttpFoundation\Request;

class CrudController extends Controller
{
	//se reporter à "/admin/ajoutBillet" (droit réservé à l'admin uniquement)
	public function newAction(Request $request)
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

			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}

		return $this->render('AngryProgrammersBlogBundle:Crud:new.html.twig', array('form' => $form->createView(), "user" => $user));
	}
	
	//se reporter à "/admin/modifierBillet" (droit réservé à l'admin uniquement)
	public function editAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager(); 
		$user = $this->getUser();
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);  
		
		if ($billet != NULL) {
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

				return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
			}

			return $this->render('AngryProgrammersBlogBundle:Crud:edit.html.twig', array('form' => $form->createView(), "user" => $user));
		}
		else {
			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}
	}
	
	//se reporter à "/admin/supprimerBillet" (droit réservé à l'admin uniquement)
	public function deleteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager(); 
		$billet = $em->getRepository("AngryProgrammersBlogBundle:Billet")->findOneById($id);   
		
		if ($billet != NULL) {
			$likes = $em->getRepository("AngryProgrammersBlogBundle:LikeBillet")->findByBillet($id);   
			
			for ($i = 0; $i < sizeOf($likes); $i++) {
				$em->remove($likes[$i]);
			}
				
			$em->remove($billet);
			$em->flush();

			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}
		else {
			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}
	}
}

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
			//création du slug
			$billet->setSlug(str_replace(' ', '_', $billet->getTitre()));
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($billet);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Billet bien enregistré.');

			return $this->redirect($this->generateUrl('angry_programmers_blog_admin'));
		}

		return $this->render('AngryProgrammersBlogBundle:Blog:ajoutBillet.html.twig', array('form' => $form->createView()));
	}
}

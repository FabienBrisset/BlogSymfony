<?php

namespace AngryProgrammers\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;

class BilletType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', 'text')
            ->add('contenu', 'textarea')
            ->add('image', 'file', array('required' => false)); 
		
		$builder->addEventListener(FormEvents::POST_SUBMIT, 
			function ($event) {
				$form = $event->getForm();
				$image = $form['image']->getData();
				
				//exit(dump($image));
				if (null === $image) {
					return;
				}
				
				$nomImage = $image->getClientOriginalName();
				
				if (!preg_match("([^\s]+(\.(?i)(jpg|png|gif|bmp))$)",$nomImage,$matches)) {
					$form['image']->addError(new FormError("Ceci n'est pas une image"));
				}
			});
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AngryProgrammers\BlogBundle\Entity\Billet'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'angryprogrammers_blogbundle_billet';
    }
}

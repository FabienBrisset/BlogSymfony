<?php

namespace AngryProgrammers\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Billet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AngryProgrammers\BlogBundle\Entity\BilletRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Billet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=10000)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="AngryProgrammers\BlogBundle\Entity\User", cascade={"persist"})
     */
    private $auteur;

	/*** UPLOAD IMAGE : Choix d'automatisation des taches en fonction d'évènements ****/
	
	// attribut image pour uploader l'image.
	private $image;
	
	// On ajoute cet attribut pour y stocker le nom de la photo temporairement
	private $tempPhoto;
	
	public function getImage()
	{
		return $this->image;
	}

	public function setImage(UploadedFile $image)
	{
		if ($this->photo !== null)
		{
			$this->tempPhoto = $this->photo;
		}
		
		$this->image = $image;
		
		$this->photo = null;
		
	}
	
	public function getUploadDir()
	{
		// On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
		return 'uploads/img';
	}

	protected function getUploadRootDir()
	{
		// On retourne le chemin relatif vers l'image pour notre code PHP
		return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}
	
	/**
	* @ORM\PrePersist()
	* @ORM\PreUpdate()
	*/
	public function preUpload()
	{
		// Si jamais il n'y a pas d'image (champ facultatif)
		if ($this->image === null) {
			return;
		}

			
		
		// initialisation attribut photo avec le nom du fichier
		$this->photo = $this->image->getClientOriginalName();
	}

	/**
	* @ORM\PostPersist()
	* @ORM\PostUpdate()
	*/
	public function upload()
	{	
		// Si jamais il n'y a pas d'image (champ facultatif)
		if ($this->image === null) {
			return;
		}

		// Si on avait un ancien fichier, on le supprime
		if (null !== $this->tempPhoto) {
			$oldImage = $this->getUploadRootDir().'/'.$this->id.'_'.$this->tempPhoto;
			if (file_exists($oldImage)) {
				unlink($oldImage);
			}
		}
		

		// On déplace le fichier envoyé dans le répertoire de notre choix
		$this->image->move($this->getUploadRootDir(), $this->id.'_'.$this->photo);
	}

	/**
	* @ORM\PreRemove()
	*/
	public function preRemoveUpload()
	{
		// On sauvegarde temporairement le nom du fichier, car il dépend de l'id
		$this->tempPhoto = $this->getUploadRootDir().'/'.$this->id.'_'.$this->photo;
	}

	/**
	* @ORM\PostRemove()
	*/
	public function removeUpload()
	{
		// En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
		if (file_exists($this->tempPhoto)) {
			// On supprime le fichier
			unlink($this->tempPhoto);
		}
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Billet
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Billet
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Billet
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Billet
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Billet
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set auteur
     *
     * @param \AngryProgrammers\BlogBundle\Entity\User $auteur
     *
     * @return Billet
     */
    public function setAuteur(\AngryProgrammers\BlogBundle\Entity\User $auteur = null)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return \AngryProgrammers\BlogBundle\Entity\User
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
}

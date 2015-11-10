<?php

namespace AngryProgrammers\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LikeBillet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AngryProgrammers\BlogBundle\Entity\LikeBilletRepository")
 */
class LikeBillet
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
     * @ORM\ManyToOne(targetEntity="AngryProgrammers\BlogBundle\Entity\User", cascade={"persist"})
     */
	private $auteur;
	
	/**
     * @ORM\ManyToOne(targetEntity="AngryProgrammers\BlogBundle\Entity\Billet", cascade={"persist"})
     */
	private $billet;

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
	* Get auteur
	*
	* @return User
	*/
	public function getAuteur()
	{
		return $this->auteur;
	}
	
	/**
	* Get billet
	*
	* @return Billet
	*/
	public function getBillet()
	{
		return $this->billet;
	}
	
	/**
	* Set auteur
	*
	* @return User
	*/
	public function setAuteur($auteur)
	{
		$this->auteur = $auteur;
	}
	
	/**
	* Set billet
	*
	* @return Billet
	*/
	public function setBillet($billet)
	{
		$this->billet = $billet;
	}
}


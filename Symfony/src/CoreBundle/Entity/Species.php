<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Species
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\SpeciesRepository")
 */
class Species
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ordre", type="string", length=32)
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="famille", type="string", length=32)
     */
    private $famille;

    /**
     * @var int
     *
     * @ORM\Column(name="cd_nom", type="integer")
     */
    private $cdNom;

    /**
     * @var string
     *
     * @ORM\Column(name="lb_nom", type="string", length=255)
     */
    private $lbNom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_vern", type="string", length=255, nullable=true)
     */
    private $nomVern;

    /**
     * @var int
     *
     * @ORM\Column(name="habitat", type="smallint", nullable=true)
     */
    private $habitat;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=1, nullable=true)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=64, nullable=true)
     */
    private $url;

    private $status;


    public function __construct()
    {
        $this->status = $this->setStatus($this->statut);
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ordre
     *
     * @param string $ordre
     *
     * @return Species
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set famille
     *
     * @param string $famille
     *
     * @return Species
     */
    public function setFamille($famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return string
     */
    public function getFamille()
    {
        return $this->famille;
    }

    /**
     * Set cdNom
     *
     * @param integer $cdNom
     *
     * @return Species
     */
    public function setCdNom($cdNom)
    {
        $this->cdNom = $cdNom;

        return $this;
    }

    /**
     * Get cdNom
     *
     * @return int
     */
    public function getCdNom()
    {
        return $this->cdNom;
    }

    /**
     * Set lbNom
     *
     * @param string $lbNom
     *
     * @return Species
     */
    public function setLbNom($lbNom)
    {
        $this->lbNom = $lbNom;

        return $this;
    }

    /**
     * Get lbNom
     *
     * @return string
     */
    public function getLbNom()
    {
        return $this->lbNom;
    }

    /**
     * Set nomVern
     *
     * @param string $nomVern
     *
     * @return Species
     */
    public function setNomVern($nomVern)
    {
        $this->nomVern = $nomVern;

        return $this;
    }

    /**
     * Get nomVern
     *
     * @return string
     */
    public function getNomVern()
    {
        return $this->nomVern;
    }

    /**
     * Set habitat
     *
     * @param integer $habitat
     *
     * @return Species
     */
    public function setHabitat($habitat)
    {
        $this->habitat = $habitat;

        return $this;
    }

    /**
     * Get habitat
     *
     * @return int
     */
    public function getHabitat()
    {
        return $this->habitat;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Species
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Species
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Species
     */
    public function setStatus($statut)
    {
        switch ($statut)
        {
            case "P":
                $this->status = "présente";
                break;
            case "B":
                $this->status = "accidentelle";
                break;
            case "W":
                $this->status = "disparue";

        }


        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {

        return $this->status;
    }

    public function __toString()
    {
        return $this->nomVern;
    }

}


<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Observation
 *
 * @ORM\Table(name="observation")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ObservationRepository")
 */
class Observation
{
    const STATUS_UNTREATED = 'untreated';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=64, nullable=false, columnDefinition="ENUM('untreated', 'accepted', 'rejected')", options={"default":"untreated"})
     */
    private $statut;


    /**
     *
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Species", cascade={"persist"})
     * @ORM\JoinColumn(name="species_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    private $bird;



    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Type("string")
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="CoreBundle\Entity\Picture", cascade={"persist", "remove"})
     * @assert\valid()
     */
    private $picture;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Assert\NotBlank()
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Assert\NotBlank()
     */
    private $longitude;





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
     * Set user
     *
     * @param integer $user
     *
     * @return Observation
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Observation
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
     * Set statut
     *
     * @param string $statut
     *
     * @return Observation
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
     * Set bird
     *
     * @param string $bird
     *
     * @return Observation
     */
    public function setBird($bird)
    {
        $this->bird = $bird;

        return $this;
    }

    /**
     * Get bird
     *
     * @return string
     */
    public function getBird()
    {
        return $this->bird;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Observation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    public function setPicture(Picture $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }


    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }



}


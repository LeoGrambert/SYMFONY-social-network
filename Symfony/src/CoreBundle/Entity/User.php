<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebookID;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $googleID;

    /**
     * @ORM\Column(name="is_accredit", type="boolean")
     */
    protected $isAccredit;

    public function __construct()
    {
        parent::__construct();
        $this->isAccredit = false;
    }

    /**
     * Set facebookID
     *
     * @param string $facebookID
     *
     * @return User
     */
    public function setFacebookID($facebookID)
    {
        $this->facebookID = $facebookID;

        return $this;
    }

    /**
     * Get facebookID
     *
     * @return string
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * Set googleID
     *
     * @param string $googleID
     *
     * @return User
     */
    public function setGoogleID($googleID)
    {
        $this->googleID = $googleID;

        return $this;
    }

    /**
     * Get googleID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }

    /**
     * Set isAccredit
     *
     * @param boolean $isAccredit
     *
     * @return User
     */
    public function setIsAccredit($isAccredit)
    {
        $this->isAccredit = $isAccredit;

        return $this;
    }

    /**
     * Get isAccredit
     *
     * @return boolean
     */
    public function getIsAccredit()
    {
        return $this->isAccredit;
    }
}
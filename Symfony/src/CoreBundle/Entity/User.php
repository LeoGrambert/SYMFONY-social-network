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

    /**
     * @ORM\Column(name="xp", type="integer")
     */
    protected $xp;

    public function __construct()
    {
        parent::__construct();
        $this->isAccredit = false;
        $this->xp = 0;
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

    /**
     * Method to get gravatar profile picture
     * @return null|string
     */
    public function getGravatarPicture()
    {
        if($this->email != null){
            $email = md5($this->getEmail());
            $email = strtolower($email);
            $defaultPicture = urlencode('https://leogrambert.fr/front/projets/blogEcrivain/blog/web/img/user.png');
            //todo Change url adress when website will be online + Do the same in search.js
            $gravatar = 'https://www.gravatar.com/avatar/'.$email.'?default='.$defaultPicture;
        } else {
            $gravatar = null;
        }
        return $gravatar;
    }

    /**
     * Set xp
     *
     * @param integer $xp
     *
     * @return User
     */
    public function setXp($xp)
    {
        $this->xp = $xp;

        return $this;
    }

    /**
     * Get xp
     *
     * @return integer
     */
    public function getXp()
    {
        return $this->xp;
    }
}

<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class BackController extends Controller
{
    /**
     * What do we do if we are on admin page
     * @Route("/admin", name="adminPage")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if(null === $user){
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Admin:index.html.twig');
        }
    }

    /**
     * What do we do if we are on admin profile page
     * @Route("/admin/profile", name="adminProfilePage")
     */
    public function profileAction()
    {
        $user = $this->getUser();

        if(null === $user){
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Admin:profile.html.twig');
        }
    }

    /**
     * What do we do if we are on admin validate an observation page
     * @Route("/admin/validate/observations", name="adminValidateObservationsPage")
     * @Security("has_role('ROLE_PRO')")
     */
    public function validateObservationsAction()
    {
        $user = $this->getUser();

        if(null === $user){
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Admin:validateObservations.html.twig');
        }
    }

    /**
     * What do we do if we are on admin validate an account page
     * @Route("/admin/validate/account", name="adminValidateAccountPage")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function validateAccountAction()
    {
        $user = $this->getUser();

        if(null === $user){
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Admin:validateAccount.html.twig');
        }
    }
}

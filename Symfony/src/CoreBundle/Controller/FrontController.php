<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FrontController
 * @package CoreBundle\Controller
 */
class FrontController extends Controller
{
    /**
     * What do we do if we are on home page
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('CoreBundle:Front:index.html.twig');
    }

    /**
     * What do we do if we are on add an observation page
     * @route("/add", name="addPage")
     */
    public function addAction()
    {
        return $this->render('CoreBundle:Front:add.html.twig');
    }

    /**
     * What do we do if we are on map page
     * @route("/map", name="mapPage")
     */
    public function mapAction()
    {
        return $this->render('CoreBundle:Front:map.html.twig');
    }

    /**
     * What do we do if we are on legal notice page
     * @route("/legal", name="legalPage")
     */
    public function legalAction()
    {
        return $this->render('CoreBundle:Front:legal.html.twig');
    }

    /**
     * What do we do if we are on association page
     * @route("/association", name="associationPage")
     */
    public function associationAction()
    {
        return $this->render('CoreBundle:Front:association.html.twig');
    }

    /**
     * What do we do if we are on login page
     * @route("/login", name="loginPage")
     */
    public function loginAction(Request $request)
    {
        //If user is already logged, he's redirected on adminPage
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('adminPage');
        }

        //We use this to get errors
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('CoreBundle:Front:login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }
}

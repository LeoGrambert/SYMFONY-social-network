<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\Observation;
use CoreBundle\Entity\Species;
use CoreBundle\Entity\User;
use CoreBundle\Form\UserType;
use CoreBundle\Form\ObservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


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
    public function addAction(Request $request)
    {
        $observation = new Observation();

        $observation->setUser($this->getUser());



        $observation->setStatut('En_attente_de_validation');

        $form = $this->createForm(ObservationType::class, $observation, ['method'=>'PUT']);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getRepository('CoreBundle:Observation')->add($observation);

            $this->addFlash('info', 'Votre observation a été enregistrée, elle est en attente de validation.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('CoreBundle:Front:add.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * What do we do if we are on search page
     * @route("/search", name="searchPage")
     */
    public function searchAction()
    {
        return $this->render('CoreBundle:Front:search.html.twig');
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
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

}

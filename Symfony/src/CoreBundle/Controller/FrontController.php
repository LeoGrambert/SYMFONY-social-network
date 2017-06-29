<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\Observation;
use CoreBundle\Entity\User;
use CoreBundle\Form\UserType;
use CoreBundle\Form\ObservationType;
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
    public function addAction(Request $request)
    {
        $observation = new Observation();

        $em = $this->getDoctrine()->getManager();

        $speciesRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CoreBundle:Species')
        ;


        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $observation->setUser($user);

            $observation->setDate($observation->getDate() );

            $observation->setStatut("en attente");

            /*$observation->setBird($form->get('bird')->getData());*/

            $getBird = $form->get('bird')->getData();

            var_dump($getBird);

            $bird = $speciesRepository ->findBy(array('lbNom'=> $getBird));

            $bird1 = $speciesRepository ->find(2);

            var_dump($bird1);

            $observation->setBird($bird1);

            $observation->setDescription($form->get('description')->getData());

            $observation->setImage("url image");

            $observation->setLatitude($form->get('latitude')->getData());

            $observation->setLongitude($form->get('longitude')->getData());




            $em->persist($observation);
            $em->flush();

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

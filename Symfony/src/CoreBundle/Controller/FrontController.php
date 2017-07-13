<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\Observation;
use CoreBundle\Entity\Species;
use CoreBundle\Entity\User;
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
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $em = $this->getDoctrine()->getManager()->getRepository('CoreBundle:Observation');
        $lastObservations = $em->findLastObservations();
        return $this->render('CoreBundle:Front:index.html.twig',[
            'Observations'=>$lastObservations,
            'gravatar'=>$gravatar
        ]);
    }


    /**
     * What do we do if we are on add an observation page
     * @route("/add", name="addPage")
     */
    public function addAction(Request $request)
    {

        $user = $this->getUser();

        if($user){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $observation = new Observation();

        $observation->setUser($user);

        // Grant status of the observation  according to ROLE
        if ($this->isGranted('ROLE_ADMIN') || ($this->isGranted('ROLE_PRO') && $user->getIsAccredit())) {
            $observation->setStatut('accepted');
        } else {
            $observation->setStatut('untreated');
        }

        $form = $this->createForm(ObservationType::class, $observation, ['method'=>'PUT']);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){


            $this->getDoctrine()->getRepository('CoreBundle:Observation')->add($observation);

            if ($observation->getStatut() === 'accepted'){
                $this->addFlash('info', 'Votre observation a été enregistrée.');
            } elseif ($observation->getStatut() === 'untreated'){
                $this->addFlash('info', 'Votre observation a été enregistrée, elle est en attente de validation.');
            }

            return $this->redirectToRoute('homepage');
        }

        if(null === $user) {
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Front:add.html.twig', array(
                'form' => $form->createView(),
                'gravatar' => $gravatar
            ));
        }

    }

    /**
     * What do we do if we are on search page
     * @route("/search", name="searchPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $em = $this->getDoctrine()->getManager();
        $ordres = $em->getRepository('CoreBundle:Species')->getOrdre();
        $familles = $em->getRepository('CoreBundle:Species')->getFamille();
        $birds = $em->getRepository('CoreBundle:Species')->getBirds();

        return $this->render('CoreBundle:Front:search.html.twig', [
            'gravatar'=>$gravatar,
            'ordres'=>$ordres,
            'familles'=>$familles,
            'birds'=>$birds
        ]);
    }

    /**
     * What do we do if we are on legal notice page
     * @route("/legal", name="legalPage")
     */
    public function legalAction()
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }
        return $this->render('CoreBundle:Front:legal.html.twig', [
            'gravatar'=>$gravatar
        ]);
    }

    /**
     * What do we do if we are on how to do page
     * @route("/how", name="howPage")
     */
    public function howAction()
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }
        return $this->render('CoreBundle:Front:how.html.twig', [
            'gravatar'=>$gravatar
        ]);
    }

    /**
     * What do we do if we are on association page
     * @route("/association", name="associationPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function associationAction(Request $request)
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $form = $this->createForm('CoreBundle\Form\ContactType',null,[
            'action' =>$this->generateUrl('associationPage'),
            'method'=>'POST'
        ]);

        if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isValid()){
                $data = $form->getData();
                $message = \Swift_Message::newInstance()
                    ->setSubject('Nouveau message de '.$data['firstname'].' '.$data['lastname'].' ('.$data['email'].') sur l\'application NAO')
                    ->setFrom($data['email'])
                    ->setTo('leogrambert@gmail.com')
                    ->setBody($form->getData()['message'],
                        'text/plain'
                    );
                $this->get('mailer')->send($message);
                $this->addFlash(
                    'success',
                    'Votre message a bien été envoyé !'
                );
            }
        }

        return $this->render('CoreBundle:Front:association.html.twig', [
            'form'=>$form->createView(),
            'gravatar'=>$gravatar
        ]);
    }

    /**
     * What do we do if we are on login page
     * @route("/login", name="loginPage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        if($this->getUser()){
            $gravatar = $this->getUser()->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        //If user is already logged, he's redirected on adminPage
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('adminPage');
        }

        //We use this to get errors
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('CoreBundle:Front:login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'gravatar'=>$gravatar
        ]);
    }

    /**
     * What do we do if we want to search coordinates in map.
     * @param Request $request
     * @route("/search/gps/{lat}/{lon}", methods={"GET"}, name="SearchGpsCoordinates")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function searchGpsCoordinates(Request $request){
        $lat = $request->get('lat');
        $lon = $request->get('lon');
        $this->addFlash('success_lat', $lat);
        $this->addFlash('success_lon', $lon);
        return $this->redirectToRoute('searchPage');
    }

}

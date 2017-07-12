<?php

namespace CoreBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Repository\ObservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BackController extends Controller
{
    /**
     * What do we do if we are on admin page
     * @Route("/admin", name="adminPage")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        if($user){
            $gravatar = $user->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $em = $this->getDoctrine()->getManager();

        $listObservations = $em
            ->getRepository('CoreBundle:Observation')
            ->findByIdUserWithSpecies($user->getId());

        $observationsToValidate = $em
            ->getRepository('CoreBundle:Observation')
            ->findObservationsToValidate();
        $howManyObservationToValidate = count($observationsToValidate);

        if(null === $user){
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Admin:index.html.twig', [
                'Observations'=>$listObservations,
                'gravatar'=>$gravatar,
                'howManyObservationToValidate'=>$howManyObservationToValidate
            ]);
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
        if($user){
            $gravatar = $user->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $em = $this->getDoctrine()->getManager();
        $observationsToValidate = $em->getRepository('CoreBundle:Observation')->findObservationsToValidate();
        $birds = $em->getRepository('CoreBundle:Species')->getBirds();

        if(null === $user){
            return $this->redirectToRoute('login');
        } elseif($user->getIsAccredit() === false){
            return $this->redirectToRoute('adminPage');
        } else {
            return $this->render('CoreBundle:Admin:validateObservations.html.twig', [
                'gravatar'=>$gravatar,
                'observationsToValidate'=>$observationsToValidate,
                'birds'=>$birds
            ]);
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
        if($user){
            $gravatar = $user->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $listUsers = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('CoreBundle:User')
            ->findUsersNotAccredit();

        if(null === $user){
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Admin:validateAccount.html.twig', [
                'listUsers'=>$listUsers,
                'gravatar'=>$gravatar
            ]);
        }
    }

    /**
     * What do we do if we want to confirm a professional user
     * @param $userId
     * @Route("/admin/validate/account/confirm/{userId}", methods={"POST", "GET"}, requirements={"userId" = "\d+"}, name="confirmAccount")
     * @Security("has_role('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmProUser($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('CoreBundle:User')->find($userId);

        if(!$user){
            throw $this->createNotFoundException(
                'L\'utilisateur d\'id '.$userId.' n\'a pas été trouvé.'
            );
        }

        $user->setIsAccredit(true);
        $em->flush();
        $this->addFlash(
            'info',
            'Le compte professionnel de '.$user->getUsername().' a été confirmé.'
        );

        return $this->redirectToRoute('adminValidateAccountPage');
    }

    /**
     * What do we do if we want to refuse a professional user
     * @param $userId
     * @Route("/admin/validate/account/refuse/{userId}", methods={"POST", "GET"}, requirements={"userId" = "\d+"}, name="refuseAccount")
     * @Security("has_role('ROLE_ADMIN')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseProUser($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('CoreBundle:User')->find($userId);

        if(!$user){
            throw $this->createNotFoundException(
                'L\'utilisateur d\'id '.$userId.' n\'a pas été trouvé.'
            );
        }

        $user->setRoles(['ROLE_USER']);
        $em->flush();
        $this->addFlash(
            'warning',
            'Le compte de '.$user->getUsername().' devient un compte amateur.'
        );

        return $this->redirectToRoute('adminValidateAccountPage');
    }

    /**
     * What do we do if we want to confirm an observation
     * @param $observationId
     * @Route("/admin/validate/observations/confirm/{observationId}", methods={"POST", "GET"}, requirements={"observationId" = "\d+"}, name="confirmObservation")
     * @Security("has_role('ROLE_PRO')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmObservation($observationId)
    {
        $em = $this->getDoctrine()->getManager();
        $observation = $em->getRepository('CoreBundle:Observation')->find($observationId);

        if(!$observation){
            throw $this->createNotFoundException(
                'L\'observation d\'id '.$observationId.' n\'a pas été trouvée.'
            );
        }

        $observation->setStatut("accepted");
        $em->flush();
        $this->addFlash(
            'info',
            'L\'observation a été validée et publiée.'
        );

        return $this->redirectToRoute('adminValidateObservationsPage');
    }

    /**
     * What do we do if we want to refuse an observation
     * @param $observationId
     * @Route("/admin/validate/account/observations/{observationId}", methods={"POST", "GET"}, requirements={"observationId" = "\d+"}, name="refuseObservation")
     * @Security("has_role('ROLE_PRO')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseObservation($observationId)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$observationId){
            throw $this->createNotFoundException(
                'L\'observation d\'id '.$observationId.' n\'a pas été trouvée.'
            );
        }

        $em->getRepository('CoreBundle:Observation')->deleteAnObservation($observationId);

        $this->addFlash(
            'warning',
            'L\'observation a été supprimée et ne sera pas publiée.'
        );

        return $this->redirectToRoute('adminValidateObservationsPage');
    }
}

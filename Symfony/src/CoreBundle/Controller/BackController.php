<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Repository\ObservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

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
            ->howManyObservationsToValidate();
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
     * @param Request $request
     * @Route("/admin/validate/observations/{page}", requirements={"page" = "\d+"}, name="adminValidateObservationsPage")
     * @Security("has_role('ROLE_PRO')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function validateObservationsAction(Request $request)
    {
        $user = $this->getUser();
        if($user){
            $gravatar = $user->getGravatarPicture();
        } else {
            $gravatar = null;
        }

        $page = intval($request->get('page'));

        $em = $this->getDoctrine()->getManager();
        $observationsToValidate = $em->getRepository('CoreBundle:Observation')->findObservationsToValidate($page);
        $howManyObservationsToValidate = $em->getRepository('CoreBundle:Observation')->howManyObservationsToValidate();
        $birds = $em->getRepository('CoreBundle:Species')->getBirds();

        $nbObservationsToValidate = count($howManyObservationsToValidate);
        $perPage = 10;
        $nbPagesFloat = $nbObservationsToValidate / $perPage;
        $nbPages = ceil($nbPagesFloat);

        if(null === $user){
            return $this->redirectToRoute('login');
        } elseif($user->getIsAccredit() === false){
            return $this->redirectToRoute('adminPage');
        } else {
            return $this->render('CoreBundle:Admin:validateObservations.html.twig', [
                'gravatar'=>$gravatar,
                'observationsToValidate'=>$observationsToValidate,
                'birds'=>$birds,
                'nbPages'=>$nbPages
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
     * @param Request $request
     * @Route("/admin/validate/observations/{page}/confirm/{observationId}", methods={"POST", "GET"}, requirements={"observationId" = "\d+"}, name="confirmObservation")
     * @Security("has_role('ROLE_PRO')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmObservation(Request $request, $observationId)
    {
        $em = $this->getDoctrine()->getManager();
        $observation = $em->getRepository('CoreBundle:Observation')->find($observationId);

        if(!$observation){
            throw $this->createNotFoundException(
                'L\'observation d\'id '.$observationId.' n\'a pas été trouvée.'
            );
        }

        $currentPage = $request->get('page');

        $observation->setStatut("accepted");
        $em->flush();
        $this->addFlash(
            'info',
            'L\'observation a été validée et publiée.'
        );

        return $this->redirectToRoute('adminValidateObservationsPage', ['page' => $currentPage]);
    }

    /**
     * What do we do if we want to refuse an observation
     * @param $observationId
     * @param Request $request
     * @Route("/admin/validate/observations/{page}/refuse/{observationId}", methods={"POST", "GET"}, requirements={"observationId" = "\d+"}, name="refuseObservation")
     * @Security("has_role('ROLE_PRO')")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function refuseObservation(Request $request, $observationId)
    {
        $em = $this->getDoctrine()->getManager();
        if(!$observationId){
            throw $this->createNotFoundException(
                'L\'observation d\'id '.$observationId.' n\'a pas été trouvée.'
            );
        }

        $currentPage = $request->get('page');

        $em->getRepository('CoreBundle:Observation')->deleteAnObservation($observationId);

        $this->addFlash(
            'warning',
            'L\'observation a été supprimée et ne sera pas publiée.'
        );

        return $this->redirectToRoute('adminValidateObservationsPage', ['page'=>$currentPage]);
    }
}

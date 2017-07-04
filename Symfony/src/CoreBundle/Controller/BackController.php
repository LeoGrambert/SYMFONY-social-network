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

        $listObservations = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('CoreBundle:Observation')
            ->findByIdUserWithSpecies($user->getId());

        if(null === $user){
            return $this->redirectToRoute('login');
        } else {
            return $this->render('CoreBundle:Admin:index.html.twig', [
                'listObservations'=>$listObservations,
                'gravatar'=>$gravatar
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

        if(null === $user){
            return $this->redirectToRoute('login');
        } elseif($user->getIsAccredit() === false){
            return $this->redirectToRoute('adminPage');
        } else {
            return $this->render('CoreBundle:Admin:validateObservations.html.twig', [
                'gravatar'=>$gravatar
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
}

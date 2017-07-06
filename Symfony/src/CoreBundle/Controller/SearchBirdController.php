<?php

namespace CoreBundle\Controller;

use Buzz\Message\Response;
use CoreBundle\Entity\Observation;
use CoreBundle\Entity\Species;
use CoreBundle\Entity\User;
use CoreBundle\Form\ObservationType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



/**
* Class SearchBirdController
* @package CoreBundle\Controller
*/
class SearchBirdController extends Controller
{

    /**
     * @Route("/search-bird", name="search_bird", defaults={"_format"="json"})
     * @Method("GET")
     */
    public function searchBirdAction(Request $request)
    {
        $q = $request->query->get('q', $request->query->get('term', ''));
        $birds = $this->getDoctrine()->getRepository('CoreBundle:Species')->findLike($q);
        return $this->render('CoreBundle:Front:searchbird.json.twig', ['birds' => $birds]);
    }

    /**
     * @Route("/get-bird/{id}", name="get_bird", defaults={"_format"="json"})
     * @Method("GET")
     */
    public function getBirdAction($id = null)
    {
        if (is_null($bird = $this->getDoctrine()->getRepository('CoreBundle:Species')->find($id))) ;
        {
            throw $this->createNotFoundException();
        }

        return $this->json($bird->getNomVern());

    }

    /**
     * @param Species $species
     * @return \Symfony\Component\HttpFoundation\Response $response
     * What do we do if we want to search a bird by knowing it family
     * @Route("/search/family/{family}", methods={"GET"}, name="searchWithFamily")
     * @ParamConverter("species", options={"mapping": {"family": "famille"}})
     */
    public function searchWithFamily(Species $species)
    {
        $em = $this->getDoctrine()->getManager();
        $birds = $em->getRepository('CoreBundle:Species')->getBirdsByFamily($species->getFamille());
        if ($birds == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['birds'=>$birds], 200);
        }
        return $response;
    }

    /**
     * @param Species $species
     * @return \Symfony\Component\HttpFoundation\Response $response
     * What do we do if we want to search a family by knowing it order
     * @Route("/search/order/{order}", methods={"GET"}, name="searchWithOrder")
     * @ParamConverter("species", options={"mapping": {"order": "ordre"}})
     */
    public function searchWithOrder(Species $species)
    {
        $em = $this->getDoctrine()->getManager();
        $families = $em->getRepository('CoreBundle:Species')->getFamilyByOrder($species->getOrdre());
        if ($families == null){
            $response = new JsonResponse([], 422);
        }else{
            $response = new JsonResponse(['families'=>$families], 200);
        }
        return $response;
    }
}
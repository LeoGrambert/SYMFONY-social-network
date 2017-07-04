<?php

namespace CoreBundle\Controller;

use Buzz\Message\Response;
use CoreBundle\Entity\Observation;
use CoreBundle\Entity\Species;
use CoreBundle\Entity\User;
use CoreBundle\Form\ObservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * What do we do if we want to search a bird by knowing his family
     * @Route("/search/{family}", methods={"POST", "GET"}, name="searchWithFamily")
     * @ParamConverter("species", options={"repository_method"="getBirdsByFamily"})
     */
    public function searchWithFamily(Species $species)
    {
        $em = $this->getDoctrine()->getManager();
        $birds = $em->getRepository('CoreBundle:Species')->getBirdsByFamily();
        dump($birds);
    }

    /**
     * What do we do if we want to search a bird by knowing his order
     * @param $species
     * @Route("/search/{order}", methods={"GET"}, name="searchWithOrder")
     * @ParamConverter("species", class="CoreBundle:FrontController")
     */
    public function searchWithOrder(Species $species)
    {

    }



}
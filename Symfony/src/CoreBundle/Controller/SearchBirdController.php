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

}
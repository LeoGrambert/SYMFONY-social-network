<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//todo : rename this controller. For now, it's only a test
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('CoreBundle:Default:index.html.twig');
    }

    /**
     * @route("/test", name="testPage")
     */
    public function testAction()
    {
        return $this->render('CoreBundle:Default:test.html.twig');
    }
}

<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function addAction()
    {
        return $this->render('CoreBundle:Front:add.html.twig');
    }

    /**
     * What do we do if we are on map page
     * @route("/map", name="mapPage")
     */
    public function mapAction()
    {
        return $this->render('CoreBundle:Front:map.html.twig');
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
     * What do we do if we are on contact page
     * @route("/contact", name="contactPage")
     */
    public function contactAction()
    {
        return $this->render('CoreBundle:Front:contact.html.twig');
    }

    /**
     * What do we do if we are on connection page
     * @route("/connection", name="connectionPage")
     */
    public function connectionAction()
    {
        return $this->render('CoreBundle:Front:connection.html.twig');
    }
}

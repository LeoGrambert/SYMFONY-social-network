<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BackController extends Controller
{
    /**
     * What do we do if we are on admin page
     * @Route("/admin", name="adminPage")
     */
    public function indexAction()
    {
        return $this->render('CoreBundle:Admin:index.html.twig');
    }
}

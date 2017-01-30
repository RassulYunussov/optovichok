<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Core\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home_page")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('AppBundle::index.html.twig');
    }
}
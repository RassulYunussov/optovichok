<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class oHomeController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function indexAction()
    {
        return $this->render('AppBundle::index.html.twig');
    }
}
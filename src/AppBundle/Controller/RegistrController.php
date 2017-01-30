<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class RegistrController extends Controller
{
    /**
     * @Route("/registr-page", name="registr_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:oRegistr:registr.html.twig');
    }
}
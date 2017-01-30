<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AuthController extends Controller
{
    /**
     * @Route("/auth-page", name="auth_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:oAuth:auth.html.twig');
    }
}
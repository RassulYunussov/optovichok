<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 04.02.2017
 * Time: 12:27
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/client_page", name="client_page")
     */
    public function clientAction()
    {
        return $this->render('AppBundle:UserPage:client_page.html.twig');
    }

    /**
     * @Route("/company_page", name="company_page")
     */
    public function companyAction()
    {
        return $this->render('AppBundle:UserPage:company_page.html.twig');
    }
}
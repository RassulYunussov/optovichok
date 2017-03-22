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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/users")
 * @Security("has_role('ROLE_CLIENT')")
 */
class oClientController extends Controller
{
    /**
     * @Route("/client_page", name="client_page")
     */
    public function clientAction()
    {
        return $this->render('AppBundle:oClient:client_page.html.twig');
    }
}
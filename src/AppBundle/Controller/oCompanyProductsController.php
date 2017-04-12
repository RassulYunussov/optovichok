<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 10.04.2017
 * Time: 11:05
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class oCompanyProductsController extends Controller
{
    /**
     * @Route("/oproduct/user/{id}", name="")
     * @Method("GET")
     */
    public function userProductsAction($id){
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT p FROM AppBundle:oProduct p WHERE p.user_id = :');
    }
}
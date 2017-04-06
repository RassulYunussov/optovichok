<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 04.04.2017
 * Time: 14:22
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @Route("/search_result", name="search_result")
     * @Method("POST")
     */
    public function searchAction(Request $request){

        $oProduct = new oProduct();
        $form = $this->createForm('AppBundle\Form\oProductType', $oProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $query = $em->createQuery('SELECT p FROM AppBundle:oProduct p WHERE p.header LIKE :header')->setParameter('header', $form);
            $oProducts = $query->getResult();

        }

        return $this->render('AppBundle:oProduct:show1.html.twig', array(
            'oProducts' => $oProduct,
        ));

    }
}
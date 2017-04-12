<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 04.04.2017
 * Time: 14:22
 */

namespace AppBundle\Controller;
use AppBundle\Entity\oProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @Route("/search_result", name="search_result")
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request){

        $product = new oProduct();
        $form = $this->createForm('\AppBundle\Form\SearchType', $product);
        $em = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if ($request->getMethod() == 'POST') {

            $data = $form->getData();
            $query = $em->createQuery('SELECT p FROM AppBundle:oProduct p WHERE p.header LIKE :header')->setParameter('header', $data);
            $product = $query->getResult();

            return $this->redirectToRoute('search_show', array('id' => $product->getId()));
        }

        return $this->render('AppBundle::base.html.twig', array(
            'oProducts' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/search_result/{id}", name="search_show")
     * @Method("GET")
     */
    public function showAction(oProduct $product)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('AppBundle:oProduct:show1.html.twig', array(
            'oProduct' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }
}
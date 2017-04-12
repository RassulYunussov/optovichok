<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 06.04.2017
 * Time: 16:10
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class oProductCategoryController extends Controller
{
    /**
     * @Route("/овощи", name="category_ovowi")
     * @Method("GET")
     */
    public function ovowiAction(){
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:oProductCategory')->find(1);

        $asd = $em->createQuery('SELECT p.id, p.nameCategory FROM AppBundle:oProductCategory p WHERE p.id = 1');
        $oCategory = $asd->getResult();

        $query = $em->createQuery('SELECT p FROM AppBundle:oProduct p WHERE p.category = :category')->setParameter('category', $category);
        $oProducts = $query->getResult();

        return $this->render('AppBundle:Categories:Ovowi.html.twig',array(
            'oProducts' => $oProducts,
            'oCategory' => $oCategory,
        ));

    }

    /**
     * @Route("/фрукты", name="category_fructi")
     * @Method("GET")
     */
    public function fructiAction(){
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:oProductCategory')->find(2);

        $asd = $em->createQuery('SELECT p.id, p.nameCategory FROM AppBundle:oProductCategory p WHERE p.id = 2');
        $oCategory = $asd->getResult();

        $query = $em->createQuery('SELECT p FROM AppBundle:oProduct p WHERE p.category = :category')->setParameter('category', $category);
        $oProducts = $query->getResult();

        return $this->render('AppBundle:Categories:Frukti.html.twig',array(
            'oProducts' => $oProducts,
            'oCategory' => $oCategory,
        ));

    }
}
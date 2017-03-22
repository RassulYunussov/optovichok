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
 * @Security("has_role('ROLE_COMPANY')")
 */
class oCompanyController extends Controller
{
    /**
     * @Route("/company_page", name="company_page")
     */
    public function companyAction()
    {
        return $this->render('AppBundle:oCompany:company_page.html.twig');
    }

    /**
     * @Route("/product", name="product_index")
     * @Method("GET")
     */
    public function productIndex()
    {
        $em = $this->getDoctrine()->getManager();
        $em1 = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userProduct = $em->getRepository('AppBundle:oProduct')->findBy(array('product'=>$user->getUserid()));
        $count = $em1->createQuery('SELECT count(product) from AppBundle:oProduct product where product.userid = :userid')->setParameter('userid',$user->getUserid())->getSingleScalarResult();
        return $this->render('AppBundle:oCompany:user_products.html.twig', array(
            'userProduct'=>$userProduct,
            'count' => $count
        ));
    }
}
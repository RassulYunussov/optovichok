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
     * @Method("GET")
     */
    public function companyAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $id = $em->getRepository('AppBundle:oUser')->find($user);
        $query = $em->createQuery(
            'SELECT p from AppBundle:oUser p WHERE p.id = :id')->setParameter('id', $id);
        $oUsers = $query->getResult();


        return $this->render('AppBundle:oCompany:company_page.html.twig', array(
            'oUsers' => $oUsers,
        ));
    }
}
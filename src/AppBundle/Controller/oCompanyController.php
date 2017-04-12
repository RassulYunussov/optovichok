<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 10.04.2017
 * Time: 11:05
 */

namespace AppBundle\Controller;

use AppBundle\Entity\oUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class oCompanyController extends Controller
{
    /**
     * @Route("/company_page/ads", name="company_page")
     * @Method("GET")
     */
    public function myProductAction()
    {
        $oUser = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $id = $em->getRepository('AppBundle:oUser')->find($oUser);
        $query = $em->createQuery(
            'SELECT p from AppBundle:oProduct p WHERE p.userid = :id')->setParameter('id', $id);
        $security = $query->getResult();


        return $this->render('AppBundle:oCompany:myproduct.html.twig', array(
            'oProducts' => $security
        ));
    }

    /**
     * @Route("/company_page/settings", name="setting_page")
     */
    public function settingsAction(){
        return $this->render('AppBundle:oCompany:company_settings.html.twig');
    }

    /**
     * @Route("/company_page/settings/password", name="change_password")
     * @Method({"GET","POST"})
     */
    public function changepasswordAction(Request $request){

        $oUser = new oUser();
        $changePasswordForm = $this->createFormBuilder($oUser)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class
            ))
            ->getForm();

        $changePasswordForm->handleRequest($request);
        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()){

            $password = $this->get('security.password_encoder')
                ->encodePassword($oUser, $oUser->getPlainPassword());
            $oUser->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $oUser = $changePasswordForm->getData();
            $em->persist($oUser);
            $em->flush();

            return $this->redirectToRoute('company_page');
        }
        return $this->render('AppBundle:oCompany:company_settings.html.twig', array(
            'oUser' => $oUser,
            'change_form' => $changePasswordForm->createView(),
        ));
    }

    public function changeContactAction(){

    }

    public function changeEmailAction(){

    }

    public function deleteUser(){

    }
}
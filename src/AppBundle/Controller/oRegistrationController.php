<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 31.01.2017
 * Time: 20:57
 */

namespace AppBundle\Controller;

use AppBundle\Entity\oUser;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class oRegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        $user = new oUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('AppBundle:registration:Successful.html.twig');
        }

        return $this->render(
            'AppBundle:registration:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\oUser;


/**
 * @Route("/api")
 */
class oUserApiController extends FOSRestController
{
    /**
     * @Rest\Get("/token")
     */
    public function newTokenAction(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');


        //return $user;
    }

    /**
     * @Rest\Post("/login")
     */
    ###### LOGIN ###########
    public function loginUser(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT p FROM AppBundle:oUser p WHERE p.email = :email')->setParameter('email', $email);
        $userEmail = $query->getResult();
        $query2 = $em->createQuery('SELECT p FROM AppBundle:oUser p WHERE p.password = :password')->setParameter('password', $password);
        $userPassword = $query2->getResult();

        if($userEmail && $userPassword)
        {
            return $userEmail;
        }
        return new View("user not found", Response::HTTP_NOT_FOUND);


    }

    /**
     *
     * @Rest\Get("/user")
     */
    public function getUser()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:oUser')->findAll();
        if ($restresult === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/user/{id}")
     */
    public function idUser($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:oUser')->find($id);
        if ($singleresult === null) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/user/")
     */
    public function postUser(Request $request)
    {
        $oUser = new oUser();

        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        $role = $request->get('role');
        $telephone = $request->get('telephone');

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT role FROM AppBundle:oRole role WHERE role.id = :id')->setParameter('id', $role);
        $orole = $query->getSingleResult();

        if(empty($email) || empty($username) || empty($password) || empty($role) || empty($telephone))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $oUser->setEmail($email);
        $oUser->setUsername($username);
        $password = $this->get('security.password_encoder')->encodePassword($oUser, $password);

        $oUser->setPassword($password);
        $oUser->setRole($orole);
        $oUser->setTelephone($telephone);
        $em = $this->getDoctrine()->getManager();
        $em->persist($oUser);
        $em->flush();
        return new View("User Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/user/{id}")
     */
    public function updateUser($id,Request $request)
    {
        $data = new oUser;
        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        $role = $request->get('role');
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:oUser')->find($id);

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT role FROM AppBundle:oRole role WHERE role.code = :code')->setParameter('code', $role);
        $orole = $query->getSingleResult();

        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($email) && !empty($username) && !empty($password) && !empty($role)){
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setRole($orole);
            $sn->flush();
            return new View("User Updated Successfully", Response::HTTP_OK);
        }
        else return new View("User name or role cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteUser($id)
    {
        $data = new oUser();
        $sn = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('AppBundle:oUser')->find($id);
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($user);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}
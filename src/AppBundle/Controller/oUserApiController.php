<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
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
     * @Rest\Post("/login")
     */
    ###### LOGIN ###########
    public function loginUser(Request $request)
    {
        $email = $request->get('email');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT id FROM AppBundle:oUser id WHERE id.email = :email')->setParameter('email', $email);
        $userid = $query->getSingleResult();

        return $userid;
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
        $data = new oUser();

        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        $role = $request->get('role');
        $telephone = $request->get('telephone');
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT id FROM AppBundle:oRole id WHERE id.id = :id')->setParameter('id', $role);
        $orole = $query->getSingleResult();

        if(empty($email) || empty($username) || empty($password) || empty($role) ||empty($telephone))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setEmail($email);
        $data->setUsername($username);
        $data->setPassword($password);
        $data->setRole($orole);
        $data->setTelephone($telephone);
        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
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
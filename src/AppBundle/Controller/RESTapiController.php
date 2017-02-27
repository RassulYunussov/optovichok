<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\oProduct;
use AppBundle\Entity\oRole;
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
class RegisterRESTController extends FOSRestController
{

    ################              REGISTER ########################
    /**
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
        $role = $request->get('code');
        if(empty($email) || empty($username) || empty($password) || empty($role))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setEmail($email);
        $data->setUsername($username);
        $data->setPassword($password);
        $data->setRole($role);
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
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($email) && !empty($username) && !empty($password) && empty($role)){
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setPassword($password);
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

    /**
     * @Method("POST")
     * @Route("/login")
     */
    public function loginDes(Request $request)
    {
        $content = $request->getContent();
        $serializer = $this->get('serializer');
        $userLogin = $serializer->deserialize($content, oUser::class, 'json');

        return new Response($userLogin);
    }


    ####      PRODUCTS            #################################
    /**
     * @Rest\Get("/product")
     */
    public function getProduct()
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:oProduct')->findAll();
        if ($restresult === null) {
            return new View("there are no products exist", Response::HTTP_NOT_FOUND);
        }
        return $restresult;
    }

    /**
     * @Rest\Get("/product/{id}")
     */
    public function idProduct($id)
    {
        $singleresult = $this->getDoctrine()->getRepository('AppBundle:oProduct')->find($id);
        if ($singleresult === null) {
            return new View("product not found", Response::HTTP_NOT_FOUND);
        }
        return $singleresult;
    }

    /**
     * @Rest\Post("/product/")
     */
    public function postProduct(Request $request)
    {
        $product = new oProduct();
        $nameProduct = $request->get('nameProduct');
        $category = $request->get('category');
        $description = $request->get('description');
        $photo = $request->get('photo');
        if(empty($nameProduct) || empty($category) || empty($description) || empty($photo))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $product->setNameProduct($nameProduct);
        $product->setCategory($category);
        $product->setDescription($description);
        $base64 = base64_decode($photo);
        $product->setPhoto($base64);
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return new View("Product Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/product/{id}")
     */
    public function updateProduct($id,Request $request)
    {
        $product = new oProduct();
        $nameProduct = $request->get('nameProduct');
        $category = $request->get('category');
        $description = $request->get('description');
        $photo = $request->get('photo');
        $sn = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AppBundle:oProduct')->find($id);
        if (empty($product)) {
            return new View("Product not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($nameProduct) && !empty($description) && !empty($photo) && empty($category)){
            $product->setNameProduct($nameProduct);
            $product->setDescription($description);
            $base64 = base64_decode($photo);
            $product->setPhoto($base64);
            $sn->flush();
            return new View("Product Updated Successfully", Response::HTTP_OK);
        }
        else return new View("Product cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Delete("/user/{id}")
     */
    public function deleteProduct($id)
    {
        $product = new oProduct();
        $sn = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AppBundle:oProduct')->find($id);
        if (empty($product)) {
            return new View("product not found", Response::HTTP_NOT_FOUND);
        }
        else {
            $sn->remove($product);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}
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
class RESTapiController extends FOSRestController
{
    /**
     * @Rest\Post("/login/")
     */
    ###### LOGIN ###########
    public function loginUser(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        if($email->get('security.authorization_checker')->isGranted('ROLE_CLIENT'))
            return new View("This account has CLIENT ROLE", Response::HTTP_OK);
        if($email->get('security.authorization_checker')->isGranted('ROLE_COMPANY'))
            return new View("This account has COMPANY ROLE", Response::HTTP_OK);
        return new View("user not found", Response::HTTP_NOT_FOUND);

    }

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
        $role = $request->get('role');
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT role FROM AppBundle:oRole role WHERE role.code = :code')->setParameter('code', $role);
        $orole = $query->getSingleResult();

        if(empty($email) || empty($username) || empty($password))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $data->setEmail($email);
        $data->setUsername($username);
        $data->setPassword($password);
        $data->setRole($orole);
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

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT productCategory FROM AppBundle:oProductCategory productCategory WHERE productCategory.nameCategory = :nameCategory')->setParameter('nameCategory', $category);
        $ocategory = $query->getSingleResult();

        if(empty($nameProduct) || empty($category) || empty($description) || empty($photo))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $product->setNameProduct($nameProduct);
        $product->setCategory($ocategory);
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
     * @Rest\Delete("/product/{id}")
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
<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\oProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Doctrine\ORM\Tools\Pagination\Paginator;
use AppBundle\Entity\oUser;

/**
 * @Route("/api")
 */
class oProductApiController extends FOSRestController
{
    /**
     * @Rest\Get("/my_product")
     */
    public function myProductAction()
    {
        $user = new oUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $id = $em->getRepository('AppBundle:oUser')->find($user);
        $query = $em->createQuery(
            'SELECT p from AppBundle:oProduct p WHERE p.userid = :id')->setParameter('id', $id);
        $security = $query->getResult();

        return $security;

    }
    /**
     * @Rest\Get("/product_10")
     */
    public function paginatorAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT product FROM AppBundle:oProduct product";
        $query = $em->createQuery($dql)->setFirstResult(0)->setMaxResults(10);

        $myArray = $query->getArrayResult();
        return new JsonResponse($myArray);
    }

    /**
     * @Rest\Get("/category")
     */
    public function getCategory()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery('SELECT productCategory FROM AppBundle:oProductCategory productCategory');
        $myArray = $query->getArrayResult();
        return new JsonResponse($myArray);
    }

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
        $header = $request->get('header');
        $category = $request->get('category');
        $description = $request->get('description');
        //$photo = $request->files->get('photo');
        $photo = $request->get('photo');
        $userid = $request->get('user_id');

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT productCategory FROM AppBundle:oProductCategory productCategory WHERE productCategory.nameCategory = :nameCategory')->setParameter('nameCategory', $category);
        $ocategory = $query->getSingleResult();
        $query1 = $em->createQuery('SELECT id FROM AppBundle:oUser id WHERE id.id = :id')->setParameter('id', $userid);
        $ouserid = $query1->getSingleResult();

        if(empty($header) || empty($category) || empty($description) || empty($photo) || empty($userid))
        {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE );
        }
        $product->setHeader($header);
        $product->setCategory($ocategory);
        $product->setDescription($description);

        $name = md5(uniqid()).'.jpeg';
        $dir = $this->get('kernel')->getRootDir() . '/../web/uploads/'.$name;

        file_put_contents($dir, base64_decode($photo));

        $product->setPhoto($name);
        $product->setUserid($ouserid);
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
        $header = $request->get('header');
        $category = $request->get('category');
        $description = $request->get('description');
        $photo = $request->get('photo');
        $userid = $request->get('user_id');
        $sn = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AppBundle:oProduct')->find($id);

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT productCategory FROM AppBundle:oProductCategory productCategory WHERE productCategory.id = :id')->setParameter('id', $category);
        $ocategory = $query->getSingleResult();
        $query1 = $em->createQuery('SELECT id FROM AppBundle:oUser id WHERE id.id = :id')->setParameter('id', $userid);
        $ouserid = $query1->getSingleResult();

        if (empty($product)) {
            return new View("Product not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($header) && !empty($description) && !empty($photo) && !empty($category) && !empty($userid)){
            $product->setHeader($header);
            $product->setCategory($ocategory);
            $product->setDescription($description);
            $base64 = base64_decode($photo);
            $product->setPhoto($base64);
            $product->setUserid($ouserid);
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
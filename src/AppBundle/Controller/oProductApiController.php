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
     * @Rest\Post("/search/")
     */
    public function searchAction(Request $request){

        $header = $request->request->get('header');
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT p FROM AppBundle:oProduct p WHERE p.header LIKE :header')->setParameter('header', $header);
        $product = $query->getResult();

        if($product){
            return $product;
        }
        return new View("Product not found", Response::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\Get("/my_product/{id}")
     */
    public function myProductAction($id,Request $request)
    {
        //$user = $this->get('security.token_storage')->getToken()->getUser();
        //$user = $request->get('user_id');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('AppBundle:oUser')->find($id);
        $query = $em->createQuery(
            'SELECT p.id, p.header, p.description, d.nameCategory, p.photo, o.email, o.telephone
             FROM AppBundle:oProduct p
             LEFT JOIN AppBundle:oUser o
             WITH p.userid = o.id
             LEFT JOIN AppBundle:oProductCategory d
             WITH p.category = d.id
             WHERE p.userid = :id')
             ->setParameter('id', $user);
        $security = $query->getArrayResult();

        return new JsonResponse($security);
    }

    /**
     * @Rest\Get("/product_10/{id}")
     */
    public function paginatorAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $dql = "SELECT p.id, p.header, p.description, d.nameCategory, p.photo, o.email, o.telephone
                 FROM AppBundle:oProduct p
                 LEFT JOIN AppBundle:oUser o
                 WITH p.userid = o.id
                 LEFT JOIN AppBundle:oProductCategory d
                 WITH p.category = d.id
                 WHERE p.id
                 BETWEEN (:id+1) AND (:id+4)";

        $query = $em->createQuery($dql)->setParameter('id',$id);
        $oProducts = $query->getArrayResult();

        return new JsonResponse($oProducts);
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
        $photo = $request->get('photo');
        $userid = $request->get('user_id');
        $price = $request->get('price');
        //$user = $this->get('security.token_storage')->getToken()->getUser();


        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT productCategory FROM AppBundle:oProductCategory productCategory WHERE productCategory.nameCategory = :nameCategory')->setParameter('nameCategory', $category);
        $ocategory = $query->getSingleResult();
        $query1 = $em->createQuery('SELECT id FROM AppBundle:oUser id WHERE id.id = :id')->setParameter('id', $userid);
        $ouserid = $query1->getSingleResult();

        if(empty($header) || empty($category) || empty($description) || empty($photo) || empty($userid) || empty($price))
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
        $product->setPrice($price);
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return new View("Product Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/product_update")
     */
    public function updateProduct(Request $request)
    {
        $id = $request->get('id');
        $header = $request->get('header');
        $category = $request->get('category');
        $description = $request->get('description');
        $photo = $request->get('photo');
        $sn = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('AppBundle:oProduct')->find($id);

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery('SELECT productCategory FROM AppBundle:oProductCategory productCategory WHERE productCategory.nameCategory = :nameCategory')->setParameter('nameCategory', $category);
        $ocategory = $query->getSingleResult();

        if (empty($product)) {
            return new View("Product not found", Response::HTTP_NOT_FOUND);
        }
        elseif(!empty($header) && !empty($description) && !empty($photo) && !empty($category)){
            $product->setHeader($header);
            $product->setCategory($ocategory);
            $product->setDescription($description);
            $name = md5(uniqid()).'.jpeg';
            $dir = $this->get('kernel')->getRootDir() . '/../web/uploads/'.$name;

            file_put_contents($dir, base64_decode($photo));

            $product->setPhoto($name);
            $sn->flush();
            return new View("Product Updated Successfully", Response::HTTP_OK);
        }
        else return new View("Product cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @Rest\Post("/product_delete")
     */
    public function deleteProduct(Request $request)
    {
        $id = $request->get('id');
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
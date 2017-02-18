<?php

namespace AppBundle\Controller;


use AppBundle\Entity\oProduct;
use AppBundle\Entity\oUser;
use AppBundle\Entity\oRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



/**
 * @Route("/api")
 */
class SerializerController extends Controller
{
    /**
     * @Method("POST")
     * @Route("/user/login")
     */
    public function loginDes(Request $request)
    {
        $content = $request->getContent();
        $serializer = $this->get('serializer');
        $user = $serializer->deserialize($content, oUser::class, 'json');

        return new Response($user);
    }

    /**
     * @Method("POST")
     * @Route("/user/register")
     */
    public function registerDes(Request $request)
    {
        $content = $request->getContent();
        $serializer = $this->get('serializer');
        $user = $serializer->deserialize($content, oUser::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response($user);
    }

    /**
     * @Route("/user")
     * @Method("GET")
     */
    public function registerSer(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:oUser')->findAll();

        $serializer = $this->get('serializer');
        $content = $serializer->serialize($user, 'json');
        return new Response($content);
    }

    /**
     * @Route("/product/new")
     * @Method("POST")
     */
    public function productDes(Request $request)
    {
        $content = $request->getContent();
        $serializer = $this->get('serializer');
        $product = $serializer->deserialize($content, oProduct::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response($product);
    }

    /**
     * @Route("/product")
     */
    public function productSer(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:oProduct')->findAll();

        $serializer = $this->get('serializer');
        $content = $serializer->serialize($product, 'json');
        return new Response($content);
    }
}
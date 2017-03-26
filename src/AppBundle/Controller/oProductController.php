<?php

namespace AppBundle\Controller;

use AppBundle\Entity\oProduct;

use AppBundle\Entity\oUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Oproduct controller.
 *
 * @Route("/oproducts")
 * @Security("has_role('ROLE_COMPANY')")
 */
class oProductController extends Controller
{
    /**
     * @Route("/myproduct", name="my_product")
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

        return $this->render('AppBundle:oProduct:myproduct.html.twig', array(
            'oProducts' => $security
        ));
    }

    /**
     * Lists all oProduct entities.
     *
     * @Route("/", name="new_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $oProducts = $em->getRepository('AppBundle:oProduct')->findAll();

        return $this->render('AppBundle:oProduct:index.html.twig', array(
            'oProducts' => $oProducts,
        ));
    }

    /**
     * Creates a new oProduct entity.
     *
     * @Route("/new", name="new_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $oProduct = new oProduct();
        $form = $this->createForm('AppBundle\Form\oProductType', $oProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $oUser = new oUser();
            $oUser = $this->get('security.token_storage')->getToken()->getUser();

            /**
             * @var UploadedFile $file
             */
            $file = $oProduct->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('photo_directory'),
                $fileName);
            $oProduct->setPhoto($fileName);
            $oProduct->setUserid($oUser);
            $em = $this->getDoctrine()->getManager();
            $em->persist($oProduct);
            $em->flush();

            return $this->redirectToRoute('new_show', array('id' => $oProduct->getId()));
        }

        return $this->render('AppBundle:oProduct:new.html.twig', array(
            'oProduct' => $oProduct,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a oProduct entity.
     *
     * @Route("/{id}", name="new_show")
     * @Method("GET")
     */
    public function showAction(oProduct $oProduct)
    {
        $deleteForm = $this->createDeleteForm($oProduct);

        return $this->render('AppBundle:oProduct:show.html.twig', array(
            'oProduct' => $oProduct,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing oProduct entity.
     *
     * @Route("/{id}/edit", name="new_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, oProduct $oProduct)
    {
        $deleteForm = $this->createDeleteForm($oProduct);
        $editForm = $this->createForm('AppBundle\Form\oProductType', $oProduct);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('new_edit', array('id' => $oProduct->getId()));
        }

        return $this->render('AppBundle:oProduct:edit.html.twig', array(
            'oProduct' => $oProduct,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a oProduct entity.
     *
     * @Route("/{id}", name="new_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, oProduct $oProduct)
    {
        $form = $this->createDeleteForm($oProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($oProduct);
            $em->flush($oProduct);
        }

        return $this->redirectToRoute('new_index');
    }

    /**
     * Creates a form to delete a oProduct entity.
     *
     * @param oProduct $oProduct The oProduct entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(oProduct $oProduct)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('new_delete', array('id' => $oProduct->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

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

class oProductController extends Controller
{
    /**
     * @Route("/", name="home_page")
     * @Method("GET")
     */
    public function paginationAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT p FROM AppBundle:oProduct p";
        $query = $em->createQuery($dql)->setFirstResult(0)->setMaxResults(4);
        $oProducts = $query->getResult();

        $oProductCategorys = $em->getRepository('AppBundle:oProductCategory')->findAll();

        return $this->render('AppBundle:oProduct:index.html.twig', array(
           'oProducts' => $oProducts,
           'oProductCategorys' => $oProductCategorys,
        ));
    }


    /**
     * @Route("/oproducts/myproduct", name="my_product")
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
     * @Route("/all", name="all_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $oProducts = $em->getRepository('AppBundle:oProduct')->findAll();

        return $this->render('AppBundle:oProduct:all_index.html.twig', array(
            'oProducts' => $oProducts,
        ));
    }

    /**
     * Creates a new oProduct entity.
     *
     * @Route("/oproducts/new", name="new_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_COMPANY')")
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
     * @Route("/oproducts/{id}", name="new_show")
     * @Method("GET")
     */
    public function showAction(oProduct $oProduct)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($oProduct);
        $oUsers = $em->getRepository('AppBundle:oUser')->findAll();
        return $this->render('AppBundle:oProduct:show.html.twig', array(
            'oProduct' => $oProduct,
            'oUsers' => $oUsers,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing oProduct entity.
     *
     * @Route("/oproducts/{id}/edit", name="new_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_COMPANY')")
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
     * @Route("/oproducts/{id}", name="new_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_COMPANY')")
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

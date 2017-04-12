<?php

namespace AppBundle\Controller;

use AppBundle\Entity\oProduct;

use AppBundle\Entity\oUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\OptionsResolver\OptionsResolver;

class oProductController extends Controller
{
    /**
     * @Route("/", name="home_page")
     * @Method("GET")
     */
    public function paginationAction(){
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM AppBundle:oProduct p";
        $query = $em->createQuery($dql)->setFirstResult(0)->setMaxResults(12);
        $oProducts = $query->getResult();

        $oProductCategorys = $em->getRepository('AppBundle:oProductCategory')->findAll();

        return $this->render('AppBundle:oProduct:index.html.twig', array(
           'oProducts' => $oProducts,
           'oProductCategorys' => $oProductCategorys,
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

        return $this->render('AppBundle:Categories:all.html.twig', array(
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

        $userId = $oProduct->getUserid();

        $query = $em->createQuery('SELECT p FROM AppBundle:oUser p WHERE p.id = :userId')->setParameter('userId', $userId);
        $oUsers = $query->getResult();


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
        $editForm = $this->createFormBuilder($oProduct)
            ->add('header', TextType::class, array('label' => 'Название продукта'))
            ->add('category', null, array('label' => 'Категория'))
            ->add('description', TextareaType::class, array('label'=>'Описание'))
            ->add('photo', FileType::class, array(
                'label' => 'Фотография',
                'data_class' => null,
            ))
            ->add('price', TextType::class, array('label' => 'Цена'))
            ->getForm();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();

            /**
             * @var UploadedFile $file
             */
            $file = $oProduct->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('photo_directory'),
                $fileName);
            $oProduct->setPhoto($fileName);
            $oProduct = $editForm->getData();
            $em->persist($oProduct);
            $em->flush();

            return $this->redirectToRoute('company_page');
        }

        return $this->render('AppBundle:oProduct:edit.html.twig', array(
            'oProduct' => $oProduct,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a oProduct entity.
     *
     * @Route("/oproducts/{id}/delete", name="new_delete")
     * @Method({"GET", "POST", "DELETE"})
     * @Security("has_role('ROLE_COMPANY')")
     */
    public function deleteAction(Request $request, oProduct $oProduct)
    {
            $em = $this->getDoctrine()->getManager();
            $product = $this->getDoctrine()->getRepository('AppBundle:oProduct')->find($oProduct);
            $em->remove($product);
            $em->flush();

        return $this->redirectToRoute('company_page');
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

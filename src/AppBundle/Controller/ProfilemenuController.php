<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\CustomSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Proxy\SessionHandlerProxy;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ProfilemenuController extends Controller
{

    public function profileMenuAction(Request $request)
    {
        $username = $this->get('security.token_storage')->getToken()->getUsername();

        $oUser = $this->get('security.token_storage')->getToken()->getUser();

            return $this->render('AppBundle:profile:profilemenu.html.twig', array(
                'username'=>$username,
            ));

    }
}
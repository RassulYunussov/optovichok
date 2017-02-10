<?php
/**
 * Created by PhpStorm.
 * User: Madi-PC
 * Date: 31.01.2017
 * Time: 20:56
 */

namespace AppBundle\Form;

use AppBundle\Entity\oUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'Почтовый адрес'))
            ->add('username', TextType::class, array('label' => 'Логин'))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Пароль'),
                'second_options' => array('label' => 'Повторите Пароль'),
            ))
            ->add('role', null, array('label' => 'Роль'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => oUser::class,
        ));
    }
}
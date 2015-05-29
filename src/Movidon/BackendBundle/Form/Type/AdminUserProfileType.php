<?php
namespace Movidon\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminUserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required' => false))
                ->add('lastName', 'text', array('required' => false))
                ->add('summary', 'textarea', array('required' => false, 'attr' => array('class' => 'tinymce', 'data-theme' => 'simple')))
                ->add('email', 'email', array('required' => false))
                ->add('twitter', 'text', array('required' => false))
                ->add('website', 'text', array('required' => false));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Movidon\BackendBundle\Entity\AdminUser',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Movidon\BackendBundle\Entity\AdminUser',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'admin_user_profile';
    }
}
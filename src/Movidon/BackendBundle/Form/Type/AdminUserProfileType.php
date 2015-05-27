<?php
namespace Movidon\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminUserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add('lastName', 'text')
                ->add('summary', 'textarea', array('attr' => array('class' => 'tinymce', 'data-theme' => 'simple')))
                ->add('email', 'email')
                ->add('twitter', 'text')
                ->add('website', 'text');
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
            'data_class' => 'Movidon\BackendBundle\Entity\AdminUser'
        ));
    }

    public function getName()
    {
        return 'admin_user_profile';
    }
}
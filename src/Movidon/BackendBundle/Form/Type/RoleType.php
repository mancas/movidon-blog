<?php
namespace Movidon\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('error_bubbling' => 'true'));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Movidon\BackendBundle\Entity\Role',
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Movidon\BackendBundle\Entity\Role'
        ));
    }

    public function getName()
    {
        return 'admin_role';
    }
}
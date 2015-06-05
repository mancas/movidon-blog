<?php
namespace Movidon\BackendBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', array('error_bubbling' => 'true'))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array('attr' => array('placeholder' => 'Password')),
                'second_options' => array('attr' => array('placeholder' => 'Confirmar password')),
                'error_bubbling' => 'true'));
        $builder->add('customRoles', 'entity',
            array('class' => 'BackendBundle:Role',
                'required' => false,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')->orderBy('r.name', 'ASC');
                }, 'expanded' => false));
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
        return 'admin_user';
    }
}
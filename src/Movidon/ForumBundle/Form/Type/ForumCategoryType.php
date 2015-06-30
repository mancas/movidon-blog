<?php
namespace Movidon\ForumBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ForumCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('error_bubbling' => 'true'));
        $builder->add('readAuthorisedRoles', 'entity',
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
            'data_class' => 'Movidon\ForumBundle\Entity\ForumCategory'
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Movidon\ForumBundle\Entity\ForumCategory'
        ));
    }

    public function getName()
    {
        return 'admin_forum_category';
    }
}
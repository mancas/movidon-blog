<?php

namespace Movidon\BlogBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text')
                ->add('subtitle', 'text')
                ->add('body', 'textarea', array('attr' => array('class' => 'tinymce', 'data-theme' => 'advanced')));
        $builder->add('tags', 'entity',
            array('class' => 'BlogBundle:Tag',
                'required' => false,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')->orderBy('t.name', 'ASC');
                }, 'expanded' => false));
        $builder->add('category', 'entity',
            array('class' => 'CategoryBundle:Category',
                'required' => false,
                'multiple' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }, 'expanded' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Movidon\BlogBundle\Entity\Post',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'post';
    }
}
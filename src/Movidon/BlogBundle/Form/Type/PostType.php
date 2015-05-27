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
        $translator = $options['translator'];
        $currentUser = $options['user'];
        $builder->add('title', 'text')
                ->add('subtitle', 'textarea', array('attr' => array('class' => 'tinymce', 'data-theme' => 'simple')))
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
                'empty_value' => $translator->trans('Choose post category'),
                'required' => false,
                'multiple' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }, 'expanded' => false));
        $builder->add('authors', 'entity',
            array('class' => 'BackendBundle:AdminUser',
                'required' => false,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) use ($currentUser) {
                    $qb = $er->createQueryBuilder('u');
                    $qb->orderBy('u.username', 'ASC');
                    $and = $qb->expr()->andX();

                    $and->add($qb->expr()->neq('u.username', '\'' . $currentUser->getUsername() . '\''));
                    $qb->where($and);

                    return $qb;
                }, 'expanded' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Movidon\BlogBundle\Entity\Post',
            'translator'        => 'Symfony\Component\Translation\IdentityTranslator',
            'user'              => 'Movidon\BackendBundle\Entity\AdminUser'
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
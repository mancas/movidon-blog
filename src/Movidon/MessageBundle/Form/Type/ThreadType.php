<?php

namespace Movidon\MessageBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThreadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentUser = $options['user'];
        $builder->add('participants', 'entity',
            array('class' => 'BackendBundle:AdminUser',
                'required' => true,
                'multiple' => false,
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
            'data_class'        => 'Movidon\MessageBundle\Entity\Thread',
            'user'              => 'Movidon\BackendBundle\Entity\AdminUser'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'thread';
    }
}
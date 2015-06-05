<?php

namespace Movidon\BackendBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Movidon\BackendBundle\Entity\AdminUser;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class AdminUserManager
{
    private $entityManager;
    private $encoderFactory;

    public function __construct(EntityManager $entityManager, EncoderFactory $encoderFactory)
    {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    public function save(AdminUser $user)
    {
        if (!$user->getSalt()) {
            $user->setSalt(md5(time() . AdminUser::AUTH_SALT));
        }
        $encoder = $this->encoderFactory->getEncoder($user);
        $passwordEncoded = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($passwordEncoded);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
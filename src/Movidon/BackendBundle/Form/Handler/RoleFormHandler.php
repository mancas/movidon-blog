<?php

namespace Movidon\BackendBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;

class RoleFormHandler
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(FormInterface $form, Request $request)
    {
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $role = $form->getData();
                $role->setName(strtoupper($role->getName()));
                $this->entityManager->persist($role);
                $this->entityManager->flush();

                return true;
            }
        }

        return false;
    }
}
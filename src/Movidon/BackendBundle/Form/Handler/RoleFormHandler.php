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
                $roleName = $role->getName();

                $role->setName($this->sanitizeRoleName($roleName));
                $this->entityManager->persist($role);
                $this->entityManager->flush();

                return true;
            }
        }

        return false;
    }

    private function sanitizeRoleName($role)
    {
        return 'ROLE_' . strtoupper($role);
    }

}
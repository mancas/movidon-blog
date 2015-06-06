<?php

namespace Movidon\BackendBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;

class AdminUserFormHandler
{
    private $adminUserManager;

    public function __construct(AdminUserManager $adminUserManager)
    {
        $this->adminUserManager = $adminUserManager;
    }

    public function handle(FormInterface $form, Request $request)
    {
        if ($request->isMethod('POST')) {
            $originalPassword = $form->getData()->getPassword();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $user = $form->getData();
                $updatePwd = true;
                if ($user->getPassword() === null) {
                    $user->setPassword($originalPassword);
                    $updatePwd = false;
                }
                $this->adminUserManager->save($user, $updatePwd);

                return true;
            }
        }

        return false;
    }

}
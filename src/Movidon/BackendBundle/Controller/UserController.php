<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BackendBundle\Entity\AdminUser;
use Movidon\BackendBundle\Form\Type\AdminUserType;
use Movidon\BackendBundle\Utils\UpdateEntityHelper;
use Movidon\ImageBundle\Form\Type\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends CustomController
{
    public function listAction()
    {
        $em = $this->getEntityManager();
        $paginator = $this->get('ideup.simple_paginator');
        $paginator->setItemsPerPage(CustomController::ITEMS_PER_PAGE, 'users');
        $users = $paginator->paginate($em->getRepository('BackendBundle:AdminUser')->findAllDQL(), 'users')->getResult();

        return $this->render('BackendBundle:Admin:User/list.html.twig', array('users' => $users));
    }

    public function createAction(Request $request)
    {
        $user = new AdminUser();
        $form = $this->createForm(new AdminUserType(), $user);
        $handler = $this->get('admin.admin_user_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The new user has been created successfully');

            return $this->redirect($this->generateUrl('super_admin_user_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Admin:User/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @ParamConverter("user", class="BackendBundle:AdminUser")
     */
    public function editAction(AdminUser $user, Request $request)
    {
        $form = $this->createForm(new AdminUserType(), $user);
        $handler = $this->get('admin.admin_user_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The user has been edited successfully');

            return $this->redirect($this->generateUrl('super_admin_user_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Admin:User/create.html.twig', array('edition' => true, 'user' => $user, 'form' => $form->createView()));
    }

    /**
     * @ParamConverter("user", class="BackendBundle:AdminUser")
     */
    public function deleteAction(AdminUser $user)
    {
        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
        $this->setTranslatedFlashMessage('The user has been removed successfully');

        return $this->redirect($this->generateUrl('super_admin_user_index'));
    }

    /**
     * @ParamConverter("user", class="BackendBundle:AdminUser")
     */
    public function toggleBanAction(AdminUser $user)
    {
        if ($user === $this->getCurrentUser()) {
            $this->setTranslatedFlashMessage('You can not ban yourself!', 'error');
        } else {
            $em = $this->getEntityManager();
            $user->setBanned(!$user->getBanned());
            $em->persist($user);
            $em->flush();
            if ($user->getBanned()) {
                $this->setTranslatedFlashMessage('The user has been banned successfully');
            } else {
                $this->setTranslatedFlashMessage('The user is no longer banned');
            }
        }

        return $this->redirect($this->generateUrl('super_admin_user_index'));
    }

    /**
     * @ParamConverter("user", class="BackendBundle:AdminUser")
     */
    public function profileAction(AdminUser $user, Request $request)
    {
        $form = $this->createForm(new ImageType());
        $imagesHandler = $this->get('image.form_handler');

        if ($request->isMethod('post') &&
            !$imagesHandler->handle($form, $request, $user)) {
            $this->setTranslatedFlashMessage('Profile image could not be updated', 'error');
        }

        return $this->render('BackendBundle:Admin:Profile/profile.html.twig', array('user' => $user, 'form' => $form->createView(), 'superadmin' => true));
    }

    /**
     * @ParamConverter("user", class="BackendBundle:AdminUser")
     */
    public function updateProfileAction(AdminUser $user, Request $request)
    {
        $json_response = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $em = $this->getEntityManager();

            $data = $request->request->all();
            $data = $data['admin_user_profile'];

            $updatedValues = UpdateEntityHelper::updateEntity($data, $user, 'admin_user_profile_');

            $em->persist($user);
            $em->flush();
            $json_response = json_encode(array('ok' => true, 'updatedValues' => $updatedValues));
        }

        return $this->getHttpJsonResponse($json_response);
    }
}

<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BackendBundle\Entity\AdminUser;
use Movidon\BackendBundle\Entity\Role;
use Movidon\BackendBundle\Form\Type\AdminUserType;
use Movidon\BackendBundle\Form\Type\RoleType;
use Movidon\FrontendBundle\Util\ArrayHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RoleController extends CustomController
{
    public function listAction()
    {
        $em = $this->getEntityManager();
        $roles = $em->getRepository('BackendBundle:Role')->findAll();

        return $this->render('BackendBundle:Admin:Role/list.html.twig', array('roles' => $roles));
    }

    public function createAction(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(new RoleType(), $role);
        $handler = $this->get('admin.admin_role_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The new role has been created successfully');

            return $this->redirect($this->generateUrl('super_admin_role_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Admin:Role/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @ParamConverter("role", class="BackendBundle:Role")
     */
    public function editAction(Role $role, Request $request)
    {
        $form = $this->createForm(new RoleType(), $role);
        $handler = $this->get('admin.admin_role_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The role has been edited successfully');

            return $this->redirect($this->generateUrl('super_admin_role_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Admin:Role/create.html.twig', array('edition' => true, 'role' => $role, 'form' => $form->createView()));
    }

    /**
     * @ParamConverter("role", class="BackendBundle:Role")
     */
    public function deleteAction(Role $role)
    {
        $em = $this->getEntityManager();
        $em->remove($role);
        $em->flush();
        $this->setTranslatedFlashMessage('The role has been removed successfully');

        return $this->redirect($this->generateUrl('super_admin_role_index'));
    }
}

<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BackendBundle\Entity\AdminUser;
use Movidon\BackendBundle\Form\Type\AdminUserType;
use Movidon\FrontendBundle\Util\ArrayHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BackendController extends CustomController
{
    public function indexAction()
    {
        return $this->render('BackendBundle:Backend:index.html.twig');
    }

    public function loginAction(Request $request)
    {
        return $this->renderLoginTemplate('BackendBundle:Backend:login.html.twig', $request);
    }

    public function registerAction(Request $request)
    {
        $user = new AdminUser();
        $form = $this->createForm(new AdminUserType(), $user);
        $formHandler = $this->get('admin.admin_user_form_handler');
        if ($formHandler->handle($form, $request)) {
            $token = new UsernamePasswordToken($user, $user->getPassword(), 'admin_user', $user->getRoles());
            $this->container->get('security.context')->setToken($token);
            $this->container->get('session')->set("_security_private", serialize($token));

            return $this->redirect($this->generateUrl('admin'));
        }

        return $this->render('BackendBundle:Backend:register.html.twig', array('form' => $form->createView(), 'dontShowMenu' => true));
    }

    /**
     * @Template("BackendBundle:Commons:sidenav.html.twig")
     *
     * @return array
     */
    public function sidenavAction()
    {
        return array('user' => $this->getCurrentUser());
    }
}

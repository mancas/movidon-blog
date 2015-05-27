<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BackendBundle\Form\Type\AdminUserProfileType;
use Movidon\ImageBundle\Form\Type\MultipleImagesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends CustomController
{
    public function avatarsAction(Request $request)
    {
        $em = $this->getEntityManager();
        $form = $this->createForm(new MultipleImagesType());
        $imagesHandler = $this->get('image.form_handler');

        if ($request->isMethod('post') &&
            !$imagesHandler->handleMultiple($form, $request, 'ImageAvatar')) {
            $this->setTranslatedFlashMessage('Something went wrong', 'error');
        }

        $avatars = $em->getRepository('ImageBundle:ImageAvatar')->findAll();

        return $this->render('BackendBundle:Admin:Avatar/index.html.twig', array('form' => $form->createView(),
                                                                                   'avatars' => $avatars));
    }

    public function viewProfileAction()
    {
        $form = $this->createForm(new AdminUserProfileType());

        return $this->render('BackendBundle:Admin:Profile/profile.html.twig', array('form' => $form->createView()));
    }
}

<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BackendBundle\Entity\AdminUser;
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
        return $this->render('BackendBundle:Admin:Profile/profile.html.twig', array('user' => $this->getCurrentUser()));
    }

    public function updateProfileAction(Request $request)
    {
        $json_response = json_encode(array('ok' => false));
        if ($request->isXmlHttpRequest()) {
            $em = $this->getEntityManager();

            $data = $request->request->all();
            $data = $data['admin_user_profile'];
            $user = $this->getCurrentUser();

            $updatedValues = array();
            foreach ($data as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($user, $method)) {
                    $user->$method($value);
                    $updatedValues['admin_user_profile_' . $key] = $value;
                }
            }

            $em->persist($user);
            $em->flush();
            $json_response = json_encode(array('ok' => true, 'updatedValues' => $updatedValues));
        }

        return $this->getHttpJsonResponse($json_response);
    }
}

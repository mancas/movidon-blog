<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\ForumBundle\Entity\Forum;
use Movidon\ForumBundle\Entity\ForumCategory;
use Movidon\ForumBundle\Form\Type\ForumCategoryType;
use Movidon\ForumBundle\Form\Type\ForumType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminForumController extends CustomController
{
    public function listAction()
    {
        $em = $this->getEntityManager();
        $forums = $em->getRepository('ForumBundle:Forum')->findAll();

        return $this->render('BackendBundle:Admin:Forum/list.html.twig', array('forums' => $forums));
    }

    public function createAction(Request $request)
    {
        $forum = new Forum();
        $form = $this->createForm(new ForumType(), $forum);
        $handler = $this->get('admin.admin_forum_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The new forum has been created successfully');

            return $this->redirect($this->generateUrl('super_admin_forum_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Admin:Forum/create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @ParamConverter("forum", class="ForumBundle:Forum")
     */
    public function editAction(Forum $forum, Request $request)
    {
        $form = $this->createForm(new ForumType(), $forum);
        $handler = $this->get('admin.admin_forum_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The new forum has been created successfully');

            return $this->redirect($this->generateUrl('super_admin_forum_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Admin:Forum/create.html.twig', array('edition' => true, 'forum' => $forum, 'form' => $form->createView()));    }

    /**
     * @ParamConverter("forum", class="ForumBundle:Forum")
     */
    public function deleteAction(Forum $forum)
    {
        $em = $this->getEntityManager();
        $forum->setDeleted(new \DateTime('now'));
        $em->persist($forum);
        $em->flush();
        $this->setTranslatedFlashMessage('The forum has been removed successfully');

        return $this->redirect($this->generateUrl('super_admin_forum_index'));
    }

    /**
     * @ParamConverter("forum", class="ForumBundle:Forum")
     */
    public function viewAction(Forum $forum)
    {
        return $this->render('BackendBundle:Admin:Forum/view.html.twig', array('forum' => $forum));
    }

    /**
     * @ParamConverter("forum", class="ForumBundle:Forum")
     */
    public function createCategoryAction(Forum $forum, Request $request)
    {
        $category = new ForumCategory();
        $form = $this->createForm(new ForumCategoryType(), $category);
        $handler = $this->get('admin.admin_forum_category_form_handler');

        if ($handler->handle($form, $forum, $request)) {
            $this->setTranslatedFlashMessage('The new forum category has been created successfully');

            return $this->redirect($this->generateUrl('super_admin_forum_view', array('slug' => $forum->getSlug())));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Admin:Forum/Category/create.html.twig', array('form' => $form->createView(), 'forum' => $forum));
    }
}

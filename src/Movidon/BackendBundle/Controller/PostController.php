<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BlogBundle\Entity\Post;
use Movidon\BlogBundle\Form\Type\PostType;
use Movidon\ImageBundle\Form\Type\MultipleImagesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;

class PostController extends CustomController
{
    public function listAction()
    {
        $em = $this->getEntityManager();
        $paginator = $this->get('ideup.simple_paginator');
        $paginator->setItemsPerPage(CustomController::ITEMS_PER_PAGE, 'posts');
        $posts = $paginator->paginate($em->getRepository('BlogBundle:Post')->findAllDQL(), 'posts')->getResult();

        return $this->render('BackendBundle:Post:list.html.twig', array('posts' => $posts));
    }

    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(new PostType(), $post, array('translator' => $this->get('translator')));
        $imageForm = $this->createForm(new MultipleImagesType());
        $handler = $this->get('blog.post_form_handler');
        $imagesHandler = $this->get('image.form_handler');

        if ($handler->handle($form, $request, $this->getCurrentUser())) {
            if ($imagesHandler->handleMultiple($imageForm, $request, $post)) {
                $this->setTranslatedFlashMessage('The post has been created successfully. Now you can pusblish it');
            } else {
                $this->setTranslatedFlashMessage('The post has been created successfully. However there is a problem with the images.');
                return $this->redirect($this->generateUrl('admin_post_edit', array('slug' => $post->getSlug())));
            }

            return $this->redirect($this->generateUrl('admin_post_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Post:create.html.twig', array('form' => $form->createView(),
                                                                          'imageForm' => $imageForm->createView()));
    }

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function editAction(Post $post, Request $request)
    {
        $form = $this->createForm(new PostType(), $post, array('translator' => $this->get('translator')));
        $imageForm = $this->createForm(new MultipleImagesType());
        $handler = $this->get('blog.post_form_handler');
        $imagesHandler = $this->get('image.form_handler');

        if ($handler->handle($form, $request, $this->getCurrentUser())) {
            if ($imagesHandler->handleMultiple($imageForm, $request, $post)) {
                $this->setTranslatedFlashMessage('The post has been edited successfully');
            } else {
                $this->setTranslatedFlashMessage('There is a problem with the images.');
                return $this->redirect($this->generateUrl('admin_post_edit', array('slug' => $post->getSlug())));
            }

            return $this->redirect($this->generateUrl('admin_post_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Post:create.html.twig', array('edition' => true, 'post' => $post,
                                                                          'form' => $form->createView(),
                                                                          'imageForm' => $imageForm->createView()));
    }

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function deleteAction(Post $post)
    {
        $em = $this->getEntityManager();
        $post->setDeleted(new \DateTime('now'));

        $em->persist($post);
        $em->flush();
        $this->setTranslatedFlashMessage('The post has been removed successfully');

        return $this->redirect($this->generateUrl('admin_post_index'));
    }

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function restoreAction(Post $post)
    {
        $em = $this->getEntityManager();
        $post->setDeleted(null);

        $em->persist($post);
        $em->flush();
        $this->setTranslatedFlashMessage('The post has been restored successfully');

        return $this->redirect($this->generateUrl('admin_post_index'));
    }

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function publishAction(Post $post)
    {
        $post->setPublished(new \DateTime('now'));
        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();
        $this->setTranslatedFlashMessage('The post has been published successfully');

        return $this->redirect($this->generateUrl('admin_post_index'));
    }

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function unpublishAction(Post $post)
    {
        $post->setPublished(null);
        $em = $this->getEntityManager();
        $em->persist($post);
        $em->flush();
        $this->setTranslatedFlashMessage('The post has been unpublished successfully');

        return $this->redirect($this->generateUrl('admin_post_index'));
    }
}

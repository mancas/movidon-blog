<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BlogBundle\Entity\FeedbackPositive;
use Movidon\BlogBundle\Entity\FeedbackNegative;
use Movidon\BlogBundle\Entity\Post;
use Movidon\BlogBundle\Form\Type\PostType;
use Movidon\ImageBundle\Entity\ImagePost;
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

        return $this->render('BackendBundle:Post:list.html.twig', array('posts' => $posts, 'user' => $this->getCurrentUser()));
    }

    public function createAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(new PostType(), $post, array('translator' => $this->get('translator'),
                                                               'user' => $this->getCurrentUser()));
        $imageForm = $this->createForm(new MultipleImagesType());
        $handler = $this->get('blog.post_form_handler');
        $imagesHandler = $this->get('image.form_handler');

        if ($handler->handle($form, $request, $this->getCurrentUser())) {
            if ($imagesHandler->handleMultiple($imageForm, $request, 'ImagePost', $post)) {
                $this->setTranslatedFlashMessage('The post has been created successfully. Now you can pusblish it');
            } else {
                $this->setTranslatedFlashMessage('The post has been created successfully. However there is a problem with the images.', 'error');
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
        $form = $this->createForm(new PostType(), $post, array('translator' => $this->get('translator'),
                                                               'user' => $this->getCurrentUser()));
        $imageForm = $this->createForm(new MultipleImagesType());
        $handler = $this->get('blog.post_form_handler');
        $imagesHandler = $this->get('image.form_handler');

        if ($handler->handle($form, $request, $this->getCurrentUser())) {
            if ($imagesHandler->handleMultiple($imageForm, $request, 'ImagePost', $post)) {
                $this->setTranslatedFlashMessage('The post has been edited successfully');
            } else {
                $this->setTranslatedFlashMessage('There is a problem with the images.', 'error');
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

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function viewAction(Post $post, Request $request)
    {
        return $this->render('BackendBundle:Post:view.html.twig', array('post' => $post, 'user' => $this->getCurrentUser()));
    }

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function sendFeedbackAction(Post $post, Request $request)
    {
        $data = $request->request->all();
        $data = $data['post_feedback'];
        if (!isset($data)) {
            $this->setTranslatedFlashMessage('Something went wrong', 'error');
            return $this->redirect($this->generateUrl('admin_post_view', array('slug' => $post->getSlug())));
        }

        $className = 'Movidon\\BlogBundle\\Entity\\Feedback' . ucfirst($data['type']);
        $feedback = new $className();

        $feedback->setAuthor($this->getCurrentUser());
        $feedback->setFeedback($data['feedback']);
        $feedback->setPost($post);

        $em = $this->getEntityManager();
        $em->persist($feedback);
        $em->flush();

        $this->setTranslatedFlashMessage('Your feedback has been send correctly');
        return $this->redirect($this->generateUrl('admin_post_view', array('slug' => $post->getSlug())));
    }
}

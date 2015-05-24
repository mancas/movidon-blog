<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BlogBundle\Entity\Post;
use Movidon\BlogBundle\Form\Type\PostType;
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
        $form = $this->createForm(new PostType(), $post);
        $handler = $this->get('blog.post_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The post has been created successfully. Now you can pusblish it');

            return $this->redirect($this->generateUrl('admin_post_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Post:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function editAction(Post $post, Request $request)
    {
        $form = $this->createForm(new PostType(), $post);
        $handler = $this->get('blog.post_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The post has been edited successfully');

            return $this->redirect($this->generateUrl('admin_post_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Post:create.html.twig', array('edition' => true, 'post' => $post, 'form' => $form->createView()));
    }

    /**
     * @ParamConverter("category", class="CategoryBundle:Category")
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getEntityManager();
        $posts = $em->getRepository('BlogBundle:Post')->findBy(array('category' => $category->getId()));
        foreach ($posts as $post) {
            $post->setCategory(null);
            $em->persist($post);
        }
        $em->flush();

        $em->remove($category);
        $em->flush();
        $this->setTranslatedFlashMessage('The category has been removed successfully');

        return $this->redirect($this->generateUrl('admin_category_index'));
    }
}

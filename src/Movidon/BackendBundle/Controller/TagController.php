<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\BlogBundle\Entity\Tag;
use Movidon\BlogBundle\Form\Type\TagType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;

class TagController extends CustomController
{
    public function listAction()
    {
        $em = $this->getEntityManager();
        $paginator = $this->get('ideup.simple_paginator');
        $paginator->setItemsPerPage(CustomController::ITEMS_PER_PAGE, 'tags');
        $tags = $paginator->paginate($em->getRepository('BlogBundle:Tag')->findAllDQL(), 'tags')->getResult();

        return $this->render('BackendBundle:Tag:list.html.twig', array('tags' => $tags));
    }

    public function createAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(new TagType(), $tag);
        $handler = $this->get('blog.tag_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The new tag has been created successfully');

            return $this->redirect($this->generateUrl('admin_tag_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Tag:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @ParamConverter("tag", class="BlogBundle:Tag")
     */
    public function editAction(Tag $tag, Request $request)
    {
        $form = $this->createForm(new TagType(), $tag);
        $handler = $this->get('blog.tag_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The tag has been edited successfully');

            return $this->redirect($this->generateUrl('admin_tag_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Tag:create.html.twig', array('edition' => true, 'tag' => $tag, 'form' => $form->createView()));
    }

    /**
     * @ParamConverter("tag", class="BlogBundle:Tag")
     */
    public function deleteAction(Tag $tag)
    {
        $em = $this->getEntityManager();
        /*$posts = $em->getRepository('BlogBundle:Post')->findBy(array('tag' => $tag->getId()));
        foreach ($posts as $post) {
            $post->removeTag($tag);
            $em->persist($post);
        }
        $em->flush();*/

        $em->remove($tag);
        $em->flush();
        $this->setTranslatedFlashMessage('The tag has been removed successfully');

        return $this->redirect($this->generateUrl('admin_tag_index'));
    }
}

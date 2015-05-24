<?php

namespace Movidon\BackendBundle\Controller;

use Movidon\CategoryBundle\Entity\Category;
use Movidon\CategoryBundle\Form\Type\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Movidon\FrontendBundle\Controller\CustomController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends CustomController
{
    public function listAction()
    {
        $em = $this->getEntityManager();
        $paginator = $this->get('ideup.simple_paginator');
        $paginator->setItemsPerPage(CustomController::ITEMS_PER_PAGE, 'categories');
        $categories = $paginator->paginate($em->getRepository('CategoryBundle:Category')->findAllDQL(), 'categories')->getResult();

        return $this->render('BackendBundle:Category:list.html.twig', array('categories' => $categories));
    }

    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);
        $handler = $this->get('category.category_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The new category has been created successfully');

            return $this->redirect($this->generateUrl('admin_category_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Category:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * @ParamConverter("category", class="CategoryBundle:Category")
     */
    public function editAction(Category $category, Request $request)
    {
        $form = $this->createForm(new CategoryType(), $category);
        $handler = $this->get('category.category_form_handler');

        if ($handler->handle($form, $request)) {
            $this->setTranslatedFlashMessage('The category has been edited successfully');

            return $this->redirect($this->generateUrl('admin_category_index'));
        } else {
            if ($request->isMethod('POST'))
                $this->setTranslatedFlashMessage('There is an error in your request', 'error');
        }

        return $this->render('BackendBundle:Category:create.html.twig', array('edition' => true, 'category' => $category, 'form' => $form->createView()));
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

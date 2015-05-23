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
     * @ParamConverter("tax", class="ItemBundle:Tax")
     */
    public function deleteAction(Tax $tax)
    {
        $em = $this->getEntityManager();
        $items = $em->getRepository('ItemBundle:Item')->findBy(array('tax' => $tax->getId()));
        foreach ($items as $item) {
            $item->setTax(null);
            $em->persist($item);
        }
        $em->flush();

        $em->remove($tax);
        $em->flush();
        $this->setTranslatedFlashMessage('Se ha eliminado correctamente el impuesto. Recuerda actualizar el impuesto de todos aquellos productos a los que se les aplicaba.');

        return $this->redirect($this->generateUrl('admin_tax_index'));
    }

    public function getTaxesJSONAction()
    {
        $em = $this->getEntityManager();
        $taxes = $em->getRepository('ItemBundle:Tax')->findAll();
        $taxesValues = array();

        foreach($taxes as $tax) {
            $taxesValues[$tax->getName()] = $tax->getTaxes();
        }

        $jsonResponse = json_encode($taxesValues);

        return $this->getHttpJsonResponse($jsonResponse);
    }
}

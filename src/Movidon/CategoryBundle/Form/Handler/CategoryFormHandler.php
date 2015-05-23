<?php

namespace Movidon\CategoryBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Movidon\CategoryBundle\Entity\Category;

class CategoryFormHandler
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function handle(FormInterface $form, Request $request)
    {
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $category = $form->getData();
                $this->em->persist($category);
                $this->em->flush();
                return true;
            }
        }

        return false;
    }
}
<?php

namespace Movidon\BlogBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Movidon\BlogBundle\Entity\Tag;

class TagFormHandler
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
                $tag = $form->getData();
                $this->em->persist($tag);
                $this->em->flush();
                return true;
            }
        }

        return false;
    }
}
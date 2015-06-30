<?php

namespace Movidon\ForumBundle\Form\Handler;

use Movidon\ForumBundle\Entity\Forum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;

class ForumCategoryFormHandler
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(FormInterface $form, Forum $forum, Request $request)
    {
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $forumCategory = $form->getData();
                $forumCategory->setForum($forum);
                $this->entityManager->persist($forumCategory);
                $this->entityManager->flush();

                return true;
            }
        }

        return false;
    }
}
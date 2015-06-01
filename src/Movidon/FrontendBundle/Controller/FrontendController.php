<?php

namespace Movidon\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends CustomController
{
    const POSTS_LIMIT_DQL = 4;

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAllBlog(self::POSTS_LIMIT_DQL);

        return $this->render('FrontendBundle:Pages:home.html.twig', array('posts' => $posts));
    }
}
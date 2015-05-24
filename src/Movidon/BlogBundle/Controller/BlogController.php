<?php

namespace Movidon\BlogBundle\Controller;

use Movidon\BlogBundle\Entity\Post;
use Movidon\FrontendBundle\Controller\CustomController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BlogController extends CustomController
{
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAllBlog();

        return $this->render('BlogBundle:Blog:index.html.twig', array('posts' => $posts));
    }

    /**
     * @param Post $post
     * @Template("BlogBundle:Commons:post-card.html.twig")
     *
     * @return array
     */
    public function postCardAction(Post $post) {
        return array('post' => $post);
    }
}

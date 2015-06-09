<?php

namespace Movidon\FrontendBundle\Controller;

use Movidon\BackendBundle\Entity\AdminUser;
use Movidon\FrontendBundle\Util\ArrayHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FrontendController extends CustomController
{
    const POSTS_LIMIT_DQL = 4;

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAllBlog(self::POSTS_LIMIT_DQL);

        return $this->render('FrontendBundle:Pages:home.html.twig', array('posts' => $posts));
    }

    public function whoAreWeAction()
    {
        $em = $this->getEntityManager();
        //TODO
        //$authors = $em->getRepository('BackendBundle:AdminUser')->findAllOrderedByPostCount();
        $authors = $em->getRepository('BackendBundle:AdminUser')->findAll();

        return $this->render('FrontendBundle:Pages:who-are-we.html.twig', array('authors' => $authors));
    }

    /**
     * @ParamConverter("user", class="BackendBundle:AdminUser")
     */
    public function viewProfileAction(AdminUser $user)
    {
        return $this->render('FrontendBundle:Pages:profile.html.twig', array('user' => $user));
    }

    /**
     * @param AdminUser $user
     * @Template("FrontendBundle:Commons:author-card.html.twig")
     *
     * @return array
     */
    public function authorCardAction(AdminUser $user) {
        return array('user' => $user);
    }
}
<?php

namespace Movidon\BlogBundle\Controller;

use Movidon\BackendBundle\Entity\AdminUser;
use Movidon\BlogBundle\Entity\Post;
use Movidon\BlogBundle\Entity\Tag;
use Movidon\FrontendBundle\Controller\CustomController;
use Movidon\FrontendBundle\Util\ArrayHelper;
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
     * @ParamConverter("post", class="BlogBundle:Post")
     */
    public function viewAction(Post $post)
    {
        return $this->render('BlogBundle:Blog:post.html.twig', array('post' => $post));
    }

    /**
     * @ParamConverter("user", class="BackendBundle:AdminUser")
     */
    public function viewUserPostsAction(AdminUser $user)
    {
        return $this->render('BlogBundle:Blog:posts-by-user.html.twig', array('user' => $user));
    }

    /**
     * @Template("BlogBundle:Commons:tag-cloud.html.twig")
     *
     * @return array
     */
    public function tagCloudAction() {
        $em = $this->getEntityManager();
        $tags = $em->getRepository('BlogBundle:Tag')->findTagsWithPostsCount();
        $tagsValues = array();

        $arrayValues = array();
        if (count($tags) > 0) {
            foreach ($tags as $tag) {
                $arrayValues[] = $tag['postCount'];
            }
            foreach ($tags as $tag) {
                $tag['value'] = ArrayHelper::getBoundPosition($tag['postCount'], $arrayValues);
                $tagsValues[] = $tag;
            }
        }

        return array('tags' => $tagsValues);
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

    /**
     * @ParamConverter("tag", class="BlogBundle:Tag")
     */
    public function viewPostsByTagAction(Tag $tag)
    {
        $em = $this->getEntityManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAllByTag($tag);

        return $this->render('BlogBundle:Blog:index.html.twig', array('posts' => $posts));
    }
}

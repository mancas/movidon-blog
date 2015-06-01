<?php

namespace Movidon\ImageBundle\Entity;

use Movidon\ImageBundle\Entity\Image;
use Movidon\ImageBundle\Util\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ImagePost extends Image
{
    CONST MAX_WIDTH = 1024;
    CONST MAX_HEIGHT = 768;
    protected $subdirectory = "images/posts";
    protected $maxWidth = self::MAX_WIDTH;
    protected $maxHeight = self::MAX_HEIGHT;

    /**
     * @ORM\ManyToOne(targetEntity="Movidon\BlogBundle\Entity\Post", inversedBy="images")
     */
    protected $post;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" = 0})
     */
    protected $main = false;

    public function setPost(\Movidon\BlogBundle\Entity\Post $post)
    {
        $this->post = $post;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function createImagePostCard()
    {
        $thumb = $this->getImagePostCard();
        if (!$thumb) {
            $thumb = new ImagePostCard();
        }

        return $thumb;
    }

    public function createImagePostCarousel()
    {
        $thumb = $this->getImagePostCarousel();
        if (!$thumb) {
            $thumb = new ImagePostCarousel();
        }

        return $thumb;
    }

    public function createCopies()
    {
        list($oldRoute, $copies) = parent::createCopies();

        if ($imagePostCard = $this->createImagePostCard()) {
            $copies[] = $imagePostCard;
        }

        if ($imagePostCarousel = $this->createImagePostCarousel()) {
            $copies[] = $imagePostCarousel;
        }

        return array($oldRoute, $copies);
    }

    public function getMain()
    {
        return $this->main;
    }

    public function setMain($main)
    {
        $this->main = $main;
    }
}

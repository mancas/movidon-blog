<?php

namespace Movidon\ImageBundle\Entity;

use Movidon\ImageBundle\Entity\Image;
use Movidon\ImageBundle\Util\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ImageProfile extends Image
{
    CONST MAX_WIDTH = 1024;
    CONST MAX_HEIGHT = 768;
    protected $subdirectory = "images/profile";
    protected $maxWidth = self::MAX_WIDTH;
    protected $maxHeight = self::MAX_HEIGHT;

    /**
     * @ORM\ManyToOne(targetEntity="Movidon\BackendBundle\Entity\AdminUser", inversedBy="imagesProfile", cascade={"persist", "remove", "merge"})
     */
    protected $user;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" = 0})
     */
    protected $main = false;

    public function setUser(\Movidon\BackendBundle\Entity\AdminUser $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function createImageProfileAvatar()
    {
        $thumb = $this->getImageProfileAvatar();
        if (!$thumb) {
            $thumb = new ImageProfileAvatar();
        }

        return $thumb;
    }

    public function createImageProfileSquare()
    {
        $thumb = $this->getImageProfileSquare();
        if (!$thumb) {
            $thumb = new ImageProfileSquare();
        }

        return $thumb;
    }

    public function createCopies()
    {
        list($oldRoute, $copies) = parent::createCopies();

        if ($imageProfileAvatar = $this->createImageProfileAvatar()) {
            $copies[] = $imageProfileAvatar;
        }

        if ($imageProfileSquare = $this->createImageProfileSquare()) {
            $copies[] = $imageProfileSquare;
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

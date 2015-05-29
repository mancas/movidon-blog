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
     * @ORM\ManyToOne(targetEntity="Movidon\BackendBundle\Entity\AdminUser", inversedBy="imageProfile")
     */
    protected $user;

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

    public function createCopies()
    {
        list($oldRoute, $copies) = parent::createCopies();

        if ($imageProfileAvatar = $this->createImageProfileAvatar()) {
            $copies[] = $imageProfileAvatar;
        }

        return array($oldRoute, $copies);
    }
}

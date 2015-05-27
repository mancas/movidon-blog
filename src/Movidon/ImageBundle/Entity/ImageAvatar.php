<?php

namespace Movidon\ImageBundle\Entity;

use Movidon\ImageBundle\Entity\Image;
use Movidon\ImageBundle\Util\FileHelper;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class ImageAvatar extends Image
{
    CONST MAX_WIDTH = 120;
    CONST MAX_HEIGHT = 120;
    CONST MIN_WIDTH = 60;
    CONST MIN_HEIGHT = 60;
    protected $subdirectory = "images/avatars";
    protected $maxWidth = self::MAX_WIDTH;
    protected $maxHeight = self::MAX_HEIGHT;

    public function createCopies()
    {
        return array($this->getImage(), array());
    }
}

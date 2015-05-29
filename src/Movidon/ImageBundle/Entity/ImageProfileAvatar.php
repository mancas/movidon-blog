<?php
namespace Movidon\ImageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class ImageProfileAvatar extends ImageCopy
{
    protected $maxWidth = 120;
    protected $maxHeight = 120;
    protected $sufix = "avatar";
    protected $crop = true;
}
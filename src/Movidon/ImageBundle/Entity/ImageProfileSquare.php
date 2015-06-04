<?php
namespace Movidon\ImageBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class ImageProfileSquare extends ImageCopy
{
    protected $maxWidth = 400;
    protected $maxHeight = 400;
    protected $sufix = "square";
    protected $crop = true;
}
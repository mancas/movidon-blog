<?php
namespace Movidon\ImageBundle\Entity;
use Doctrine\Tests\DBAL\Types\IntegerTest;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class ImagePostCarousel extends ImageCopy
{
    protected $maxWidth = 750;
    protected $maxHeight = 400;
    protected $sufix = "postcarousel";
    protected $crop = false;
}
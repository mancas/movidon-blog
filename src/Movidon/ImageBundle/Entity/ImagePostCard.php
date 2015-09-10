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
class ImagePostCard extends ImageCopy
{
    protected $maxWidth = 600;
    protected $maxHeight = 531;
    protected $sufix = "postcard";
    protected $crop = false;
}
<?php

namespace Movidon\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class InternalMessage extends Message
{
    /**
     * @ORM\ManyToOne(targetEntity="Movidon\MessageBundle\Entity\Thread", inversedBy="messages")
     */
    protected $thread;

    /**
     * @return mixed
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param mixed $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }
}

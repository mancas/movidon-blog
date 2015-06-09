<?php

namespace Movidon\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"internal"="InternalMessage"})
 */
abstract class Message
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    protected $createDate;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="date", nullable=true)
     * @Assert\Date()
     */
    protected $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity="Movidon\BackendBundle\Entity\AdminUser")
     */
    protected $sender;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" = 1})
     */
    protected $unread = true;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param mixed $createDate
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @param mixed $updateDate
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getUnread()
    {
        return $this->unread;
    }

    /**
     * @param mixed $unread
     */
    public function setUnread($unread)
    {
        $this->unread = $unread;
    }

    public function isUnread()
    {
        return $this->unread;
    }
}
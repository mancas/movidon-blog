<?php

namespace Movidon\MessageBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table()
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class Thread
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
     * @ORM\OneToMany(targetEntity="Movidon\MessageBundle\Entity\InternalMessage", mappedBy="thread")
     */
    protected $messages;

    /**
     * @ORM\ManyToMany(targetEntity="Movidon\BackendBundle\Entity\AdminUser", inversedBy="threads")
     * @ORM\JoinTable(name="threads_users",
     *      joinColumns={@ORM\JoinColumn(name="thread_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    protected $participants;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

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
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param mixed $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    public function getParticipant($userId)
    {
        foreach ($this->participants as $participant) {
            if ($participant->getId() !== $userId) {
                return $participant;
            }
        }

        return null;
    }

    public function getLastMessage()
    {
        return $this->messages->last();
    }

    public function isUnread()
    {
        foreach ($this->messages as $message) {
            if ($message->isUnread()) {
                return true;
            }
        }

        return false;
    }
}
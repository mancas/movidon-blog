<?php
namespace Movidon\BackendBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table()
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity("username")
 * @UniqueEntity("username")
 */
class AdminUser implements UserInterface, \Serializable, EquatableInterface
{
    const AUTH_SALT = "R4y0d3Lu24b1sMa7";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = 6)
     * */
    protected $password;

    /**
     * @ORM\Column(name="salt", type="string", length=255)
     */
    protected $salt;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="registeredDate", type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $registeredDate;

    /**
     * @ORM\ManyToMany(targetEntity="Movidon\BlogBundle\Entity\Post", mappedBy="authors")
     */
    protected $posts;

    /**
     * @ORM\ManyToOne(targetEntity="Movidon\ImageBundle\Entity\ImageAvatar")
     */
    protected $avatar;

    /**
     * @ORM\OneToOne(targetEntity="Movidon\ImageBundle\Entity\ImageProfile", mappedBy="user")
     */
    protected $imageProfile;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="text")
     */
    protected $summary;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     * @Assert\Email();
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $twitter;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $website;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $degree;

    /**
     * @Gedmo\Slug(fields={"name", "lastName"}, updatable=true)
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="integer")
     */
    protected $visits;

    public function serialize()
    {
        return serialize(array($this->id, $this->password, $this->username));
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->password, $this->username) = unserialize(
            $serialized);
    }

    public function __toString()
    {

        return ucfirst($this->getUsername());
    }

    public function isEqualTo(
        \Symfony\Component\Security\Core\User\UserInterface $user)
    {
        return $this->getUsername() == $user->getUsername();
    }

    public function eraseCredentials()
    {
    }

    public function getRoles()
    {
        return array('ROLE_SUPER_ADMIN');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getRegisteredDate()
    {
        return $this->registeredDate;
    }

    public function getUsername()
    {
        return ucfirst($this->username);
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return ucfirst($this->name);
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return ucfirst($this->lastName);
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getImageProfile()
    {
        return $this->imageProfile;
    }

    /**
     * @param mixed $imageProfile
     */
    public function setImageProfile($imageProfile)
    {
        $this->imageProfile = $imageProfile;
    }

    /**
     * @return mixed
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * @param mixed $visits
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;
    }

    /**
     * @return mixed
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * @param mixed $degree
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
}
<?php
namespace Movidon\BackendBundle\Entity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Movidon\BackendBundle\Entity\AdminUserRepository")
 * @DoctrineAssert\UniqueEntity("username")
 * @UniqueEntity("username")
 */
class AdminUser implements AdvancedUserInterface, \Serializable, EquatableInterface
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
     * @ORM\OneToMany(targetEntity="Movidon\ImageBundle\Entity\ImageProfile", mappedBy="user", cascade={"persist", "remove", "merge"})
     */
    protected $imagesProfile;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $aboutMe;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $visits;

    /**
     * @ORM\ManyToMany(targetEntity="Movidon\BackendBundle\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    protected $roles;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" = 0})
     */
    protected $banned = false;

    public function __construct()
    {
        $this->imagesProfile = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
        if ($this->banned) {
            return array('ROLE_BANNED');
        }

        $roles = array();
        foreach ($this->roles as $role) {
            $roles[] = $role->getNameWithPrefix();
        }

        // Default role
        if (count($roles) === 0) {
            $roles[] = 'ROLE_SUPER_ADMIN';
        }

        return $roles;
    }

    public function getCustomRoles()
    {
        return $this->roles;
    }

    public function getRolesAsString()
    {
        $result = '';
        foreach ($this->roles as $role) {
            $result .= $role->getNameWithPrefix();
            if ($role !== $this->roles->last()) {
                $result .= ', ';
            }
        }

        // Default role
        if (strlen($result) === 0) {
            $result = 'ROLE_SUPER_ADMIN';
        }
        return $result;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
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
    public function getImagesProfile()
    {
        return $this->imagesProfile;
    }

    /**
     * @param mixed $imageProfile
     */
    public function setImagesProfile($imagesProfile)
    {
        $this->imagesProfile = $imagesProfile;
    }

    public function getImageProfile()
    {
        foreach ($this->imagesProfile as $image) {
            if ($image->getMain()) {
                return $image;
            }
        }

        return false;
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

    /**
     * @return mixed
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * @param mixed $aboutMe
     */
    public function setAboutMe($aboutMe)
    {
        $this->aboutMe = $aboutMe;
    }

    /**
     * @return mixed
     */
    public function getBanned()
    {
        return $this->banned;
    }

    /**
     * @param mixed $banned
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return !$this->banned;
    }
}
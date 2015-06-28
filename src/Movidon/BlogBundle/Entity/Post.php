<?php

namespace Movidon\BlogBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Movidon\BlogBundle\Entity\PostRepository")
 * @DoctrineAssert\UniqueEntity("id")
 * @UniqueEntity("id")
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $subtitle;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @ORM\Column(name="published", type="datetime", nullable=true)
     */
    protected $published;

    /**
     * @ORM\Column(name="deleted", type="date", nullable=true)
     * @Assert\Date()
     */
    protected $deleted;

    /**
     * @ORM\ManyToOne(targetEntity="Movidon\CategoryBundle\Entity\Category", inversedBy="posts")
     */
    protected $category;

    /**
     * @Gedmo\Slug(fields={"title"}, updatable=true)
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    protected $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Movidon\BlogBundle\Entity\Tag", inversedBy="posts")
     * @ORM\JoinTable(name="posts_tags",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;

    /**
     * @ORM\ManyToMany(targetEntity="Movidon\BackendBundle\Entity\AdminUser", inversedBy="posts")
     * @ORM\JoinTable(name="posts_authors",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="adminuser_id", referencedColumnName="id")}
     *      )
     */
    protected $authors;

    /**
     * @ORM\OneToMany(targetEntity="Movidon\ImageBundle\Entity\ImagePost", mappedBy="post", cascade={"persist", "remove", "merge"})
     */
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="Movidon\BlogBundle\Entity\Feedback", mappedBy="post", cascade={"persist", "remove", "merge"})
     */
    protected $feedbacks;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param mixed $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
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
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    public function getAuthorsAsString()
    {
        $result = '';
        foreach ($this->authors as $author) {
            $result .= $author->getUsername();
            if ($author !== $this->authors->last()) {
                $result .= ', ';
            }
        }

        return $result;
    }

    /**
     * @param mixed $authors
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;
    }

    public function addAuthor($author) {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }
    }

    public function removeAuthor($author)
    {
        if ($this->authors->contains($author)) {
            $this->authors->remove($author);
        }
    }

    public function containsAuthor($author) {
        return $this->authors->contains($author);
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        $images = array();
        foreach ($this->images as $image) {
            if ($image->getDeletedDate() == null)
                $images[] = $image;
        }

        return $images;
    }

    public function getImageMain()
    {
        foreach ($this->images as $image) {
            if ($image->getMain()) {
                return $image;
            }
        }

        return false;
    }

    public function addImage($image) {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }
    }

    public function removeImage($image)
    {
        if ($this->images->contains($image)) {
            $this->images->remove($image);
        }
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    /**
     * @param mixed $feedbacks
     */
    public function setFeedbacks($feedbacks)
    {
        $this->feedbacks = $feedbacks;
    }
    public function addFeedback($feedback) {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks->add($feedback);
        }
    }

    public function removeFeedback($feedback)
    {
        if ($this->feedbacks->contains($feedback)) {
            $this->feedbacks->remove($feedback);
        }
    }

    public function hasFeedback($author)
    {
        foreach ($this->feedbacks as $feedback) {
            if ($feedback->getAuthor() === $author) {
                return true;
            }
        }

        return false;
    }
}
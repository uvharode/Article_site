<?php

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
// use Doctrine\ODM\MongoDB\Mapping\OneToMany as ReferenceOne;


/**
 * @MongoDB\Document(collection="Articles")
 */
class Article
{

    /**
     * @MongoDB\Id
     */
    protected $id;
    /** @MongoDB\Field(type="string") */
    protected $title;
    /** @MongoDB\Field(type="string") */
    protected $author;
    /** @MongoDB\Field(type="string") */
    protected $content;
    /** @MongoDB\Field(type="string") */
    protected $image;
    /** @MongoDB\Field(type="string") */
    protected $imagename;

    
    /** @MongoDB\ReferenceOne(targetDocument=User::class, inversedBy="articles", storeAs="id") */
    private $user;

    // /** @MongoDB\ReferenceMany(targetDocument=Comment::class, mappedBy="article") */
    private $comments;
   
    /**
     * @return Collection|User[]
     */
    public function getUser()
    {
        return $this->user;
    } 
    public function setUser(User $user)
    {
        $user->addArticle($this);
        $this->user = $user;
    }

    // /**
    //  * @return Collection|Comment[]
    //  */
    public function getComments()
    {
        return $this->comments;
    } 
   
    // public function __construct()
    // {
    //     $this->comments = new ArrayCollection();
    // }

    // ---------------------------------------------------//

    public function getId()
    {
        return $this->id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
        return $this;
    }
    public function getAuthor()
    {
        return $this->author;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }
    public function getContent()
    {
        return $this->content;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
    public function getImage()
    {
        return $this->image;
    }

    public function setImageName($imagename)
    {
        $this->image = $imagename;
        return $this;
    }
    public function getImageName()
    {
        return $this->imagename;
    }

}
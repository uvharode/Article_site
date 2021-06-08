<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
// use Doctrine\ODM\MongoDB\Mapping\OneToMany as ReferenceMany;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MongoDB\Document(collection="Users") 
 * @MongoDBUnique(fields="email",message="Email is already register")
 * @MongoDBUnique(fields="username",message="Username is not available")
 */


class User
{

    /**
     * @MongoDB\Id
     */
    protected $id;
    /** @MongoDB\Field(type="string") */
    protected $username;
    /** @MongoDB\Field(type="string") */
    protected $email;
    /** @MongoDB\Field(type="string") */
    protected $plainPassword;
     /** @Assert\Length(max=4096) */
    protected $password;
    /** @MongoDB\Field(type="string") */
    protected $role;
    /** @MongoDB\Field(type="boolean") */
    protected $isActive;

    
    /** @MongoDB\ReferenceMany(targetDocument=Article::class, mappedBy="user") */
    private $articles;

    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
    }
    /**
     * @return Collection|Article[]
     */
    public function getArticles()
    {
        return $this->articles;
    }

    

    public function __construct()
    {
        $this->isActive = true;
        $this->articles = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getRole()
    {
        return $this->role;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }




}



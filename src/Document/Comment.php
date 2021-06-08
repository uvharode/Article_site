<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ManyToOne as MongoDBManyToOne;


/**
 * @MongoDB\Document(collection="comment")
 */
class Comment
{
    /**
     * @MongoDB\Id
     */
    protected $id;
    // /**
    //  * @MongoDB\ReferenceOne(targetDocument = Article::class, inversedBy=comment)
    //  */
    protected $article;
    /** @MongoDB\Field(type="string") */
    protected $commentData;
    // /**
    //  * @MongoDB\ReferenceOne(targetDocument = User::class, inversedBy=comment)
    //  */
    protected $user;

    public function getId()
    {
        return $this->id;
    }
    public function getCommentData()
    {
        return $this->commentData;
    }
    public function setCommentData($commentData)
    {
        $this->commentData = $commentData;
        return $this;
    }

    // /**
    //  * @return Collection|Article[]
    //  */
    public function getArticle()
    {
        return $this->article;
    } 
    public function setArticle(Article $article)
    {
        $this->article = $article;
        return $this;
    }
    // /**
    //  * @return Collection|User[]
    //  */
    public function getUser()
    {
        return $this->user;
    } 
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
}

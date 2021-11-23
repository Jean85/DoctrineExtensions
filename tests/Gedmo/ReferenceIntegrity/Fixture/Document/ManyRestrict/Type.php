<?php

namespace Gedmo\Tests\ReferenceIntegrity\Fixture\Document\ManyRestrict;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(collection="types")
 */
class Type
{
    /**
     * @ODM\ReferenceMany(targetDocument="Gedmo\Tests\ReferenceIntegrity\Fixture\Document\ManyRestrict\Article", mappedBy="type")
     * @Gedmo\ReferenceIntegrity("restrict")
     *
     * @var ArrayCollection
     */
    protected $articles = [];
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     */
    private $title;

    /**
     * @ODM\Field(type="string")
     */
    private $identifier;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Add articles
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
    }

    /**
     * Get posts
     *
     * @return ArrayCollection $articles
     */
    public function getArticles()
    {
        return $this->articles;
    }
}

<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @ORM\Table(name="Article")
 * 
 */
class Article
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;
       /**
     * @var string
     *
     * @ORM\Column(name="topic", type="string", length=100, nullable=false)
     */
    private $topic;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="filepath", type="string", length=512, nullable=false)
     */
    private $filepath = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
   
    public function getName(){
        return $this->name;
    }
/**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getTitle(){
        return $this->title;
    }
/**
     * Sets file.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function getTopic(){
        return $this->topic;
    }
/**
     * Sets topic.
     *
     * @param string $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }
    public function getFilepath(){
        return ($this->filepath)?$this->filepath:'';
    }
/**
     * Sets filepath.
     *
     * @param string $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }


}


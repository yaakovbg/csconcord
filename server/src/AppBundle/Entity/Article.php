<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Article
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @ORM\Table(name="Article")
 * 
 */
class Article {

    /**
     * @var string
     * @Serializer\Type("string")
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @Serializer\Type("string")
     * @ORM\Column(name="topic", type="string", length=100, nullable=false)
     */
    private $topic;

    /**
     * @var string
     *  @Serializer\Type("string")
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     * @Serializer\Type("string")
     * @ORM\Column(name="filepath", type="string", length=512, nullable=false)
     */
    private $filepath = '';

    /**
     * @var integer
     * 
     * @Serializer\Type("integer")
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * 
     * @Serializer\XmlList(inline=false, entry="articleWord")
     * @Serializer\Type("array<AppBundle\Entity\ArticleWord>")
     * @ORM\OneToMany(targetEntity="ArticleWord", mappedBy="article")
     */
    private $words;

    public function getId() {
       
        return $this->id;
    }

    public function __construct() {
        $this->description = '';
        $this->title = '';
        $this->topic = '';
        $this->filepath = '';
    }

    /**
     * Sets id.
     *
     * @param string $title
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getTopic() {
        return $this->topic;
    }

    /**
     * Sets topic.
     *
     * @param string $topic
     */
    public function setTopic($topic) {
        $this->topic = $topic;
    }

    public function getFilepath() {
        return ($this->filepath) ? $this->filepath : '';
    }

    /**
     * Sets filepath.
     *
     * @param string $filepath
     */
    public function setFilepath($filepath) {
        $this->filepath = $filepath;
    }
    public function getWords(){
        return $this->words;
    }
    public function setWords($inwords){
        $this->words=$inwords;
    }

    public function serilize() {
        $arr = array();
        foreach ($this as $k => $v) {
            $arr[$k] = $v;
        }
        return $arr;
    }

}

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
     * @Serializer\Groups({"Atom"})
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"Atom"})
     * @ORM\Column(name="topic", type="string", length=100, nullable=false)
     */
    private $topic;

    /**
     * @var string
     *  @Serializer\Type("string")
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({"Atom"})
     * @ORM\Column(name="filepath", type="string", length=512, nullable=false)
     */
    private $filepath = '';

    /**
     * @var integer
     * 
     * @Serializer\Type("integer")
     * @Serializer\Groups({"Atom"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * 
     * @Serializer\Groups({"List"})
     * @Serializer\XmlList(inline=false, entry="articleWord")
     * @Serializer\Type("array<AppBundle\Entity\ArticleWord>")
     * @ORM\OneToMany(targetEntity="ArticleWord", mappedBy="article")
     */
    private $words;

    /**
     * @Serializer\Groups({"List"})
     * @Serializer\XmlList(inline=false, entry="articleLetter")
     * @Serializer\Type("array<AppBundle\Entity\ArticleLetter>")
     * @ORM\OneToMany(targetEntity="ArticleLetter", mappedBy="article")
     */
    private $letters;

    /**
     * @Serializer\Groups({"List"})
     * @Serializer\XmlList(inline=false, entry="articleParagraph")
     * @Serializer\Type("array<AppBundle\Entity\ArticleParagraph>")
     * @ORM\OneToMany(targetEntity="ArticleParagraph", mappedBy="article")
     */
    private $paragraphs;

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

    public function getWords() {
        return $this->words;
    }

    public function getLetters() {
        return $this->letters;
    }

    public function getParagraphs() {
        return $this->paragraphs;
    }

    public function setWords($inwords) {
        $this->words = $inwords;
    }

    public function serilize() {
        $arr = array();
        foreach ($this as $k => $v) {
            $arr[$k] = $v;
        }
        return $arr;
    }

    /**
     * updates article words to be article new id
     * @param integer $id
     */
    public function updateArticleId($id) {
        $this->id = $id;
        foreach ($this->words as $k => $v) {
            $v->setArticleid($id);
        }
        foreach ($this->letters as $k => $v) {
            $v->setArticleid($id);
        }
        foreach ($this->paragraphs as $k => $v) {
            $v->setArticleid($id);
        }
    }

}

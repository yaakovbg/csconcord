<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
/**
 * ArticleWord
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleWordRepository")
 * @ORM\Table(name="articleword",uniqueConstraints={
 *      @ORM\UniqueConstraint(name="word_constraint", columns={"articleid","word", "position"})
 * })
 * 
 */
class ArticleWord {

    public function __construct($args = '') {
        if (gettype($args) == 'object') {
            foreach ($this as $k => $v) {
                if (isset($args->$k))
                    $this->$k = $args->$k;
            }
        }else {
            $this->word = '';
        }
    }

    /**
     * @var integer
     * @Serializer\Type("integer")
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     * @Serializer\Type("integer")
     * 
     * @ORM\Column(name="position", type="integer", length=13)
     */
    private $position;

    /**
     * @var integer
     * @Serializer\Type("integer")
     * 
     * @ORM\Column(name="wordPosition", type="integer", length=13)
     */
    private $wordPosition;

    /**
     * @var integer
     *  @Serializer\Type("integer")
     * @ORM\Column(name="articleid", type="integer", length=13)
     */
    private $articleid;

    /**
     * @var string
     * @Serializer\Type("string")
     * @ORM\Column(name="word", type="string", length=100, nullable=false)
     */
    private $word;

    /**
     * @var string
     * @Serializer\Type("string")
     * @ORM\Column(name="context", type="string", length=512, nullable=false)
     */
    private $context;
    /**
     * 
     * @Serializer\Type("array<AppBundle\Entity\Article>")
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="words")
     * @ORM\JoinColumn(name="articleid", referencedColumnName="id")
     */
    private $article;
    
    public function getId() {
        return $this->id;
    }

    /**
     * Sets id.
     *
     * @param string $title
     */
    public function setId($id) {
        $this->id = $id;
    }

    public function getArticleid() {
        return $this->articleid;
    }

    /**
     * Sets articleid.
     *
     * @param integer $id
     */
    public function setArticleid($id) {
        $this->articleid = $id;
    }

    /**
     * Sets word.
     *
     * @param string $word
     */
    public function setWord($word) {
        $this->word = $word;
    }

    public function getWord() {
        return $this->word;
    }

    /**
     * Sets word.
     *
     * @param string $word
     */
    public function setContext($context) {
        $this->context = $context;
    }

    public function getContext() {
        return $this->context;
    }

    /**
     * Sets position.
     *
     * @param integer $position
     */
    public function setPosition($position) {
        $this->position = $position;
    }

    public function getPosition() {
        return $this->position;
    }

    /**
     * Sets position.
     *
     * @param integer $position
     */
    public function setWordPosition($position) {
        $this->wordPosition = $position;
    }

    /**
     * 
     * @return Iteger word positionn in file by words
     */
    public function getWordPosition() {
        return $this->wordPosition;
    }

}

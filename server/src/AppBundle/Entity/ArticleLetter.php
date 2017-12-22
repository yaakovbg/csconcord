<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ArticleLetter
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleWordRepository")
 * @ORM\Table(name="articleletter",uniqueConstraints={
 *      @ORM\UniqueConstraint(name="letter_constraint", columns={"articleid","letter", "position"})
 * })
 * 
 */
class ArticleLetter {

    public function __construct($args = '') {
        if (gettype($args) == 'object') {
            foreach ($this as $k => $v) {
                if (isset($args->$k))
                    $this->$k = $args->$k;
            }
        }else {
            $this->letter = '';
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
     * @ORM\Column(name="position", type="integer", length=13)
     */
    private $position;

    /**
     * @var integer
     * @Serializer\Type("integer")
     * @ORM\Column(name="articleid", type="integer", length=13)
     */
    private $articleid;

    /**
     * @var string
     * @Serializer\Type("string")
     * @ORM\Column(name="letter", type="string", length=15, nullable=false)
     */
    private $letter;

    /**
     * 
     * @Serializer\Type("array<AppBundle\Entity\Article>")
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="letters")
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
    public function setWord($letter) {
        $this->letter = $letter;
    }

    public function getLetter() {
        return $this->letter;
    }

}

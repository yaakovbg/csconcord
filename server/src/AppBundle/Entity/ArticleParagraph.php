<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ArticleWord
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleWordRepository")
 * @ORM\Table(name="articleparagraph",uniqueConstraints={
 *      @ORM\UniqueConstraint(name="paragrph_constraint", columns={"articleid","beginning", "end"})
 * })
 * 
 */
class ArticleParagraph {

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
     * @ORM\Column(name="paragraphNumber", type="integer", length=13)
     */
    private $paragraphNumber;
    /**
     * @var integer
     * @Serializer\Type("integer")
     * @ORM\Column(name="beginning", type="integer", length=13)
     */
    private $beginning;

    /**
     * @var integer
     * @Serializer\Type("integer")
     * @ORM\Column(name="end", type="integer", length=13)
     */
    private $end;

    /**
     * @var integer
     * @Serializer\Type("integer")
     * @ORM\Column(name="articleid", type="integer", length=13)
     */
    private $articleid;

    /**
     * 
     * @Serializer\Type("array<AppBundle\Entity\Article>")
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="paragraphs")
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
     * @param Integer $pos
     */
    public function setBeginning($pos) {
        $this->beginning = $pos;
    }

    public function getBeginning() {
        return $this->beginning;
    }

    /**
     * Sets word.
     *
     * @param Integer $pos
     */
    public function setEnd($pos) {
        $this->end = $pos;
    }

    /**
     * @return Integer end position of paragraph
     */
    public function getEnd() {
        return $this->end;
    }
    /**
     * Sets word.
     *
     * @param Integer $pos
     */
    public function setParagraphNumber($pos) {
        $this->paragraphNumber = $pos;
    }

    /**
     * @return Integer end position of paragraph
     */
    public function getParagraphNumber() {
        return $this->paragraphNumber;
    }

}

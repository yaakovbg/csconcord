<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * WordGroup
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordGroupRepository")
 * @ORM\Table(name="wordGroup")
 * 
 */
class WordGroup {

    /**
     * @var string
     * @Serializer\Groups({"Atom"})
     * @Serializer\Type("string")
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var integer
     * @Serializer\Type("integer")
     *  @Serializer\Groups({"Atom"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @Serializer\XmlList(inline=false, entry="word")
     * @Serializer\Type("array<AppBundle\Entity\ArticleWordGroup>")
     * @ORM\OneToMany(targetEntity="ArticleWordGroup", mappedBy="wordGroup")
     */
    private $words;

    public function __construct($args = null) {
        $this->words = new ArrayCollection();
        if (($args !== null) && gettype($args) == "array") {
            if (isset($args['id']))
                $this->id = $args['id'];
            if (isset($args['name']))
                $this->name = $args['name'];
        }
    }

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

    public function getName() {
        return $this->name;
    }

    public function setName($name) {

        $this->name = $name;
    }

    public function addWord($word) {
        //  print_r('here2');
        return $this->words->add($word);
    }

    public function setWords($words) {
        //print_r('here1');
        if (is_array($words)) {
            foreach ($words as $k => $word) {
                $this->addWord($word);
            }
        }
    }

    /**
     * gets words
     *
     * @return ArrayCollection wordgroup words
     */
    public function getWords() {

        return $this->words;
    }

}

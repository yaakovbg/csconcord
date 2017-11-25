<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleWordGroup
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordGroupRepository")
 * @ORM\Table(name="articleWordGroup")
 * 
 */
class ArticleWordGroup
{
   
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
     /**
     * @var integer
     *
     * @ORM\Column(name="wgid", type="integer")
     */
    private $wgid;
    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=256, nullable=false)
     */
    private $word;
    
     /**
     * @ORM\ManyToOne(targetEntity="WordGroup", inversedBy="words")
     * @ORM\JoinColumn(name="wgid", referencedColumnName="id")
     */
    private $wordGroup;
            
    public function getId(){
        return $this->id;
    }
    /**
     * Sets id.
     *
     * @param string $title
     */
    public function setId($id)
    {
        $this->id = $id;
    }
        public function getWgId(){
        return $this->wgid;
    }
    /**
     * Sets wgid.
     *
     * @param Integer $id
     */
    public function setWgId($id)
    {
        $this->wgid = $id;
    }
    public function getWord(){
        return $this->word;
    }
    public function setWord($word)
    {
        $this->word = $word;
    }
     public function getWordGroup(){
        return $this->wordGroup;
    }
    public function setWordGroup($wordGroup)
    {
        $this->wordGroup = $wordGroup;
    }
}


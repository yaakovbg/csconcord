<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WordGroup
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordGroupRepository")
 * @ORM\Table(name="wordGroup")
 * 
 */
class WordGroup
{
   
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
     
    /**
     * @ORM\OneToMany(targetEntity="ArticleWordGroup", mappedBy="wordGroup")
     */
     private $words;
     
      public function __construct()
    {
        $this->words = new ArrayCollection();
    }
   
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
    public function getName(){
        return $this->name;
    }
    public function setName($name)
    {
      
        $this->name = $name;
    }
     public function addWord($word){
          print_r('here2');
        return $this->words->add($word);
    }
    public function setWords($words)
    {
       print_r('here1');
        if(is_array($words)){
            foreach($words as $k=>$word){
                $this->addWord($word);
            }
        }
    }
    public function getWords()
    {
        
        return $this->words;
    }
}


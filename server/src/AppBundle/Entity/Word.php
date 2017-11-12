<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRepository")
 * @ORM\Table(name="Word")
 * 
 */
class Word
{
    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=100, nullable=false)
     */
    private $word;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
     public function __construct() {
        $this->word='';
       
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
    /**
     * Sets word.
     *
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }
    public function getWord(){
        return $this->word;
    }
    

}


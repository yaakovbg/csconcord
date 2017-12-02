<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WordRelation
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRelationRepository")
 * @ORM\Table(name="relation")
 * 
 */
class Relation
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
     * @ORM\OneToMany(targetEntity="WordRelation", mappedBy="relation")
     */
     private $tuples ;
     
      public function __construct($args=null)
    {
        $this->words = new ArrayCollection();
        if(($args!==null) && gettype($args) == "array"){
            if(isset($args['id'])) $this->id=$args['id'];
            if(isset($args['name'])) $this->name=$args['name'];
        }
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
        //  print_r('here2');
        return $this->words->add($word);
    }
    public function setTuples($tuples)
    {
       //print_r('here1');
        if(is_array($tuples)){
            foreach($tuples as $k=>$word){
                $this->addWord($word);
            }
        }
    }
    /**
     * gets words
     *
     * @return ArrayCollection wordgroup words
     */
    public function getTuples()
    {
        
        return $this->words;
    }
}


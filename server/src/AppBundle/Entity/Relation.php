<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

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
     * @Serializer\Type("string")
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;
    /**
     * @var integer
     * @Serializer\Type("integer")
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
     
    /**
     * @Serializer\XmlList(inline=false, entry="tuple")
     * @Serializer\Type("array<AppBundle\Entity\WordRelation>")
     * @ORM\OneToMany(targetEntity="WordRelation", mappedBy="relation")
     */
     private $tuples ;
     
      public function __construct($args=null)
    {
        $this->tuples = new ArrayCollection();
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
     public function addTuple($tuple){
        //  print_r('here2');
        return $this->tuples->add($tuple);
    }
    public function setTuples($tuples)
    {
       //print_r('here1');
        if(is_array($tuples)){
            foreach($tuples as $k=>$tuple){
                $this->addTuple($tuple);
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
        
        return $this->tuples;
    }
}


<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;

/**
 * WordRelation
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRelationRepository")
 * @ORM\Table(name="wordRelation")
 * 
 */
class WordRelation
{
   
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Type("integer")
     */
    private $id;
     /**
     * @var integer
     *
     * @ORM\Column(name="rid", type="integer")
     * @Type("integer")
     */
    private $rid;
    /**
     * @var string
     *
     * @ORM\Column(name="word_a", type="string", length=256, nullable=false)
     * @Type("string")
     */
    private $word_a;
    /**
     * @var string
     *
     * @ORM\Column(name="word_b", type="string", length=256, nullable=false)
     * @Type("string")
     */
    private $word_b; 
     /**
     * @ORM\ManyToOne(targetEntity="Relation", inversedBy="tuples")
     * @ORM\JoinColumn(name="rid", referencedColumnName="id")
     */
    private $relation;
            
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
        public function getRid(){
        return $this->rid;
    }
    /**
     * Sets rid.
     *
     * @param Integer $id
     */
    public function setRid($id)
    {
        $this->rid = $id;
    }
    public function getWord(){
        return $this->word;
    }
    public function setWord($word)
    {
        $this->word = $word;
    }
     public function getRelation(){
        return $this->relation;
    }
    public function setRelation($relation)
    {
        $this->relation = $relation;
    }
}


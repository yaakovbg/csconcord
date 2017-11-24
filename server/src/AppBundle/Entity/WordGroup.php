<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
        return $this->title;
    }
    public function setName($title)
    {
        $this->title = $title;
    }
}


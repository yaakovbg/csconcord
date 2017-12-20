<?php

namespace AppBundle\Classes;

use JMS\Serializer\Annotation as Serializer;
use AppBundle\Entity\Article;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * GlobalHolder
 *
 * holds site data 
 * 
 */
class GlobalHolder {
    /**
     * @var article[]
     *
     * 
     *
     * @Serializer\XmlList(inline=false, entry="artilce")
     * @Serializer\Type("array<AppBundle\Entity\Article>")
     */
    public $articles;
    /**
     * @var wordGroup[]
     *
     * 
     *
     * @Serializer\XmlList(inline=false, entry="wordGroup")
     * @Serializer\Type("array<AppBundle\Entity\WordGroup>")
     */
    public $wordGroups;
    /**
     * @var relation[]
     *
     * 
     *
     * @Serializer\XmlList(inline=false, entry="relation")
     * @Serializer\Type("array<AppBundle\Entity\Relation>")
     */
    public $relations;
}



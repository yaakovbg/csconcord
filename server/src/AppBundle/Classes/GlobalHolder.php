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
     * @Serializer\XmlList(inline=false, entry="article")
     * @Serializer\Type("array<AppBundle\Entity\Article>")
     */
    public $articles;

    /**
     * @var wordGroup[]
     * @Serializer\XmlList(inline=false, entry="wordGroup")
     * @Serializer\Type("array<AppBundle\Entity\WordGroup>")
     */
    public $wordGroups;

    /**
     * @var relation[]
     * @Serializer\XmlList(inline=false, entry="relation")
     * @Serializer\Type("array<AppBundle\Entity\Relation>")
     */
    public $relations;
    
 
    public function removeIds() {
        foreach ($this->articles as $k => $v) {
            $words = $v->getWords();
            foreach ($words as $kw => $vw) {
                $vw->setArticleid(null);
                $vw->setId(null);
            }
            $letters = $v->getLetters();
            foreach ($letters as $kw => $vw) {
                $vw->setArticleid(null);
                $vw->setId(null);
            }
            $paragraphs = $v->getParagraphs();
            foreach ($paragraphs as $kw => $vw) {
                $vw->setArticleid(null);
                $vw->setId(null);
            }
            $v->setId(null);
        }
        foreach ($this->wordGroups as $k => $v) {
            $words = $v->getWords();
            foreach ($words as $kw => $vw) {
                $vw->setWgid(null);
                $vw->setId(null);
            }
            $v->setId(null);
        }
        foreach ($this->relations as $k => $v) {
            $words = $v->getTuples();
            foreach ($words as $kw => $vw) {
                $vw->setRid(null);
                $vw->setId(null);
            }
            $v->setId(null);
        }
    }

}

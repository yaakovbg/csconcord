<?php

namespace AppBundle\Service;

use \AppBundle\Entity\Article;
use \AppBundle\Entity\ArticleWord;
use \AppBundle\Entity\ArticleLetter;
use \AppBundle\Entity\ArticleParagraph;
use \Symfony\Component\DependencyInjection\ContainerAware;
use \stdClass;

class FileAnalyzer {

    private $container;
    private $filePath;
    private $articleId;
    private $originalContent;
    private $manipulatedContent;
    private $numOfWords;
    private $words;
    private $letters;
    private $paragraphs;
    private $wordsData;
    private $lettersData;
    private $paragraphsData;
    private $wordMap;

    public function __construct($container) {
        $this->container = $container;
    }

    public function analyze($articleId) {
        $this->articleId = $articleId;
        $articlerepo = $this->container->get('doctrine')->getRepository(Article::class);
        $article = $articlerepo->getArticleById($articleId);
        $filesDir = $this->container->getParameter('files_directory');

        $this->filePath = $filesDir . '/' . $article->getFilepath();

        $this->originalContent = file_get_contents($this->filePath);

        $this->manipulatedContent = preg_replace("/[^[:alnum:][:space:]]/u", " ", $this->originalContent);
        $this->manipulatedContent = str_replace("\r\n\r\n", " ", $this->manipulatedContent);
        $this->manipulatedContent = str_replace("\n", " ", $this->manipulatedContent);
        $this->words = explode(" ", $this->manipulatedContent);
        $this->letters = str_split($this->originalContent);

        $this->wordMap = new stdClass();
        $this->letterMap = new stdClass();
        $this->wordsData = array();
        $this->paragraphsData = array();
        
        $this->mapParagrphs();      
        $this->mapWords();
        $this->mapLetters();
        
        return $this->save();
    }
    private function  mapWords() {
        foreach ($this->paragraphs as $k => $paragraph) {
            $paragraphsData = $this->paragraphsData[$k];
            $manipulatedContent = preg_replace("/[^[:alnum:][:space:]]/u", " ", $paragraph);
            $manipulatedContent = str_replace("\r\n\r\n", " ", $manipulatedContent);
            $manipulatedContent = str_replace("\n", " ", $manipulatedContent);
            $wordsArr = explode(" ", $manipulatedContent);
            foreach($wordsArr as $wordPos=>$word){
                
            }
        }
    }
//    private function mapWords() {
//        foreach ($this->words as $k => $word) {
//            if ($word !== "") {
//                if (!(isset($this->wordMap->{$word}))) {
//                    $wordPosition = array($k);
//                    $this->wordMap->{$word} = (object) array('count' => 1, 'wordPosition' => $wordPosition);
//                } else {
//                    $this->wordMap->{$word}->wordPosition[] = $k;
//                    $this->wordMap->{$word}->count++;
//                }
//            }
//        }
//        foreach ($this->wordMap as $k => $v) {
//            $lastpos = 0;
//            for ($i = 0; $i < $v->count; $i++) {
//                $wordPosition = $v->wordPosition[$i];
//                $newpos = strpos($this->originalContent, $k, $lastpos);
//                $lastpos = $newpos + strlen($k);
//                if (($newpos - 75) > 0) {
//                    $wordContext = substr($this->originalContent, $newpos - 75);
//                    $spacepos = strpos($wordContext, " ");
//                } else {
//                    $wordContext = $this->originalContent;
//                    $spacepos = -1;
//                }
//
//                $wordContext = substr($wordContext, $spacepos + 1);
//                if (150 < strlen($wordContext))
//                    $wordContext = substr($wordContext, 0, 150);
//                $wordContext = str_replace($k, '<b>' . $k . '</b>', $wordContext);
//                $artilceWord = new ArticleWord((object) array('word' => $k, 'position' => $newpos, 'context' => $wordContext, 'wordPosition' => $wordPosition, 'articleid' => $this->articleId));
//                $this->wordsData[] = $artilceWord;
//            }
//        }
//    }

    function mapLetters() {
        foreach ($this->letters as $k => $letter) {
            $artilceLetter = new ArticleLetter((object) array('letter' => $letter, 'position' => $k, 'articleid' => $this->articleId));
            $this->lettersData[] = $artilceLetter;
        }
    }

    function mapParagrphs() {
        $this->paragraphs = explode("\r\n\r\n", $this->originalContent);
        $start = 0;
        $count = 1;
        foreach ($this->paragraphs as $k => $paragraph) {
            $artilceLetter = new ArticleParagraph((object) array('beginning' => $start, 'end' => $start + strlen($paragraph), 'articleid' => $this->articleId, 'paragraphNumber' => $count));
            $this->paragraphsData[] = $artilceLetter;
            $start = $start + strlen($paragraph) + strlen("\r\n\r\n");
            $count++;
        }
    }

    function save() {
        $articleWordrepo = $this->container->get('doctrine')->getRepository(ArticleWord::class);
        $res1 = $articleWordrepo->saveArticleWords($this->wordsData);
        $res2 = $articleWordrepo->saveArticleLetters($this->lettersData);
        $res3 = $articleWordrepo->saveArticleParagraphs($this->paragraphsData);
        return $res1 && $res2 && $res3;
    }

    function getWordsData() {
        return $this->wordsData;
    }

}

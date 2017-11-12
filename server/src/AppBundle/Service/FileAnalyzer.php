<?php
namespace AppBundle\Service;


class FileAnalyzer
{
    private $filePath;
    private $originalContent;
    private $manipulatedContent;
    private $numOfWords;
    private $words;
    private $wordMap;
    
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
       
        $this->originalContent = file_get_contents($this->filePath);
        $this->manipulatedContent=preg_replace("/[^A-Za-z0-9 ]/", '', $this->originalContent);
        $this->manipulatedContent=preg_replace("/[^[:alnum:][:space:]]/u", '', $this->originalContent);
        $this->words=explode(" ",$this->manipulatedContent);
        $this->wordMap=new stdClass();
       // print_r($this->words);
//        $this->mapWords();
    }
    private function mapWords(){
        foreach( $this->words as $k=>$word){
            if(!(isset($this->wordMap->{$word}))){
                $this->wordMap->{$word}=(object)array();
            }
        }
    }

}
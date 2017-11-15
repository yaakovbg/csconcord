<?php
namespace AppBundle\Service;

use \Symfony\Component\DependencyInjection\ContainerAware;
use \stdClass;

class FileAnalyzer
{
    private $container;
    private $filePath;
    private $originalContent;
    private $manipulatedContent;
    private $numOfWords;
    private $words;
    private $wordsData;
    private $wordMap;
    
    public function __construct($targetDir)
    {
        //$this->container=$container;
    }
    public function analyze($filePath)
    {
        $this->filePath = $filePath;
       
        $this->originalContent = file_get_contents($this->filePath);
       
        $this->manipulatedContent=preg_replace("/[^[:alnum:][:space:]]/u", '', $this->originalContent);
        $this->words=explode(" ",$this->manipulatedContent);
        $this->wordMap=new stdClass();
        $this->wordsData=array();
       // print_r($this->words);
        $this->mapWords();
        //print_r($this->wordsData);
    }
    private function mapWords(){
        foreach( $this->words as $k=>$word){
            if(!(isset($this->wordMap->{$word}))){
                $this->wordMap->{$word}=(object)array('count'=>1);
            }
            else{
                $this->wordMap->{$word}->count++;
            } 
        }
        foreach($this->wordMap as $k=>$v){
            $lastpos=0;
            for($i=0;$i<$v->count;$i++){
                $newpos=mb_strpos($this->originalContent, $k,$lastpos);
                $lastpos=$newpos+mb_strlen($k);
                if(($newpos-75)>0){
                    $wordContext=mb_substr($this->originalContent,$newpos-75);
                    $spacepos=mb_strpos($wordContext," ");
                }
                else{
                    $wordContext=$this->originalContent;
                    $spacepos=-1;
                }
                    
                $wordContext=mb_substr($wordContext,$spacepos+1);
                if(150<strlen($wordContext))
                    $wordContext=mb_substr($wordContext,0,150);
                $wordContext = str_replace($k, '<b>'.$k.'</b>', $wordContext);
                $this->wordsData[]=(object)array('word'=>$k,'pos'=>$newpos,'context'=>$wordContext);
            }
        }
    }
    function getWordsData(){
        return $this->wordsData;
    }

}
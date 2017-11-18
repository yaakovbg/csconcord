<?php
namespace AppBundle\Service;

use \AppBundle\Entity\Article;
use \AppBundle\Entity\ArticleWord;
use \Symfony\Component\DependencyInjection\ContainerAware;
use \stdClass;

class FileAnalyzer
{     
    private $container;
    private $filePath;
    private $articleId;
    private $originalContent;
    private $manipulatedContent;
    private $numOfWords;
    private $words;
    private $wordsData;
    private $wordMap;
    
    public function __construct($container)
    {
        $this->container=$container;
    }
    public function analyze($articleId)
    {
        $this->articleId=$articleId;
        $articlerepo= $this->container->get('doctrine')->getRepository(Article::class);
        $article=$articlerepo->getArticleById($articleId);
        $filesDir=$this->container->getParameter('files_directory');  
       
        $this->filePath=$filesDir.'/'.$article->getFilepath();
       
        $this->originalContent = file_get_contents($this->filePath);
       
        $this->manipulatedContent=preg_replace("/[^[:alnum:][:space:]]/u", '', $this->originalContent);
        $this->words=explode(" ",$this->manipulatedContent);
        $this->wordMap=new stdClass();
        $this->wordsData=array();
        $this->mapWords();
        return $this->save();
        
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
                $artilceWord=new ArticleWord((object)array('word'=>$k,'position'=>$newpos,'context'=>$wordContext,'articleid'=> $this->articleId));
                $this->wordsData[]=$artilceWord;
            }
        }
    }
    function save(){
        $articleWordrepo= $this->container->get('doctrine')->getRepository(ArticleWord::class);
        return $articleWordrepo->saveArticleWords($this->wordsData);
    }
    function getWordsData(){
        return $this->wordsData;
    }

}
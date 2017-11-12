<?php
/**
 *
 * User: yaakov
 * Date: 15/10/17
 * Time: 09:44
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;
use AppBundle\Entity\File;
use AppBundle\Entity\Article;
use AppBundle\Services\Entity\Article\ArticleGroup;

class ArticleRepository extends BaseRepository {
    public static $articleCache = array();

     /**
     * @param $fileId
     * @return File
     */
    public function saveArticle(Article $article) {
     $arr = $this->serializeArr($article);
     $arrParams=$this->serializeArrParams($article);

     if(isset($arr['id'])){//update
         
        $q = $this->_em->getConnection()->createQueryBuilder();
        $q->update('article');
        foreach($arr as $k=>$v){
            if($k!='id')
                $q->set($k, ':'.$k);
        }
        
        $q->where('id=:id');
        $q->setParameters($arrParams);
       $res= $q->execute();

     }
     else//new insert
      $res=$this->smartQuery(array(
          'sql' => "insert into `article`(title,topic,filepath) values (:title,:topic,:filepath)",
                    'par' => $arr,
                    'ret' => 'result'
      ));
    
    

return $res;
    }
    
     /**
     * @param $articleId
     * @return File
     */
    public function deleteArticleById($articleId) {
       $q = $this->_em->getConnection()->createQueryBuilder();
       $q->delete('article')->where('article.id=:aid')->setParameter('aid', $articleId);
      $res= $q->execute();
      // $q->get
        return $res;
    }
    /**
     * @param $articleId
     * @return Article
     */
    public function getArticleById($articleId) {
       
        $articleResult = $this->findOneBy(array('id' => $articleId));
        return $articleResult;
    }
     /**
     * 
     * @return File
     */
    public function test() {
        
        
      

         $results = $this->findAll();
        // $articleResult = current($results);
        // $article = $this->deserializeArr($articleResult, Article::class);
        // // cache the result
        // self::$articleCache[$articleId] = $article;
        return $results;
    }

}
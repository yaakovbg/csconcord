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

class FileRepository extends BaseRepository {
    public static $articleCache = array();

    /**
     * @param $fileId
     * @return File
     */
    public function getArticleById($fileId) {
//        if(isset(self::$articleCache[$fileId])) {
//            return self::$articleCache[$fileId];
//        }
//        //$article = $this->findOneBy(array('articleid' => $articleId));
//        $sql = "
//            SELECT 
//                *
//            FROM
//                `file`
//            WHERE
//                `fid` = :fileId
//            LIMIT 1
//        ";
//        $results = $this->fetchAssoc($sql, array(":fileId" => $fileId));
//        if(count($results) === 0) {
//            return NULL;
//        }
//        $articleResult = current($results);
//        $article = $this->deserializeArr($articleResult, File::class);
//        // cache the result
//        self::$articleCache[$articleId] = $article;
       // return self::$articleCache[$articleId];
        return 'test';
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
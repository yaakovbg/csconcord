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
use AppBundle\Entity\ArticleWord;
use AppBundle\Services\Entity\Article\ArticleGroup;

class ArticleRepository extends BaseRepository {

    public static $articleCache = array();

    /**
     * @param $fileId
     * @return File
     */
    public function saveArticle(Article $article) {
        $arr = $this->serializeArr($article, 'Atom');

        $arrParams = $this->serializeArrParams($article, 'Atom');

        if (isset($arr['id'])) {//update
            $q = $this->_em->getConnection()->createQueryBuilder();
            $q->update('article');
            foreach ($arr as $k => $v) {
                if ($k != 'id')
                    $q->set($k, ':' . $k);
            }

            $q->where('id=:id');
            $q->setParameters($arrParams);
            $res = $q->execute();
        } else {//new insert
            $res = $this->smartQuery(array(
                'sql' => "insert into `article`(title,topic,filepath) values (:title,:topic,:filepath)",
                'par' => $arr,
                'ret' => 'result'
            ));
        }//new insert




        return $res;
    }

    /**
     * @param WordGroup $wordGroup
     * @return Boolen
     */
    public function importSaveArticle(Article $article) {
        $arr = $this->serializeArr($article);

        $this->_em->getConnection()->beginTransaction();

        try {
            if (isset($arr['id'])) {//update
                $aid = $arr['id'];
            } else {//insert
                $this->saveArticle($article);
                $aid = $this->_em->getConnection()->lastInsertId();
                $article->updateArticleId($aid);
            }
            $wordRepo = $this->getEntityManager()->getRepository(ArticleWord::class);
            $res1 = $wordRepo->saveArticleWords($article->getWords());
            $res2 = $wordRepo->saveArticleLetters($article->getLetters());
            $res3 = $wordRepo->saveArticleParagraphs($article->getParagraphs());
            $res = $res1 && $res2 && $res3;
            $this->_em->getConnection()->commit();
        } catch (Exception $e) {
            //An exception has occured, which means that one of our database queries
            //failed.
            //Print out the error message.
            echo $e->getMessage();
            //Rollback the transaction.
            $this->_em->getConnection()->rollBack();
            $res = $e;
        }
        return $res;
    }

    /**
     * 
     * @return array<Article>
     */
    public function findAllWithOutWords() {
        $articles = $this->smartQuery(array(
            'sql' => "select * from article",
            'par' => array(),
            'ret' => 'all'
        ));
        $res = $this->deserializeArrs($articles, Article::class);
        
        return $res;
    }

    /**
     * @param $articleId
     * @return Boolean
     */
    public function deleteArticleById($articleId) {
        $q = $this->_em->getConnection()->createQueryBuilder();
        $q->delete('article')->where('article.id=:aid')->setParameter('aid', $articleId);
        $res = $q->execute();
        
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

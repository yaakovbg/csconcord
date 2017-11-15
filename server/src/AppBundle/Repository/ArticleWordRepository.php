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
use AppBundle\Entity\ArticleWord;


class ArticleWordRepository extends BaseRepository {
    

     /**
     * @param $articlewords
     * @return Boolen
     */
    public function saveArticleWords($articlewords,$articleid) {
        $arr=array();
        foreach($articlewords as $k=>$v){
            $line=array($articleid,$v->word,$v->pos,$v->context);
            $arr[]=$line;
        }
    // print_r($arr);
//     $arrParams=$this->serializeArrParams($article);
//
//     if(isset($arr['id'])){//update
//         
//        $q = $this->_em->getConnection()->createQueryBuilder();
//        $q->update('articleword');
//        foreach($arr as $k=>$v){
//            if($k!='id')
//                $q->set($k, ':'.$k);
//        }
//        
//        $q->where('id=:id');
//        $q->setParameters($arrParams);
//       $res= $q->execute();
//
//     }
//     else//new insert
    // print_r(gettype($arr));
     print_r(implode(',',$arr));
//      $res=$this->smartQuery(array(
//          'sql' => "insert into `articleword`(articleid,word,position,context) values :vals",
//                    'par' => array('vals'=>$arr),
//                    'ret' => 'result'
//      ));
    
    

return $res;
    }
    
   
    

}
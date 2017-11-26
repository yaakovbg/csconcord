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
use AppBundle\Entity\ArticleWordGroup;
use AppBundle\Entity\WordGroup;

class WordGroupRepository extends BaseRepository {

    /**
     * @param WordGroup $wordGroup
     * @return Boolen
     */
    public function save(WordGroup $wordGroup) {
        $arr = $this->serializeArr($wordGroup);
        if (isset($arr['id'])) {//update
        } else {
            $this->_em->getConnection()->beginTransaction();
            try{
                $params = array();
                $sqlArr='';
               
                $res = $this->smartQuery(array(
                    'sql' => "insert into `wordgroup`(name) values (:wgname);",      
                    'par' => array('wgname'=>$wordGroup->getName()),
                    'ret' => 'result'
                ));
                $wgid=$this->_em->getConnection()->lastInsertId();
                foreach($wordGroup->getWords() as $k=>$v){
                    $params[]=$v;
                    $sqlArr.="($wgid,?)";
                }
                echo  "insert into `articlewordgroup`(wgid,word) values $sqlArr;";
                print_r($params);
               $res = $this->executeStmt("insert into `articlewordgroup`(wgid,word) values ($sqlArr);", $params);
//                 $res = $this->smartQuery(array(
//                    'sql' => "insert into `articlewordgroup`(wgid,word) values $sqlArr;",      
//                    'par' => $params,
//                    'ret' => 'result'
//                ));
                 $this->_em->getConnection()->commit();
            }catch(Exception $e){
                //An exception has occured, which means that one of our database queries
                //failed.
                //Print out the error message.
                echo $e->getMessage();
                //Rollback the transaction.
                $this->_em->getConnection()->rollBack();
                $res=$e;
            }
            
        }


        return $res;
    }

    public function getArticleWords($params) {
        $paramArr = array();
        $search = (isset($params->search) && $params->search != '') ? $params->search : false;
        $page = (isset($params->page) && $params->page != '') ? $params->page : false;
        $numperpage = (isset($params->numPerPage) && $params->numPerPage != '') ? $params->numPerPage : false;
        $where = '';
        $limit = '';
        if ($search !== false) {
            $where = "where word like '%:search%'";
            $paramArr['search'] = $search;
        }
        if ($page !== false && $numperpage !== false) {
            $start = ($page - 1) * $numperpage;
            $limit = "limit $start,$numperpage";
        }

        $res = $this->smartQuery(array(
            'sql' => "select * from `articleword` $where $limit",
            'par' => $paramArr,
            'ret' => 'all'
        ));
        $count = $this->smartQuery(array(
            'sql' => "select count(*) as total_rows from `articleword` $where",
            'par' => $paramArr,
            'ret' => 'fetch-assoc'
        ));
        $totalRows = $count['total_rows'];
        $numOfPages = floor($totalRows / $numperpage);
        $ret = array('rows' => $res, 'total_rows' => $totalRows, 'numberOfPages' => $numOfPages);
        return $ret;
    }

}

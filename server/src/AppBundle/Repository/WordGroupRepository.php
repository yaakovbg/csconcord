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

class WordGroupRepository extends BaseRepository {

    /**
     * @param WordGroup $wordGroup
     * @return Boolen
     */
    public function save($wordGroup) {
       $res='';


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
        $totalRows=$count['total_rows'];
        $numOfPages=floor ($totalRows/$numperpage);
        $ret=array('rows'=>$res,'total_rows'=>$totalRows,'numberOfPages'=>$numOfPages);
        return $ret;
    }

}

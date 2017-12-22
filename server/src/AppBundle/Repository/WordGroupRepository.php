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
use Doctrine\Common\Collections\ArrayCollection;

class WordGroupRepository extends BaseRepository {

    /**
     * returns all word groups
     * @return array all word groups
     */
    public function getAllWordGroups() {
        $wordgroups = $this->smartQuery(array(
            'sql' => "select * from `wordgroup`",
            'par' => array(),
            'ret' => 'all'
        ));
        $words = $this->smartQuery(array(
            'sql' => "select * from `articlewordgroup`",
            'par' => array(),
            'ret' => 'all'
        ));
        $wgMap = array();
        $wgArray = new ArrayCollection;
        foreach ($wordgroups as $k => $v) {
            $wg = new WordGroup($v);
            $wgMap[$wg->getId()] = $wg;
            $wgArray->add($wg);
        }
        foreach ($words as $k => $v) {
            $awg = $this->deserializeArr($v, ArticleWordGroup::class);
            $wgMap[$v['wgid']]->addWord($awg);
        }

        return $wgArray;
    }

    /**
     *      deletes given word group
     * @param Integer $wgid
     * @return Boolen
     */
    public function delete(WordGroup $wordGroup) {
        $arr = $this->serializeArr($wordGroup);
        $this->_em->getConnection()->beginTransaction();
        try {
            $wgid = $arr['id'];
            $params = array();
            $sqlArr = '';
            $res = $this->smartQuery(array(
                'sql' => "delete from `articlewordgroup` where wgid=:wgid",
                'par' => array('wgid' => $wgid),
                'ret' => 'result'
            ));
            $res = $this->smartQuery(array(
                'sql' => "delete from `wordgroup` where id=:wgid",
                'par' => array('wgid' => $wgid),
                'ret' => 'result'
            ));
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
     * @param WordGroup $wordGroup
     * @return Boolen
     */
    public function save(WordGroup $wordGroup) {
        $arr = $this->serializeArr($wordGroup, 'Atom');
        $this->_em->getConnection()->beginTransaction();

        try {
            if (isset($arr['id'])) {//update
                $wgid = $arr['id'];
            } else {//insert
                $res = $this->smartQuery(array(
                    'sql' => "insert into `wordgroup`(name) values (:wgname);",
                    'par' => array('wgname' => $wordGroup->getName()),
                    'ret' => 'result'
                ));
                $wgid = $this->_em->getConnection()->lastInsertId();
            }

            $params = array();
            $sqlArr = '';
            $res = $this->smartQuery(array(
                'sql' => "delete from `articlewordgroup` where wgid=:wgid",
                'par' => array('wgid' => $wgid),
                'ret' => 'result'
            ));
          
            foreach ($wordGroup->getWords() as $k => $v) {
                $v = $this->serializeArr($v);
                if (isset($v['word'])) {
                    $params[] = $v['word'];
                    $sqlArr .= ($sqlArr !== '') ? ',' : '';
                    $sqlArr .= "($wgid,?)";
                }
            }
            $res = $this->executeStmt("insert into `articlewordgroup`(wgid,word) values $sqlArr ON DUPLICATE KEY UPDATE wgid= VALUES(wgid),word=VALUES(word);", $params);
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

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
    public function saveArticleWords($articlewords) {
        $dataVals = $this->serializeArr($articlewords);

        $dataToInsert = array();
        $colNames = array('position', 'wordPosition', 'articleid', 'word', 'context');
        foreach ($dataVals as $row => $data) {
            foreach ($data as $val) {
                $dataToInsert[] = $val;
            }
        }
        $updateCols = array();

        foreach ($colNames as $curCol) {
            $updateCols[] = $curCol . " = VALUES($curCol)";
        }

        $onDup = implode(', ', $updateCols);
        // setup the placeholders - a fancy way to make the long "(?, ?, ?)..." string
        $rowPlaces = '(' . implode(', ', array_fill(0, count($colNames), '?')) . ')';
        $allPlaces = implode(', ', array_fill(0, count($dataVals), $rowPlaces));
        $sql = "INSERT INTO `articleword` (" . implode(', ', $colNames) .
                ") VALUES " . $allPlaces . " ON DUPLICATE KEY UPDATE $onDup";



        $res = $this->executeStmt($sql, $dataToInsert);


        return $res;
    }

    /**
     * @param $articlewords
     * @return Boolen
     */
    public function saveArticleLetters($articleletters) {
        $dataVals = $this->serializeArr($articleletters);

        $dataToInsert = array();
        $colNames = array('position', 'articleid', 'letter');
        foreach ($dataVals as $row => $data) {
            foreach ($data as $val) {
                $dataToInsert[] = $val;
            }
        }
        $updateCols = array();

        foreach ($colNames as $curCol) {
            $updateCols[] = $curCol . " = VALUES($curCol)";
        }

        $onDup = implode(', ', $updateCols);
        // setup the placeholders - a fancy way to make the long "(?, ?, ?)..." string
        $rowPlaces = '(' . implode(', ', array_fill(0, count($colNames), '?')) . ')';
        $allPlaces = implode(', ', array_fill(0, count($dataVals), $rowPlaces));
        $sql = "INSERT INTO `articleletter` (" . implode(', ', $colNames) .
                ") VALUES " . $allPlaces . " ON DUPLICATE KEY UPDATE $onDup";



        $res = $this->executeStmt($sql, $dataToInsert);
        return $res;
    }

    /**
     * @param $articleparagraphs
     * @return Boolen
     */
    public function saveArticleParagraphs($articleparagraphs) {
        $dataVals = $this->serializeArr($articleparagraphs);

        $dataToInsert = array();
        $colNames = array('beginning', 'end', 'articleid');
        foreach ($dataVals as $row => $data) {
            foreach ($data as $val) {
                $dataToInsert[] = $val;
            }
        }
        $updateCols = array();

        foreach ($colNames as $curCol) {
            $updateCols[] = $curCol . " = VALUES($curCol)";
        }

        $onDup = implode(', ', $updateCols);
        // setup the placeholders - a fancy way to make the long "(?, ?, ?)..." string
        $rowPlaces = '(' . implode(', ', array_fill(0, count($colNames), '?')) . ')';
        $allPlaces = implode(', ', array_fill(0, count($dataVals), $rowPlaces));
        $sql = "INSERT INTO `articleparagraph` (" . implode(', ', $colNames) .
                ") VALUES " . $allPlaces . " ON DUPLICATE KEY UPDATE $onDup";



        $res = $this->executeStmt($sql, $dataToInsert);
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
            $paramArr['search'] = "%$search%";
            $where = "where word like :search";
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

    /**
     * 
     * @return mixed statistics on words
     */
    public function getWordStatistics() {
        $statistics = $this->smartQuery(array(
            'sql' => "select word,count(word) as word_count from articleword group by word ;",
            'par' => array(),
            'ret' => 'all'
        ));
        return $statistics;
    }

    /**
     * @return distinct words
     *
     */
    public function getDistictWords($params) {
        $paramArr = array();
        $search = (isset($params->search) && $params->search != '') ? $params->search : false;
        $page = (isset($params->page) && $params->page != '') ? $params->page : false;
        $numperpage = (isset($params->numPerPage) && $params->numPerPage != '') ? $params->numPerPage : false;
        $where = '';
        $limit = '';
        if ($search !== false) {
            $paramArr['search'] = "%$search%";
            $where = "where word like :search";
        }
        if ($page !== false && $numperpage !== false) {
            $start = ($page - 1) * $numperpage;
            $limit = "limit $start,$numperpage";
        }
        $res = $this->smartQuery(array(
            'sql' => "select distinct word from `articleword` $where $limit",
            'par' => $paramArr,
            'ret' => 'all'
        ));
        $count = $this->smartQuery(array(
            'sql' => "select count(distinct word) as total_rows from `articleword` $where",
            'par' => $paramArr,
            'ret' => 'fetch-assoc'
        ));
        $totalRows = $count['total_rows'];
        $numOfPages = floor($totalRows / $numperpage);
        $ret = array('rows' => $res, 'total_rows' => $totalRows, 'numberOfPages' => $numOfPages);
        return $ret;
    }

    /**
     * 
     * @return mixed statistics on words
     */
    public function getWordOcuranceStatistics() {
        $statistics = $this->smartQuery(array(
            'sql' => "select a.word_count,count(a.word_count) as numOfWords from (select word,count(word) as word_count from articleword group by word)as a group by a.word_count;",
            'par' => array(),
            'ret' => 'all'
        ));
        return $statistics;
    }

    /**
     * 
     * @return mixed statistics on letters
     */
    public function getLetterStatistics() {
        $statistics = $this->smartQuery(array(
            'sql' => "select letter,count(letter) as letter_count from articleletter group by (BINARY letter ) ",
            'par' => array(),
            'ret' => 'all'
        ));
        return $statistics;
    }

}

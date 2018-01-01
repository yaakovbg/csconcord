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
        $colNames = array('position', 'wordPosition', 'paragraphWordPosition', 'paragraphNumber', 'articleid', 'word', 'context');
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
        $colNames = array('paragraphNumber', 'beginning', 'end', 'articleid');
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
        $articlesFilter = (isset($params->chosenArticles) && $params->chosenArticles != '' && sizeof($params->chosenArticles) > 0 ) ? $params->chosenArticles : false;
        $groupsFilter = (isset($params->chosenGroups) && $params->chosenGroups != '' && sizeof($params->chosenGroups) > 0 ) ? $params->chosenGroups : false;
        $sortBy = (isset($params->sort) && $params->sort != '' && !(empty((array) $params->sort)) ) ? $params->sort : false;
        $where = '';
        $limit = '';
        $orderby = '';
        //$join = " join `articleparagraph` on(`articleword`.`articleid`=`articleparagraph`.`articleid` and `articleword`.`position`<=`articleparagraph`.`end` and `articleword`.`position`>=`articleparagraph`.`beginning` ) ";
        $join = "";
        $whereArray = [];
        if ($sortBy !== false) {

            $orderstr = '';
            $count = 0;
            foreach ($sortBy as $k => $v) {
                if ($orderstr !== '') {
                    $orderstr .= ',';
                }
                $orderstr .= " $k $v";
                //$orderstr .= " :orderby$count :orderbydir$count ";
                //$paramArr["orderby$count"] = $k;
                //$paramArr["orderbydir$count"] = $v;
            }
            $orderby = ' order by ' . $orderstr;
//             $orderby  = ' order 
            //print_r($sortBy);
//             $orderby  = ' order by :orderby :orderdir';
//             $paramArr['orderby'] = $sortBy;
        }
        if ($search !== false) {
            $paramArr['search'] = "%$search%";
            $searchWhere = "`articleword`.word like :search";
            $whereArray[] = $searchWhere;
        }
        if ($articlesFilter !== false) {
            $articlesSigns = "";
            $arcount = 1;
            foreach ($articlesFilter as $article) {

                if ($arcount > 1)
                    $articlesSigns .= ",";
                $articlesSigns .= ":ar" . $arcount;
                $paramArr["ar" . $arcount] = $article->id;
                $arcount++;
            }
            $whereArticles = "articleid in ($articlesSigns)";

            $whereArray[] = $whereArticles;
        }
        if ($groupsFilter !== false) {
            $join .= " left join `articlewordgroup` on(`articleword`.word=`articlewordgroup`.word) ";
            $groupsSigns = "";
            $grcount = 1;
            foreach ($groupsFilter as $group) {

                if ($grcount > 1)
                    $groupsSigns .= ",";
                $groupsSigns .= ":gr" . $grcount;
                $paramArr["gr" . $grcount] = $group->id;
                $grcount++;
            }
            $whereArticles = "`articlewordgroup`.wgid in ($groupsSigns)";

            $whereArray[] = $whereArticles;
        }
        if (sizeof($whereArray) > 0) {
            $where = "where " . implode(" and ", $whereArray);
        }
        if ($page !== false && $numperpage !== false) {
            $start = ($page - 1) * $numperpage;
            $limit = "limit $start,$numperpage";
        }

        $res = $this->smartQuery(array(
            'sql' => "select distinct articleword.* FROM `articleword` $join $where $orderby $limit",
            'par' => $paramArr,
            'ret' => 'all'
        ));
        $count = $this->smartQuery(array(
            'sql' => "select count(distinct `articleword`.id) as total_rows FROM `articleword` $join $where",
            'par' => $paramArr,
            'ret' => 'fetch-assoc'
        ));
        $totalRows = $count['total_rows'];
        $numOfPages = ($numperpage !== false) ? floor($totalRows / $numperpage) : 0;
        //$numOfPages = floor($totalRows / $numperpage);
        $ret = array('rows' => $res, 'total_rows' => $totalRows, 'numberOfPages' => $numOfPages);
        return $ret;
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
        $numOfPages = ($numperpage !== false) ? floor($totalRows / $numperpage) : 0;
        $ret = array('rows' => $res, 'total_rows' => $totalRows, 'numberOfPages' => $numOfPages);
        return $ret;
    }

    /**
     * 
     * @return mixed statistics on words
     */
    public function getWordStatistics($params) {        
        $paramArr = array();
        $articlesFilter = (isset($params->chosenArticles) && $params->chosenArticles != '' && sizeof($params->chosenArticles) > 0 ) ? $params->chosenArticles : false;
        $groupsFilter = (isset($params->chosenGroups) && $params->chosenGroups != '' && sizeof($params->chosenGroups) > 0 ) ? $params->chosenGroups : false;
        $where = '';
        $join = "";
        $whereArray = [];
        if ($articlesFilter !== false) {
            $articlesSigns = "";
            $arcount = 1;
            foreach ($articlesFilter as $article) {

                if ($arcount > 1)
                    $articlesSigns .= ",";
                $articlesSigns .= ":ar" . $arcount;
                $paramArr["ar" . $arcount] = $article->id;
                $arcount++;
            }
            $whereArticles = "articleword.articleid in ($articlesSigns)";

            $whereArray[] = $whereArticles;
        }
        if ($groupsFilter !== false) {
            $join .= " left join `articlewordgroup` on(`articleword`.word=`articlewordgroup`.word) ";
            $groupsSigns = "";
            $grcount = 1;
            foreach ($groupsFilter as $group) {

                if ($grcount > 1)
                    $groupsSigns .= ",";
                $groupsSigns .= ":gr" . $grcount;
                $paramArr["gr" . $grcount] = $group->id;
                $grcount++;
            }
            $whereArticles = "`articlewordgroup`.wgid in ($groupsSigns)";

            $whereArray[] = $whereArticles;
        }
        if (sizeof($whereArray) > 0) {
            $where = "where " . implode(" and ", $whereArray);
        }
        
        $statistics = $this->smartQuery(array(
            'sql' => "select articleword.word,count(articleword.word) as word_count from articleword $join $where group by articleword.word ;",
            'par' => $paramArr,
            'ret' => 'all'
        ));
        return $statistics;
    }

    /**
     * 
     * @return mixed statistics on words
     */
    public function getWordOcuranceStatistics($data) {
        $articlesFilter = (isset($params->chosenArticles) && $params->chosenArticles != '' && sizeof($params->chosenArticles) > 0 ) ? $params->chosenArticles : false;
        $groupsFilter = (isset($params->chosenGroups) && $params->chosenGroups != '' && sizeof($params->chosenGroups) > 0 ) ? $params->chosenGroups : false;
        if ($articlesFilter !== false) {
            $articlesSigns = "";
            $arcount = 1;
            foreach ($articlesFilter as $article) {

                if ($arcount > 1)
                    $articlesSigns .= ",";
                $articlesSigns .= ":ar" . $arcount;
                $paramArr["ar" . $arcount] = $article->id;
                $arcount++;
            }
            $whereArticles = "articleid in ($articlesSigns)";

            $whereArray[] = $whereArticles;
        }
        if ($groupsFilter !== false) {
            $join .= " left join `articlewordgroup` on(`articleword`.word=`articlewordgroup`.word) ";
            $groupsSigns = "";
            $grcount = 1;
            foreach ($groupsFilter as $group) {

                if ($grcount > 1)
                    $groupsSigns .= ",";
                $groupsSigns .= ":gr" . $grcount;
                $paramArr["gr" . $grcount] = $group->id;
                $grcount++;
            }
            $whereArticles = "`articlewordgroup`.wgid in ($groupsSigns)";

            $whereArray[] = $whereArticles;
        }
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
        $articlesFilter = (isset($params->chosenArticles) && $params->chosenArticles != '' && sizeof($params->chosenArticles) > 0 ) ? $params->chosenArticles : false;
        $groupsFilter = (isset($params->chosenGroups) && $params->chosenGroups != '' && sizeof($params->chosenGroups) > 0 ) ? $params->chosenGroups : false;
        if ($articlesFilter !== false) {
            $articlesSigns = "";
            $arcount = 1;
            foreach ($articlesFilter as $article) {

                if ($arcount > 1)
                    $articlesSigns .= ",";
                $articlesSigns .= ":ar" . $arcount;
                $paramArr["ar" . $arcount] = $article->id;
                $arcount++;
            }
            $whereArticles = "articleid in ($articlesSigns)";

            $whereArray[] = $whereArticles;
        }
        if ($groupsFilter !== false) {
            $join .= " left join `articlewordgroup` on(`articleword`.word=`articlewordgroup`.word) ";
            $groupsSigns = "";
            $grcount = 1;
            foreach ($groupsFilter as $group) {

                if ($grcount > 1)
                    $groupsSigns .= ",";
                $groupsSigns .= ":gr" . $grcount;
                $paramArr["gr" . $grcount] = $group->id;
                $grcount++;
            }
            $whereArticles = "`articlewordgroup`.wgid in ($groupsSigns)";

            $whereArray[] = $whereArticles;
        }
        $statistics = $this->smartQuery(array(
            'sql' => "select letter,count(letter) as letter_count from articleletter group by (BINARY letter ) ",
            'par' => array(),
            'ret' => 'all'
        ));
        return $statistics;
    }

}

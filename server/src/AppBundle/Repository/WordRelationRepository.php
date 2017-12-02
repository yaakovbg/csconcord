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
use AppBundle\Entity\WordRelation;
use AppBundle\Entity\Relation;
use Doctrine\Common\Collections\ArrayCollection;

class WordRelationRepository extends BaseRepository {

    /**
     * returns all word groups
     * @return array all word groups
     */
    public function getAllWordRelations() {
        $relations = $this->smartQuery(array(
            'sql' => "select * from `relation`",
            'par' => array(),
            'ret' => 'all'
        ));
        $words = $this->smartQuery(array(
            'sql' => "select * from `wordrelation`",
            'par' => array(),
            'ret' => 'all'
        ));
        $wrMap = array();
        $wrArray = new ArrayCollection;
        foreach ($relations as $k => $v) {
            $wr = new Relation($v);
            $wrMap[$wr->getId()] = $wr;
            $wrArray->add($wr);
        }
        foreach ($words as $k => $v) {
            $awr = $this->deserializeArr($v, WordRelation::class);
            $wrMap[$v['rid']]->addWord($awr);
        }

        return $wrArray;
    }
/**
 *      deletes given word group
     * @param Integer $rid
     * @return Boolen
     */
    public function delete(Relation $relation) {
        $arr = $this->serializeArr($relation);
        $this->_em->getConnection()->beginTransaction();
        try {
            $rid=$arr['id'];
            $params = array();
            $sqlArr = '';
            $res = $this->smartQuery(array(
                'sql' => "delete from `wordrelation` where rid=:rid",
                'par' => array('rid' => $rid),
                'ret' => 'result'
            ));   
            $res = $this->smartQuery(array(
                'sql' => "delete from `relation` where id=:rid",
                'par' => array('rid' => $rid),
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
     * @param WordRelation $wordRelation
     * @return Boolen
     */
    public function save(Relation $relation) {
        $arr = $this->serializeArr($relation);
        $this->_em->getConnection()->beginTransaction();
       
        try {
        if (isset($arr['id'])) {//update
            $rid=$arr['id'];
        }
        else{//insert
           
             $res = $this->smartQuery(array(
                'sql' => "insert into `relation`(name) values (:rname);",
                'par' => array('rname' => $relation->getName()),
                'ret' => 'result'
            ));
            $rid = $this->_em->getConnection()->lastInsertId();
        }
        
            $params = array();
            $sqlArr = '';
            $res = $this->smartQuery(array(
                'sql' => "delete from `wordrelation` where rid=:rid",
                'par' => array('rid' => $rid),
                'ret' => 'result'
            ));   
         
            foreach ($relation->getWords() as $k => $v) {
               if(isset($v['word'])){
                    $params[] = $v['word'];
                    $sqlArr .= ($sqlArr !== '') ? ',' : '';
                    $sqlArr .= "($rid,?)";
               } 
            }
            $res = $this->executeStmt("insert into `wordrelation`(rid,word) values $sqlArr ON DUPLICATE KEY UPDATE rid= VALUES(rid),word=VALUES(word);", $params);
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

   

}

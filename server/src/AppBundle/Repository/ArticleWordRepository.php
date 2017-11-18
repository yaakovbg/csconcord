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
            $dataVals=$this->serializeArr($articlewords);
          
        $dataToInsert = array();
        $colNames=array('position','articleid','word','context');
        foreach ($dataVals as $row => $data) {
            foreach($data as $val) {
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


         
       $res=$this->executeStmt($sql,$dataToInsert);
       

    return $res;
    }
    
   
    

}
<?php
/**
 * 
 * User: yaakov
 * Date: 15/010/17
 * Time: 12:18
 */
namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;

class BaseRepository extends EntityRepository
{
    protected function fetchAssoc($sql, $args = array(), $default = FALSE) {
        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->execute($args);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }
    protected function executeStmt($sql, $args = array()) {
        $stmt = $this->_em->getConnection()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
    /**
     * @param $arrResult
     * @param $type
     * @return array|\JMS\Serializer\scalar|mixed|object
     */
    protected function deserializeArr($arrResult, $type) {
        $serializer = SerializerBuilder::create()->build();
        $res = $serializer->deserialize(json_encode($arrResult), $type, 'json');
        return $res;
    }
    /**
     * @param $obj
     * @return mixed
     */
    protected function serializeArr($obj) {
        $serializer = SerializerBuilder::create()->build();
        $res = $serializer->toArray($obj);
        return $res;
    }
    /**
     * @param $obj
     * @return mixed
     */
    protected function serializeJson($obj) {
        $serializer = SerializerBuilder::create()->build();
        $res = $serializer->serialize($obj, 'json');
        return $res;
    }
    /**
     * @param array $arrResult
     * @param $type0
     * @return array|\JMS\Serializer\scalar|mixed|object
     */
    protected function deserializeArrs(array $arrResults, $type) {
        $results = array();
        foreach ($arrResults as $arrResult) {
            $results[] = $this->deserializeArr($arrResult, $type);
        }
        return $results;
    }
	public function smartQuery($array)
    {
        # Managing passed vars
        $sql = $array['sql'];
        $par = (isset($array['par'])) ? $array['par'] : array();
        $ret = (isset($array['ret'])) ? $array['ret'] : 'res';

        # Executing our query
        $obj = $this->_em->getConnection()->prepare($sql);
	
		foreach($par as $key=>&$value){		
			switch (gettype($value)){
				case "integer":
					$obj->bindParam($key, $value, \PDO::PARAM_INT);
					break;
				case "string":
					$obj->bindParam($key, $value, \PDO::PARAM_STR);
					break;
				case "boolean":
					$obj->bindParam($key, $value, \PDO::PARAM_BOOL);
					break;
				case "NULL":
					$obj->bindParam($key, $value, \PDO::PARAM_NULL);
					break;
				default:
					$obj->bindParam($key, $value, \PDO::PARAM_STR);
			}
		}

		$result = $obj->execute();
		//$result = $obj->execute($par);

        # Error occurred...
        if (!$result) { $this->sqlErrorHandle($obj->errorInfo(),$sql,$par);}

        # What do you want me to return?
        switch ($ret)
        {
            case 'obj':
            case 'object':
                return $obj;
            break;

            case 'ass':
            case 'assoc':
            case 'fetch-assoc':
                return $obj->fetch(\PDO::FETCH_ASSOC);
            break;

            case 'all':
            case 'fetch-all':
                return $obj->fetchAll(\PDO::FETCH_ASSOC);// PDO::FETCH_ASSOC will remove the numeric index of the result.
            break;

            case 'res':
            case 'result':
                return $result;
			break;
			case 'count':
                return $obj->rowCount();
            break;

            default:
                return $result;
            break;
        }
    }
}
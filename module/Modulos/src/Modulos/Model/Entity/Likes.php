<?php
namespace Modulos\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;


class Likes extends TableGateway
{
   private $dbAdapter;
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter=$adapter;
         
		return parent::__construct('likes', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }
    	 
    public function agregarLike($id,$ip)
	{	
        $array  =   array(
            'deck_id'      =>  $id,
            'ip'    =>  $ip
        );
		$insert=$this->insert($array);
		return $insert;
	}
    
    public function buscarIpDuplicada($ip, $id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('likes')
                ->where(array('ip' => $ip, 'deck_id' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
    
    public function getDeckLikes($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cantidad' => new \Zend\Db\Sql\Expression('COUNT(id)')))
                ->from('likes')
                ->where(array('likes.deck_id' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
}

?>
<?php
namespace Modulos\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;


class Tournaments extends TableGateway
{
   private $dbAdapter;
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter=$adapter;
         
		return parent::__construct('tournaments', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }
    	
    public function getId()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('tournaments')
                ->order('id desc')
                ->limit(1);	   
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
    
    public function TorneosRecientes()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('tournaments')
                ->order('date desc')
                ->limit(15);	   
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function obtenerTorneos($currentPage = 1, $countPerPage = 2)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('tournaments')
                ->order('date desc');   
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;   
    }
         
    public function getTournamentsDeUsuario($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('tournaments')
                ->where(array('user_id' => $id))
                ->order('date desc')
                ->group('id');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function getData($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('tournaments')
                ->where(array('id' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
    
    public function addTournament($name, $players, $date, $id_user)
    {		
       $array   =   array(
           'name'           =>  ucwords($name),
           'num_players'    =>  $players,
           'date'   =>  $date,
           'user_id'    =>  $id_user
       );
        
        $this->insert($array);
    }
    
     public function buscarTorneos($array = array(), $currentPage = 1, $countPerPage = 2)
    {
        $where = new \Zend\Db\Sql\Where();
        if(!empty($array['name2'])){
            $where->like('name', '%'.$array['name2'].'%');
        }
        if(!empty($array['num_players2'])){
            $where->like('num_players', $array['num_players2']);
        }
        if(!empty($array['date'])){
            $where->like('date2', $array['date2']);
        }
        if(!empty($array['id'])){
            $where->like('id', $array['id']);
        }
        $sql    =   new Sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('tournaments')
            ->where($where)
            ->group('id')
            ->order('date desc, id desc');
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }
    
    public function eliminarTorneo($id)
	{	
		$delete=$this->delete(array('id' => $id));
		return $delete;
	}
    
    public function modificarTorneo($id, $datos = array())
	{	
        $array  =   array(
            'num_players'  =>  $datos['num_players'],
            'date'  =>  $datos['date'],
            'name'  =>  $datos['name']
        );
		$update=$this->update($array, array("id"=>$id));
		return $update;
	}
}

?>
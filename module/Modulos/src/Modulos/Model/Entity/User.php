<?php
namespace Modulos\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;


class User extends TableGateway
{
   private $dbAdapter;
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter=$adapter;
         
		return parent::__construct('user', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }
    	 
    public function addClan($name)
    {		
       $array   =   array(
           'name_clan'  =>  $name
       );
        
        $this->insert($array);
    }
    
    public function buscarUsuarioPorId($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('user')
                ->where(array('user_id' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
    
    public function buscarUsuariosConMasDecks()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('decks', 'tournaments', 'username', 'ver_name', 'display_name', 'imagen', 'user_id', 'suma' => new \Zend\Db\Sql\Expression('(decks + tournaments)')))
                ->from('user')
                ->order('suma desc')
                ->limit(5);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function buscarUsernameRepetido($id, $name)
    {		
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('username', $name)
                ->and->notEqualTo('user_id', $id);
        
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('username'))
                ->from('user')
                ->where($where)
                ->limit(1);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
    
    public function modificarUsuario($id, $data=array())
	{	
        $array  =   array(
            'username'      =>  $data['username'],
            'display_name'  =>  $data['display_name'],
            'imagen'        =>  $data['imagen'],
            'ver_name'      =>  $data['ver_name'],
            'email'         =>  $data['email'],
            'ver_email'     =>  $data['ver_email'],
            'id_type'       =>  $data['id_type'],
        );
		$update=$this->update($array, array("user_id"=>$id));
		return $update;
	}
    
    public function modificarUsuario2($id, $data=array())
	{	
        $array  =   array(
            'username'      =>  $data['username'],
            //'display_name'  =>  $data['display_name'],
            'imagen'        =>  $data['imagen'],
            'ver_name'      =>  $data['ver_name'],
            'email'         =>  $data['email'],
            'ver_email'     =>  $data['ver_email'],
            //'id_type'       =>  $data['id_type'],
        );
		$update=$this->update($array, array("user_id"=>$id));
		return $update;
	}
    
    public function contarClans($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('name_clan' => 'name_clan', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(*)')))
                ->from('clans')
                ->join('decks', 'clans.id_clan = decks.deck_clan', array())
                ->where(array('decks.user_id' => $id))
                ->group('clans.id_clan');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function sumarDeck($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->update();
        $select->table('user');
        $select->set(array(
            'decks' => new \Zend\Db\Sql\Expression("decks + 1")
        ));
        $select->where(array('user_id'=>$id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);     
        return; 
    }
    
    public function restarDeck($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->update();
        $select->table('user');
        $select->set(array(
            'decks' => new \Zend\Db\Sql\Expression("decks - 1")
        ));
        $select->where(array('user_id'=>$id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);     
        return; 
    }
    
    public function sumarTournament($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->update();
        $select->table('user');
        $select->set(array(
            'decks' => new \Zend\Db\Sql\Expression("tournaments + 1")
        ));
        $select->where(array('user_id'=>$id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);     
        return; 
    }
    
     public function restarTournament($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->update();
        $select->table('user');
        $select->set(array(
            'decks' => new \Zend\Db\Sql\Expression("tournaments - 1")
        ));
        $select->where(array('user_id'=>$id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);     
        return; 
    }
    
    public function obtenerUsuarios($currentPage = 1, $countPerPage = 2)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('user')
                ->join('type_user', 'user.id_type = type_user.id_type', array('type'), 'left')
                ->order('username asc, display_name asc');   
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;   
    }
    
     public function buscarUsuarios($array = array(), $currentPage = 1, $countPerPage = 2)
    {
        $where = new \Zend\Db\Sql\Where();
        if(!empty($array['display_name2'])){
            $where->like('display_name', '%'.$array['display_name2'].'%');
        }
        if(!empty($array['email2'])){
            $where->like('email', $array['email2']);
        }
        if(!empty($array['username'])){
            $where->like('username', $array['username']);
        }
        if(!empty($array['user_id'])){
            $where->like('user_id', $array['user_id']);
        }
        if(!empty($array['id_type'])){
            $where->like('user.id_type', $array['id_type']);
        }
        $sql    =   new Sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('user')
             ->join('type_user', 'user.id_type = type_user.id_type', array('type'), 'left')
            ->where($where)
            ->group('user_id')
            ->order('username asc, display_name asc');
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }
}

?>
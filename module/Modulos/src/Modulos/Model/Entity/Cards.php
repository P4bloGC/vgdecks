<?php
namespace Modulos\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;


class Cards extends TableGateway
{
   private $dbAdapter;
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter=$adapter;
         
		return parent::__construct('cards', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }
    	
    public function getCardPorId($id)
    {
        $sql    =   new sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('cards')
                ->where(array('cardID' => $id));
        
        $selectString    =   $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result; 
    }
    
    public function obtenerListado($id)
    {
        $sql    =   new sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('cards')
                ->join('deck_cards', 'cards.cardID = deck_cards.card_id', array('quantity'), 'left')
                ->where(array('deck_cards.deck_id' => $id))
                ->order('grade_skill desc');        
        
        $selectString    =   $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result; 
    }
    
    public function getTriggersStatistics($id)
    {
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('deck_cards.deck_id', $id);
        $where->notEqualTo('cards.triger', 'none');
        
        $sql    =   new sql($this->dbAdapter);
        $select =   $sql->select();
        $select->columns(array('triger', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(triger)*quantity')))
                ->from('cards')
                ->join('deck_cards', 'cards.cardID = deck_cards.card_id', array('quantity'), 'left')
                ->where($where)
                ->group('triger');
        
        $selectString    =   $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result; 
    }
    
    public function getCardByName($name)
    {		
       $name =  str_replace('"', '&#34;',$name);
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('cards')
                ->where(array('name' => $name));	   
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
    
    public function verRepetidos($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('cards')
                ->where(array('cardID' => $id));	   
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }
    
    public function getCards()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('cards')
                ->group('clan');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function getNames()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('name'))
                ->from('cards')
                ->group('name');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function getAll($currentPage = 1, $countPerPage = 2)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('cards')
                ->order('name asc');
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator; 
    }
    
     public function getEffect($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cardID', 'effect'))
                ->from('cards')
                ->join('deck_cards', 'cards.cardID = deck_cards.card_id', array(), 'left')
                ->where(array('deck_cards.deck_id' => $id));	   
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function buscarCards($datos = array(), $currentPage = 1, $countPerPage = 2)
    {
        $where = new \Zend\Db\Sql\Where();
        if(!empty($datos['name'])){
            $where->like('name', '%'.$datos['name'].'%');
        }
        if(!empty($datos['cardid'])){
            $where->like('cardID', '%'.$datos['cardid'].'%');
        }
        if(!empty($datos['power'])){
            $where->like('power', $datos['power']);
        }
        if(!empty($datos['shield'])){
            $where->like('shield', $datos['shield']);
        }
        if(!empty($datos['triggers'])){
            $where->like('triger', $datos['triggers']);
        }
        if(!empty($datos['archetype'])){
            $where->like('clan', $datos['archetype']);
        }
        if(!empty($datos['uclass'])){
            $where->like('uclass', $datos['uclass']);
        }
        if(!empty($datos['skillicon'])){
            $where->like('grade_skill', $datos['skillicon']);
        }
        $sql    =   new Sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('cards')
                ->where($where)
                ->order('name asc');
    
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator; 
    }
    
    public function addCard($data = array())
    {		
       $array   =   array(
           'cardID'  =>  $data['cardid'],
           'name'   =>  $data['name'],
           'uclass' =>  $data['uclass'],
           'power'  =>  $data['power'],
           'shield' =>  $data['shield'],
           'grade'  =>  $data['grade'],
           'trigger'    =>  $data['triggers'],
           'clan'  =>  $data['archetype'],
           'skillicon'  =>  $data['skillicon'],
           'race'   =>  $data['race'],
           'illustrator'    =>  $data['illustrator'],
           'nation' =>  $data['nation'],
           'text'   =>  $data['text'],
           'effect' =>  $data['effect']
       );
        
        $this->insert($array);
    }
    
}

?>
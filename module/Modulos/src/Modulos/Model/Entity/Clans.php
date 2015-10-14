<?php
namespace Modulos\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;


class Clans extends TableGateway
{
   private $dbAdapter;
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter=$adapter;
         
		return parent::__construct('clans', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }
    	 
    public function addClan($name)
    {		
       $array   =   array(
           'name_clan'  =>  $name
       );
        
        $this->insert($array);
    }
    
    public function getClans()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('clans');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function contarClans($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('name_clan' => 'name_clan', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(id_clan)')))
                ->from('clans')
                ->join('decks', 'clans.id_clan = decks.deck_clan', array('deck_id'), 'left')
                ->where(array('decks.user_id' => $id, 'deck_type' => 2))
                ->group('clans.id_clan');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function contarClansDeTorneo($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('name_clan' => 'name_clan', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(*)')))
                ->from('clans')
                ->join('decks', 'clans.id_clan = decks.deck_clan', array(), 'left')
                ->join('tournaments', 'decks.tournament_id = tournaments.id', array(), 'left')
                ->where(array('tournaments.id' => $id))
                ->group('clans.id_clan');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
}

?>
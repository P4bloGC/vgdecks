<?php
namespace Modulos\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;


class DeckCards extends TableGateway
{
   private $dbAdapter;
    
    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter=$adapter;
         
		return parent::__construct('deck_cards', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }
    
    public function getDeck($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array('grade_skill', 'name', 'triger'), 'left')
                ->where(array('deck_id' => $id))
                ->order('cards.grade_skill asc, deck_cards.quantity asc, cards.name asc');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function getGradeCurve($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cantidad' => new \Zend\Db\Sql\Expression('SUM(quantity)')))
                ->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array('grade_skill'), 'left')
                ->where(array('deck_id' => $id))
                ->group('cards.grade_skill');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }
    
    public function addDeck($deck_id, $card_id, $quantity)
    {		
       $array   =   array(
           'deck_id'  =>  $deck_id,
           'card_id'      =>  $card_id,
           'quantity'    =>  $quantity
       );
        
        $this->insert($array);
    }
    
    public function contarGrade($id, $grade, $type)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cantidad' => new \Zend\Db\Sql\Expression('SUM(quantity)')))
                ->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array('name' => 'name'), 'left')
                ->join('decks', 'deck_cards.deck_id = decks.deck_id', array(), 'left')
                ->join('clans', 'decks.deck_clan = clans.id_clan', array(), 'left')
                ->where(array('clans.id_clan' => $id, 'decks.deck_type' => $type, 'grade_skill' => $grade))
                ->group('cards.cardID')
                ->order('cantidad desc, name asc')
                ->limit(5);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;  
    }
    
    public function contarAllGrade($id, $grade, $type)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cantidad' => new \Zend\Db\Sql\Expression('SUM(quantity)')))
                ->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array(), 'left')
                ->join('decks', 'deck_cards.deck_id = decks.deck_id', array(), 'left')
                ->join('clans', 'decks.deck_clan = clans.id_clan', array(), 'left')
                ->where(array('clans.id_clan' => $id, 'decks.deck_type' => $type, 'grade_skill' => $grade))
                ->group('cards.grade_skill');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;  
    }
    
    public function contarCartas($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('card_id', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(decks.deck_id)')))
                ->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array('name' => 'name', 'grade_skill' => 'grade_skill'), 'left')
                ->join('decks', 'deck_cards.deck_id = decks.deck_id', array('deck_clan' => 'deck_clan'), 'left')
                ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan' => 'name_clan'), 'left')
                ->where(array('clans.id_clan' => $id, 'decks.deck_type' => 1))
                ->group('deck_cards.card_id')
                ->order('cantidad desc, grade_skill desc')
                ->limit(10);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;  
    }
    
    public function contarCartas3Meses($id, $fecha)
    {
        $nuevafecha = strtotime ( '-3 month' , strtotime ($fecha)) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha ); 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('card_id', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(decks.deck_id)')))
                ->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array('name' => 'name', 'grade_skill' => 'grade_skill'), 'left')
                ->join('decks', 'deck_cards.deck_id = decks.deck_id', array('deck_clan' => 'deck_clan'), 'left')
                ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan' => 'name_clan'), 'left')
                ->join('tournaments', 'decks.tournament_id = tournaments.id', array('date' => 'date'), 'left')
                 ->group('deck_cards.card_id')
                ->order('cantidad desc, grade_skill desc')
                ->limit(10)
                ->where(array('clans.id_clan' => $id, 'decks.deck_type' => 1))
                ->where->between('tournaments.date', $nuevafecha, $fecha);
               
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;  
    }
    
    public function contarCartas6Meses($id, $fecha)
    {
        $nuevafecha = strtotime ( '-6 month' , strtotime ($fecha)) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha ); 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('card_id', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(decks.deck_id)')))
                ->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array('name' => 'name', 'grade_skill' => 'grade_skill'), 'left')
                ->join('decks', 'deck_cards.deck_id = decks.deck_id', array('deck_clan' => 'deck_clan'), 'left')
                ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan' => 'name_clan'), 'left')
                ->join('tournaments', 'decks.tournament_id = tournaments.id', array('date' => 'date'), 'left')
                 ->group('deck_cards.card_id')
                ->order('cantidad desc, grade_skill desc')
                ->limit(10)
                ->where(array('clans.id_clan' => $id, 'decks.deck_type' => 1))
                ->where->between('tournaments.date', $nuevafecha, $fecha);
               
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;  
    }
    
    public function contarCartas12Meses($id, $fecha)
    {
        $nuevafecha = strtotime ( '-12 month' , strtotime ($fecha)) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha ); 
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('card_id', 'cantidad' => new \Zend\Db\Sql\Expression('COUNT(decks.deck_id)')))
                ->from('deck_cards')
                ->join('cards', 'deck_cards.card_id = cards.cardID', array('name' => 'name', 'grade_skill' => 'grade_skill'), 'left')
                ->join('decks', 'deck_cards.deck_id = decks.deck_id', array('deck_clan' => 'deck_clan'), 'left')
                ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan' => 'name_clan'), 'left')
                ->join('tournaments', 'decks.tournament_id = tournaments.id', array('date' => 'date'), 'left')
                 ->group('deck_cards.card_id')
                ->order('cantidad desc, grade_skill desc')
                ->limit(10)
                ->where(array('clans.id_clan' => $id, 'decks.deck_type' => 1))
                ->where->between('tournaments.date', $nuevafecha, $fecha);
               
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;  
    }
    
    public function actualizarDeck($id_deck, $carta, $cantidad)
	{	
        $array  =   array(
            'quantity'  =>  $cantidad,
        );
		$update=$this->update($array, array("deck_id"=>$id_deck, "card_id" => $carta));
		return $update;
	}
    
    public function eliminarCarta($id_deck, $carta)
	{	
		$delete=$this->delete(array('deck_id' => $id_deck, 'card_id' => $carta));
		return $delete;
	}
    
     public function eliminarCartas($id_deck)
	{	
		$delete=$this->delete(array('deck_id' => $id_deck));
		return $delete;
	}
}

?>
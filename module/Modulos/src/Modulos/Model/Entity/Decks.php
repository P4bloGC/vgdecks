<?php
namespace Modulos\Model\Entity;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;


class Decks extends TableGateway
{
    private $dbAdapter;

    public function __construct(Adapter $adapter = null, $databaseSchema = null, ResultSet $selectResultPrototype = null)
    {
        $this->dbAdapter=$adapter;

        return parent::__construct('decks', $this->dbAdapter, $databaseSchema,$selectResultPrototype);
    }

    public function buscarDecks($array = array(), $currentPage = 1, $countPerPage = 2)
    {
        $where = new \Zend\Db\Sql\Where();
        if(!empty($array['name'])){
            $where->like('name', '%'.$array['name'].'%');
        }
        if(!empty($array['deck_player'])){
            $where->like('deck_player', '%'.$array['deck_player'].'%');
        }
        if(!empty($array['deck_name'])){
            $where->like('deck_name', '%'.$array['deck_name'].'%');
        }
        if(!empty($array['card_name'])){
            $where->like('cards.name', '%'.$array['card_name'].'%');
        }
        if(!empty($array['archetype'])){
            $where->like('decks.deck_clan', '%'.$array['archetype'].'%');
        }
        if(!empty($array['deck_id'])){
            $where->like('decks.deck_id', $array['deck_id']);
        }
        $sql    =   new Sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('decks')
            ->join('tournaments', 'decks.tournament_id = tournaments.id', array('name' => 'name', 'date' => 'date', 'num_players'), 'left')
            ->join('deck_cards', 'decks.deck_id = deck_cards.deck_id', array())
            ->join('cards', 'deck_cards.card_id = cards.cardID', array('card_name' => 'name'))
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan'    =>  'name_clan'))
            ->where($where)
            ->group('decks.deck_id')
            ->order('deck_date desc, decks.deck_id desc');
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }

    public function buscarDecks2($id_user, $array = array(), $currentPage = 1, $countPerPage = 2)
    {
        $where = new \Zend\Db\Sql\Where();
        if(!empty($array['name'])){
            $where->like('name', '%'.$array['name'].'%');
        }
        if(!empty($array['deck_player'])){
            $where->like('deck_player', '%'.$array['deck_player'].'%');
        }
        if(!empty($array['deck_name'])){
            $where->like('deck_name', '%'.$array['deck_name'].'%');
        }
        if(!empty($array['card_name'])){
            $where->like('cards.name', '%'.$array['card_name'].'%');
        }
        if(!empty($array['archetype'])){
            $where->like('decks.deck_clan', '%'.$array['archetype'].'%');
        }
        if(!empty($array['deck_id'])){
            $where->like('decks.deck_id', $array['deck_id']);
        }
        $where->equalTo('decks.user_id', $id_user);
        $sql    =   new Sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('decks')
            ->join('tournaments', 'decks.tournament_id = tournaments.id', array('name' => 'name', 'date' => 'date', 'num_players'), 'left')
            ->join('deck_cards', 'decks.deck_id = deck_cards.deck_id', array())
            ->join('cards', 'deck_cards.card_id = cards.cardID', array('card_name' => 'name'))
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan'    =>  'name_clan'))
            ->where($where)
            ->group('decks.deck_id')
            ->order('deck_date desc, decks.deck_id desc');
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }

    public function allDecks($currentPage = 1, $countPerPage = 2)
    {
        $sql    =   new Sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('decks')
            ->join('tournaments', 'decks.tournament_id = tournaments.id', array('name' => 'name', 'date', 'num_players'), 'left')
            ->join('deck_cards', 'decks.deck_id = deck_cards.deck_id', array())
            ->join('cards', 'deck_cards.card_id = cards.cardID', array('card_name' => 'name'))
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan'    =>  'name_clan'))
            ->group('decks.deck_id')
            ->order('deck_date desc, deck_id desc');
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;
    }

    public function getDecksPorCard($id)
    {
        $sql    =   new Sql($this->dbAdapter);
        $select =   $sql->select();
        $select->from('decks')
            ->join('deck_cards', 'decks.deck_id = deck_cards.deck_id', array('quantity' => 'quantity'), 'left')
            ->where(array('deck_cards.card_id' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function getId()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('decks')
            ->order('deck_id desc')
            ->limit(1);	   
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function getDecks($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('decks')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('deck_archetype' => 'name_clan', 'id_clan' => 'id_clan'), 'left')
            ->where(array('tournament_id' => $id))
            ->order('decks.deck_place asc');
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }

    public function obtenerDecksDeTorneo($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('deck_id', 'user_id'))
            ->from('decks')
            ->where(array('tournament_id' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }

    public function DecksRecientes()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('decks')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('deck_archetype' => 'name_clan', 'id_clan' => 'id_clan'), 'left')
            ->where(array('deck_type' => 2))
            ->order('decks.deck_date desc')
            ->limit(15);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }

    public function DecksPopulares()
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('decks')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('deck_archetype' => 'name_clan', 'id_clan' => 'id_clan'), 'left')
            ->where(array('deck_type' => 2))
            ->order('decks.likes desc')
            ->limit(15);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }

    public function getDecksArchetype($id, $currentPage = 1, $countPerPage = 2, $type)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('decks')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('deck_archetype' => 'name_clan'), 'left')
            ->join('tournaments', 'decks.tournament_id = tournaments.id', array('name' => 'name', 'num_players' => 'num_players', 'date' => 'date'), 'left')
            ->where(array('deck_clan' => $id, 'deck_type' => $type))
            ->order('tournaments.date desc, deck_date desc, deck_place asc');
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;   
    }

    public function obtenerTierDecks($fecha, $id, $nuevafecha)
    {		
        //$nuevafecha = strtotime ( '-1 month' , strtotime ($fecha)) ;
        // $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('tops' => new \Zend\Db\Sql\Expression('COUNT(deck_clan)')))
            ->from('decks')
            ->join('tournaments', 'decks.tournament_id = tournaments.id', array(), 'left')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('name_clan' => 'name_clan'), 'left')
            ->where(array('decks.deck_clan' => $id))
            ->where->between('tournaments.date', $nuevafecha, $fecha)
            ->where->between('decks.deck_place', 1, 8);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;   
    }

    public function contarTodosLosDecks($id, $type)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cantidad' => new \Zend\Db\Sql\Expression('COUNT(*)')))
            ->from('decks')
            ->where(array('deck_clan' => $id, 'deck_type' => $type));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function decksTotales($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cantidad' => new \Zend\Db\Sql\Expression('COUNT(*)')))
            ->from('decks')
            ->where(array('deck_type' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function contarDecksPorMeses($id, $fecha, $fecha2)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('cantidad' => new \Zend\Db\Sql\Expression('COUNT(*)')))
            ->from('decks')
            ->join('tournaments', 'decks.tournament_id = tournaments.id', array('date' => 'date'), 'left')
            ->where(array('deck_clan' => $id))
            ->where->between('tournaments.date', $fecha2, $fecha);
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function getData($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->from('decks')
            ->join('tournaments', 'decks.tournament_id = tournaments.id', array('num_players' => 'num_players', 'date' => 'date'), 'left')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('deck_archetype' => 'name_clan'), 'left')
            ->join('user', 'decks.user_id = user.user_id', array('username' => 'username', 'display_name' => 'display_name', 'user_id', 'imagen'), 'left')
            ->where(array('decks.deck_id' => $id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function sumarLike($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->update();
        $select->table('decks');
        $select->set(array(
            'likes' => new \Zend\Db\Sql\Expression("likes + 1")
        ));
        $select->where(array('deck_id'=>$id));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);     
        return; 
    }

    public function getDecksDeUsuario($id, $currentPage = 1, $countPerPage = 2)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('deck_id' => 'deck_id', 'deck_name' => 'deck_name', 'deck_player' => 'deck_player', 'deck_date' => 'deck_date', 'deck_clan' => 'deck_clan', 'user_id' => 'user_id', 'likes'))
            ->from('decks')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('deck_archetype' => 'name_clan'), 'left')
            ->join('user', 'decks.user_id = user.user_id', array('username' => 'username', 'display_name' => 'display_name', 'imagen'), 'left')
            ->where(array('user.user_id' => $id, 'deck_type' => 2));
        $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($currentPage);
        return $paginator;  
    }

    public function getDecksDeUsuario2($id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('deck_id' => 'deck_id', 'deck_name' => 'deck_name', 'deck_player' => 'deck_player', 'deck_date' => 'deck_date', 'deck_clan' => 'deck_clan', 'user_id' => 'user_id', 'likes'))
            ->from('decks')
            ->join('clans', 'decks.deck_clan = clans.id_clan', array('deck_archetype' => 'name_clan'), 'left')
            ->join('user', 'decks.user_id = user.user_id', array('username' => 'username', 'display_name' => 'display_name', 'imagen'), 'left')
            ->where(array('user.user_id' => $id, 'deck_type' => 2));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->toArray();       
        return $result;  
    }

    public function verificarAutorDeDeck($id_deck, $id_user)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('user_id', 'deck_comment', 'deck_name', 'deck_player'))
            ->from('decks')
            ->where(array('user_id' => $id_user, 'deck_id' => $id_deck));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function ObtenerDeck($id_deck)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('user_id', 'deck_comment', 'deck_name', 'deck_player', 'deck_type'))
            ->from('decks')
            ->where(array('deck_id' => $id_deck));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function addDeck($name, $player, $archetype, $place, $id_t, $id_user)
    {		
        $array   =   array(
            'tournament_id'  =>  $id_t,
            'deck_name'      =>  ucwords($name),
            'deck_player'    =>  $player,
            'deck_place'     =>  $place,
            'deck_clan' =>  $archetype,
            'deck_type'  =>  1,
            'user_id'   =>  $id_user
        );

        $this->insert($array);
    }

    public function addDeckCasual($name, $player, $archetype, $comment, $id_user)
    {		
        $array   =   array(
            'deck_name'      =>  ucwords($name),
            'deck_player'    =>  $player,
            'deck_clan'      =>  $archetype,
            'deck_type'      =>  2,
            'deck_comment'   =>  $comment,
            'deck_date'      =>  date('Y-m-j'),
            'user_id'        =>  $id_user
        );

        $this->insert($array);
    }

    public function actualizarComentario($id_deck, $comentario)
    {	
        $array  =   array(
            'deck_comment'  =>  $comentario,
        );
        $update=$this->update($array, array("deck_id"=>$id_deck));
        return $update;
    }

    public function actualizarDatosBasicos($id_deck, $name, $player)
    {	
        $array  =   array(
            'deck_name'  =>  ucwords($name),
            'deck_player'   =>  $player
        );
        $update=$this->update($array, array("deck_id"=>$id_deck));
        return $update;
    }

    public function obtenerPosicion($place, $id)
    {		
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select();
        $select->columns(array('deck_place'))
            ->from('decks')
            ->where(array('tournament_id' => $id, 'deck_place' => $place));
        $selectString = $sql->getSqlStringForSqlObject($select);
        $execute = $this->dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        $result=$execute->Current();       
        return $result;   
    }

    public function eliminarDeck($id_deck)
    {	
        $delete=$this->delete(array('deck_id' => $id_deck));
        return $delete;
    }
}

?>
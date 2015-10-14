<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Modulos\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Modulos\Form\DecksForm;
use Modulos\Model\Entity\Cards,
Modulos\Model\Entity\Decks,
Modulos\Model\Entity\Clans,
Modulos\Model\Entity\DeckCards,
Modulos\Model\Entity\Tournaments;

class TorneosController extends AbstractActionController
{
    public function indexAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $page                 =   $this->params()->fromRoute('id',0);
        $t   =   new Tournaments($this->dbAdapter);
        $t->obtenerTorneos($page, 25, 2);
        $this->layout('layout/blanco');
        $valores    =   array(
            'tournaments' => $t->obtenerTorneos($page, 25, 2),
            'route'  =>  'vertorneos',
            'action' =>  'index',
        );
        return new ViewModel($valores);

    }

    public function agregardeckAction()
    {
        if($this->zfcUserAuthentication()->hasIdentity()){
            if($this->getRequest()->getPost('ingresar_casual')){
                $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
                $id                 =   $this->params()->fromRoute('id',0);
                $decks              =   $this->request->getPost();
                $this->validarPosicion($decks['deck_place'], $id);
                $this->validarDecks($decks);
                $id_user    =    $this->zfcUserAuthentication()->getIdentity()->getId();   
                $d                  =   new Decks($this->dbAdapter);
                $dc                 =   new DeckCards($this->dbAdapter);
                $c                  =   new Cards($this->dbAdapter);
                $d->addDeck($decks['deck_name1'], $decks['deck_player1'], $decks['archetype1'], $decks['deck_place'], $id, $id_user);
                $deck_id    =   $d->getId();
                $texto      =    nl2br($decks['deck1']);
                $lineas     =   explode('<br />',$texto);
                foreach ($lineas as $k => $deck) {
                    if(!empty($deck[$k])){
                        preg_match_all("/([0-9]) (.*)/", $deck, $coincidencias);
                        $cantidad   =   $coincidencias[1][0];
                        $carta      = $coincidencias[2][0];
                        $card_id    =   $c->getCardByName($carta);
                        $dc->addDeck($deck_id['deck_id'], $card_id['cardID'], $cantidad);
                    }
                }
                $this->flashMessenger()->setNamespace("msg")->addMessage("Deck ingresado exitosamente");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/decks/ver/'.$deck_id['deck_id']);
            }else{
                $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
                $c  =   new Cards($this->dbAdapter);
                $form               =   new DecksForm($this->dbAdapter);
                $cards  =   $c->getNames();
                $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Datos del deck');
                $valores            =   array(
                    'titulo'    =>  'Datos del deck',
                    'form'  =>  $form,
                    'cards' =>  $cards
                );
                return new ViewModel($valores);
            }
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Para enviar un deck debe iniciar sesión");
            return	$this->redirect()->toUrl($this->url()->fromRoute('home'));
        }
    }

    public function agregarAction()
    {
        if($this->zfcUserAuthentication()->hasIdentity()){
            if($this->getRequest()->getPost('ingresar')){
                $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
                $datos              =   $this->request->getPost();
                $decks              =   $this->ordenarDecks($datos);
                $this->validarDecks($decks);
                $names              =   $this->ordenarNombres($datos);
                $players            =   $this->ordenarPlayers($datos);
                $archetypes         =   $this->ordenarArquetipos($datos);
                $id_user    =    $this->zfcUserAuthentication()->getIdentity()->getId();
                $t                  =   new Tournaments($this->dbAdapter);
                $t->addTournament($datos['name'], $datos['num_players'], $datos['date'], $id_user);
                $id_t               =   $t->getId();    
                $d                  =   new Decks($this->dbAdapter);
                $dc                 =   new DeckCards($this->dbAdapter);
                $c                  =   new Cards($this->dbAdapter);
                $id_user    =    $this->zfcUserAuthentication()->getIdentity()->getId();
                for($i=0; $i<4; $i++){
                    $place  =   $i+1;
                    $d->addDeck($names[$i], $players[$i], $archetypes[$i], $place, $id_t['id'], $id_user);
                    $deck_id    =   $d->getId();
                    $texto      =    nl2br($decks[$i]);
                    $lineas     =   explode('<br />',$texto);
                    foreach ($lineas as $k => $deck) {
                        if(!empty($deck[$k])){
                            preg_match_all("/([0-9]) (.*)/", $deck, $coincidencias);
                            $cantidad   =   $coincidencias[1][0];
                            $carta      = $coincidencias[2][0];
                            $card_id    =   $c->getCardByName($carta);
                            $dc->addDeck($deck_id['deck_id'], $card_id['cardID'], $cantidad);
                        }
                    }
                }
                $u  =   new User($this->dbAdapter);
                $u->sumarTournament($id_user);
                $this->flashMessenger()->setNamespace("msg")->addMessage("Torneo ingresado exitosamente");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/torneos/ver/'.$id_t['id']);
            }else{
                $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
                $c  =   new Cards($this->dbAdapter);
                $cards  =   $c->getNames();
                $form               =   new DecksForm($this->dbAdapter);
                $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Datos del torneo');
                $valores            =   array(
                    'titulo'    =>  'Datos del torneo',
                    'form'  =>  $form,
                    'cards' =>  $cards
                );
                return new ViewModel($valores);
            }
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Para enviar un torneo debe iniciar sesión");
            return	$this->redirect()->toUrl($this->url()->fromRoute('home'));
        }
    }

    public function verAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id                 =   $this->params()->fromRoute('id',0);
        $d                  =   new Decks($this->dbAdapter);
        $t                  =   new Tournaments($this->dbAdapter);
        $c                  =   new Clans($this->dbAdapter);
        $datos              =   $t->getData($id);    
        $decks              =   $d->getDecks($id);
        $clans              =   $c->contarClansDeTorneo($id);
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($datos['name']);
        $valores            =   array(
            'datos' =>  $datos,
            'decks' =>  $decks,
            'id'    =>  $id,
            'clans' =>  $clans
        );
        return new ViewModel($valores);
    }

    public function ordenarDecks($array)
    {
        $deck[0]    =   $array['deck1'];
        $deck[1]    =   $array['deck2'];
        $deck[2]    =   $array['deck3'];
        $deck[3]    =   $array['deck4'];
        $deck[4]    =   $array['deck5'];
        $deck[5]    =   $array['deck6'];
        $deck[6]    =   $array['deck7'];
        $deck[7]    =   $array['deck8'];
        return $deck;
    }

    public function ordenarNombres($array)
    {       
        $name[0]    =   $array['deck_name1'];
        $name[1]    =   $array['deck_name2'];
        $name[2]    =   $array['deck_name3'];
        $name[3]    =   $array['deck_name4'];
        $name[4]    =   $array['deck_name5'];
        $name[5]    =   $array['deck_name6'];
        $name[6]    =   $array['deck_name7'];
        $name[7]    =   $array['deck_name8'];
        return $name;
    }

    public function ordenarPlayers($array)
    {
        $player[0]    =   $array['deck_player1'];
        $player[1]    =   $array['deck_player2'];
        $player[2]    =   $array['deck_player3'];
        $player[3]    =   $array['deck_player4'];
        $player[4]    =   $array['deck_player5'];
        $player[5]    =   $array['deck_player6'];
        $player[6]    =   $array['deck_player7'];
        $player[7]    =   $array['deck_player8'];
        return $player;
    }

    public function ordenarArquetipos($array)
    {
        $archetype[0]    =   $array['archetype1'];
        $archetype[1]    =   $array['archetype2'];
        $archetype[2]    =   $array['archetype3'];
        $archetype[3]    =   $array['archetype4'];
        $archetype[4]    =   $array['archetype5'];
        $archetype[5]    =   $array['archetype6'];
        $archetype[6]    =   $array['archetype7'];
        $archetype[7]    =   $array['archetype8'];
        return $archetype;
    }

    public function validarDecks($array)
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $c                  =   new Cards($this->dbAdapter);
        $texto  =   nl2br($array['deck1']);
        $lineas =   explode('<br />',$texto);
        foreach ($lineas as $k => $deck) {
            if(!empty($deck[$k])){
                preg_match_all("/([0-9]) (.*)/", $deck, $coincidencias);
                $cantidad   =   $coincidencias[1][0];
                $carta      =   $coincidencias[2][0];
                if($cantidad < 1 || $cantidad > 4){
                    echo "<script type='text/javascript'>";
                    echo "alert('No puede haber $cantidad $carta en un mazo');";
                    echo "window.history.back(-1);";
                    echo "</script>";
                    exit();
                }else{
                    $card   =  $c->getCardByName($carta);
                    if($card['name'] == ''){
                        echo "<script type='text/javascript'>";
                        echo "alert('No existe la carta $carta en nuestros registros, por favor verifique que esta escrita correctamente');";
                        echo "window.history.back(-1);";
                        echo "</script>";
                        exit(); 
                    }
                }
            }
        }

        return;
    }
    
    public function validarPosicion($place, $id)
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $d  =   new Decks($this->dbAdapter);
        $posicion   =   $d->obtenerPosicion($place, $id);
        if(empty($posicion)){
            return;
        }else{
             echo "<script type='text/javascript'>";
                    echo "alert('Ya existe un deck en este evento con la posición n° $place');";
                    echo "window.history.back(-1);";
                    echo "</script>";
                    exit();
        }
    }
}


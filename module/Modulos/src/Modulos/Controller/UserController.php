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
use Modulos\Form\UserForm,
Modulos\Form\DecksForm;
use Modulos\Model\Entity\Decks,
Modulos\Model\Entity\Clans,
Modulos\Model\Entity\Cards,
Modulos\Model\Entity\DeckCards,
Modulos\Model\Entity\User,
Modulos\Model\Entity\Tournaments;    

class UserController extends AbstractActionController
{
    public function perfilAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id =   $this->params()->fromRoute('id',0);
        $d  =   new Decks($this->dbAdapter);
        $c  =   new Clans($this->dbAdapter);
        $t  =   new Tournaments($this->dbAdapter);
        $u  =   new User($this->dbAdapter);
        $decks  =   $d->getDecksDeUsuario2($id);
        $clans  =   $c->contarClans($id);
        $tournaments    =   $t->getTournamentsDeUsuario($id);
        $user   =   $u->buscarUsuarioPorId($id);
        if($user['username'] == 'Desconocido' && $user['ver_name'] == 1){
            $name  =   $user['display_name'];
        }else{
            $name = $user['username'];
        }
        if($user['ver_name'] == 0){
            $user['display_name'] = 'CONFIDENCIAL';
        }
        if($user['ver_email'] == 0){
            $user['email'] = 'CONFIDENCIAL';
        }
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($name);
        $valores    =   array(
            'titulo' => $name,
            'decks' =>  $decks,
            'clans' => $clans,
            'tournaments' => $tournaments,
            'imagen'    =>  $user['imagen'],
            'user'  =>  $user
        );
        return new ViewModel($valores);
    }

    public function editarAction()
    {
        $this->verificar_usuario();
        $id = $this->zfcUserAuthentication()->getIdentity()->getId();
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $u  = new User($this->dbAdapter);
        if($this->getRequest()->getPost('editar')){
            $datos  =   $this->request->getPost();
            $username   =   $u->buscarUsernameRepetido($id, $datos['username']);
            if(empty($username) || $username['username'] == 'Desconocido'){
                $u->modificarUsuario2($id, $datos);
                $this->flashMessenger()->setNamespace("msg")->addMessage("Perfil editado exitosamente");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/user/editar/'.$id); 
            }else{
                $this->flashMessenger()->setNamespace("msg")->addMessage("Nickname ingresado ya esta siendo usado por otro usuario. Por favor elegir un nickname distinto");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/user/editar/'.$id); 
            }
        }
        $form   =   new UserForm($this->dbAdapter);
        $usuario   =   $u->buscarUsuarioPorId($id);
        $form->setData($usuario);
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Configurar perfil');
        $valores    =   array(
            'form'  =>  $form,
            'titulo'    =>  'Configurar perfil',
            'imagen'    =>  $usuario['imagen']
        );
        return new ViewModel($valores);
    }

    public function misdecksAction()
    {
        $this->verificar_usuario();
        $page               =   $this->params()->fromRoute('id',1);
        $this->dbAdapter  =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $d  =   new Decks($this->dbAdapter);
        $id_user    =   $this->zfcUserAuthentication()->getIdentity()->getId();
        $form   =   new DecksForm($this->dbAdapter);
         if($this->getRequest()->isPost()){
            $datos  =   $this->request->getPost();
            $decks  =   $d->buscarDecks2($id_user, $datos, $page, 25, 2);
            $form->setData($datos);
        }else{
        $decks  =   $d->getDecksDeUsuario($id_user, $page, 25, 2);
        }
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Mis decks');
        $valores    =   array(
            'titulo' =>   'Mis decks',
            'decks' =>  $decks,
            'route'  =>  'decksusuario',
            'action' =>  'misdecks',
            'form'  =>  $form
        );
        return new ViewModel($valores);
    }

    public function modificardeckAction()
    {
        if($this->getRequest()->getPost('actualizar_comentario')){
            $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
            $d =   new Decks($this->dbAdapter);
            $datos  =   $this->request->getPost();
            $id_deck    =   $this->params()->fromRoute('id', 0);
            $d->actualizarComentario($id_deck, $datos['deck_comment']);
            $this->flashMessenger()->setNamespace("msg")->addMessage("Deck actualizado exitosamente");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/user/modificardeck/'.$id_deck);
        }
        if($this->getRequest()->getPost('nueva_carta')){
            $datos  =   $this->request->getPost();
            $id_deck    =   $this->params()->fromRoute('id', 0);
            if(!empty($datos['carta'])){
                $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
                $dc =   new DeckCards($this->dbAdapter);
                $c =   new Cards($this->dbAdapter);
                preg_match_all("/([0-9]) (.*)/", $datos['carta'], $coincidencias);
                $cantidad   =   $coincidencias[1][0];
                $carta      = $coincidencias[2][0];
                $card_id    =   $c->getCardByName($carta);
                if(!empty($card_id)){
                    $dc->addDeck($id_deck, $card_id['cardID'], $cantidad);
                    $this->flashMessenger()->setNamespace("msg")->addMessage("Deck actualizado exitosamente");
                    return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/user/modificardeck/'.$id_deck);
                }else{
                    $this->flashMessenger()->setNamespace("msg")->addMessage("No existe la carta '$carta' en nuestra base de datos");
                    return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/user/modificardeck/'.$id_deck);
                }
            }else{
                $this->flashMessenger()->setNamespace("msg")->addMessage("Ingrese una carta");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/user/modificardeck/'.$id_deck);
            }
        }
        if($this->getRequest()->getPost('actualizar')){
            $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
            $dc =   new DeckCards($this->dbAdapter);
            $datos  =   $this->request->getPost();
            $id_deck    =   $this->params()->fromRoute('id', 0);
            foreach($datos as $carta => $cantidad){
                if($cantidad == 0){
                    $dc->eliminarCarta($id_deck, $carta);
                }elseif(is_numeric($cantidad)){
                    $dc->actualizarDeck($id_deck, $carta, $cantidad);
                }
            }
            $this->flashMessenger()->setNamespace("msg")->addMessage("Deck actualizado exitosamente");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/user/modificardeck/'.$id_deck);
        }
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id_deck    =   $this->params()->fromRoute('id', 0);
        $id_user    =   $this->zfcUserAuthentication()->getIdentity()->getId(); 
        $d  =   new Decks($this->dbAdapter);
        $deck = $d->verificarAutorDeDeck($id_deck, $id_user);
        if(!empty($deck)){
            $c  =   new Cards($this->dbAdapter);
            $form   =   new DecksForm($this->dbAdapter);
            $cartas =   $c->obtenerListado($id_deck);
            $form->setData($deck);
            $valores    =   array(
                'titulo'    =>  'Listado',
                'cartas'    =>  $cartas,
                'form'  =>  $form,
                'allcards'  =>  $c->getNames(),
            );
            return new ViewModel($valores);
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Acceso restringido");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl()); 
        }
    }

    public function verificar_usuario()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()){
            return;      
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Para tener acceso a esta sección debe iniciar sesión");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl()); 
        }
    }
}

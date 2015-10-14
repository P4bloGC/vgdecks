<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Modulos\Controller;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Modulos\Form\DecksForm,
Modulos\Form\UserForm;
use Modulos\Model\Entity\Tournaments,
Modulos\Model\Entity\User,
Modulos\Model\Entity\DeckCards,
Modulos\Model\Entity\Cards,
Modulos\Model\Entity\Decks;

class AdminController extends AbstractActionController
{
    public function onDispatch(MvcEvent $e) {

        $this->verificar_usuario();

        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        $this->layout('layout/admin');
        return new ViewModel();
    }

    public function DecksAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $page               =   $this->params()->fromRoute('id',1);
        $form   =   new DecksForm($this->dbAdapter);
        $d  =   new Decks($this->dbAdapter);
        if($this->getRequest()->isPost()){
            $datos  =   $this->request->getPost();
            $decks  =   $d->buscarDecks($datos, $page, 25, 2);
            $form->setData($datos);
        }else{
            $decks  =   $d->allDecks($page, 25, 2);
        }
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Buscador de decks');
        $valores    =   array(
            'form'  =>  $form,
            'titulo'    =>  'Buscador de decks',
            'decks' =>  $decks,
            'route'  =>  'verdecks2',
            'action' =>  'decks', 
        );
        $this->layout('layout/admin');
        return new ViewModel($valores);
    }

    public function eliminardeckAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id =   $this->params()->fromRoute('id', 0);
        $dc =   new DeckCards($this->dbAdapter);
        $d  =   new Decks($this->dbAdapter);
        $u  =   new User($this->dbAdapter);
        $id_user = $d->ObtenerDeck($id);
            $dc->EliminarCartas($id);
        $d->eliminarDeck($id);
        if($id_user['deck_type'] == 2){
            $u->restarDeck($id_user['user_id']);
        }
        $this->flashMessenger()->setNamespace("msg")->addMessage("Deck eliminado exitosamente");
        return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/decks');

    }

    public function deletedeckAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id =   $this->params()->fromRoute('id', 0);
        $id2 =   $this->params()->fromRoute('id2', 0);
        $dc =   new DeckCards($this->dbAdapter);
        $d  =   new Decks($this->dbAdapter);
        $dc->EliminarCartas($id);
        $d->eliminarDeck($id);
        $this->flashMessenger()->setNamespace("msg")->addMessage("Deck eliminado exitosamente");
        return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificartorneo/'.$id2);

    }

    public function modificardeckAction()
    {
        $this->layout('layout/admin');
        if($this->getRequest()->getPost('datos_basicos')){
            $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
            $d =   new Decks($this->dbAdapter);
            $datos  =   $this->request->getPost();
            $id_deck    =   $this->params()->fromRoute('id', 0);
            $d->actualizarDatosBasicos($id_deck, $datos['deck_name'], $datos['deck_player']);
            $this->flashMessenger()->setNamespace("msg")->addMessage("Deck actualizado exitosamente");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificardeck/'.$id_deck);
        }
        if($this->getRequest()->getPost('actualizar_comentario')){
            $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
            $d =   new Decks($this->dbAdapter);
            $datos  =   $this->request->getPost();
            $id_deck    =   $this->params()->fromRoute('id', 0);
            $d->actualizarComentario($id_deck, $datos['deck_comment']);
            $this->flashMessenger()->setNamespace("msg")->addMessage("Deck actualizado exitosamente");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificardeck/'.$id_deck);
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
                    return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificardeck/'.$id_deck);
                }else{
                    $this->flashMessenger()->setNamespace("msg")->addMessage("No existe la carta '$carta' en nuestra base de datos");
                    return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificardeck/'.$id_deck);
                }
            }else{
                $this->flashMessenger()->setNamespace("msg")->addMessage("Ingrese una carta");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificardeck/'.$id_deck);
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
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificardeck/'.$id_deck);
        }
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id_deck    =   $this->params()->fromRoute('id', 0);
        $id_user    =   $this->zfcUserAuthentication()->getIdentity()->getId(); 
        $d  =   new Decks($this->dbAdapter);
        $deck = $d->ObtenerDeck($id_deck);
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
    }

    public function torneosAction()
    {
        $this->layout('layout/admin');
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $page                 =   $this->params()->fromRoute('id',0);
        $form   =   new DecksForm($this->dbAdapter);
        $t   =   new Tournaments($this->dbAdapter);
        if($this->getRequest()->isPost()){
            $datos  =   $this->request->getPost();
            $torneos  =   $t->buscarTorneos($datos, $page, 25, 2);
            $form->setData($datos);
        }else{
            $torneos = $t->obtenerTorneos($page, 25, 2);
        }
        $valores    =   array(
            'tournaments' => $torneos,
            'route'  =>  'vertorneos2',
            'action' =>  'torneos',
            'form'  =>  $form
        );
        return new ViewModel($valores);
    }

    public function eliminartorneoAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id =   $this->params()->fromRoute('id', 0);
        $dc =   new DeckCards($this->dbAdapter);
        $d  =   new Decks($this->dbAdapter);
        $u  =   new User($this->dbAdapter);
        $t  =   new Tournaments($this->dbAdapter);
        $decks  =   $d->obtenerDecksDeTorneo($id);
        foreach($decks as $var){
            $dc->EliminarCartas($decks['deck_id']);
            $d->eliminarDeck($decks['deck_id']);
        }
        $t->eliminarTorneo($id);
        $u->restarTournament($decks[0]['user_id']);
        $this->flashMessenger()->setNamespace("msg")->addMessage("Torneo eliminado exitosamente");
        return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/torneos');
    }

    public function modificartorneoAction()
    {
        $this->layout('layout/admin');
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id =   $this->params()->fromRoute('id',0);
        $t  =   new Tournaments($this->dbAdapter);
        if($this->getRequest()->isPost()){
            $datos =  $this->request->getPost();
            $t->modificarTorneo($id, $datos);
            $this->flashMessenger()->setNamespace("msg")->addMessage("Torneo actualizado exitosamente");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificartorneo/'.$id);
        }
        $form   =   new DecksForm($this->dbAdapter);
        $datos  =   $t->getData($id);
        $form->setData($datos);
        $d       =   new Decks($this->dbAdapter);
        $valores    =   array(
            'titulo' => 'Modificar torneo',
            'form'  =>  $form,
            'decks' =>  $d->getDecks($id),
            'id'    =>  $id
        );
        return new ViewModel($valores);
    }

    public function usuariosAction()
    {
        $this->verificar_admin();
        $this->layout('layout/admin');
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $page                 =   $this->params()->fromRoute('id',0);
        $form   =   new UserForm($this->dbAdapter);
        $u   =   new User($this->dbAdapter);
        if($this->getRequest()->isPost()){
            $datos  =   $this->request->getPost();
            $users  =   $u->buscarUsuarios($datos, $page, 25, 2);
            $form->setData($datos);
        }else{
            $users = $u->obtenerUsuarios($page, 25, 2);
        }
        $valores    =   array(
            'titulo' => 'Mantenedor de usuarios',
            'users' => $users,
            'route'  =>  'verusers',
            'action' =>  'usuarios',
            'form'  =>  $form
        );
        return new ViewModel($valores);
    }

    public function modificarusuarioAction()
    {
        $this->verificar_admin();
        $this->layout('layout/admin');
        $id = $this->params()->fromRoute('id', 0);
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $u  = new User($this->dbAdapter);
        if($this->getRequest()->getPost('editar')){
            $datos  =   $this->request->getPost();
            $repetido   =   $u->buscarUsernameRepetido($id, $datos['username']);
            if(empty($repetido)){
                $usuario['display_name'] = $usuario['display_name2'];
                $usuario['email']  =   $usuario['email2'];
                $u->modificarUsuario($id, $datos);
                $this->flashMessenger()->setNamespace("msg")->addMessage("Perfil editado exitosamente");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificarusuario/'.$id); 
            }else{
                $this->flashMessenger()->setNamespace("msg")->addMessage("Nickname ingresado ya esta siendo usado por otro usuario. Por favor elegir un nickname distinto");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/admin/modificarusuario/'.$id); 
            }
        }
        $form   =   new UserForm($this->dbAdapter);
        $usuario   =   $u->buscarUsuarioPorId($id);
        $usuario['display_name2'] = $usuario['display_name'];
        $usuario['email2']  =   $usuario['email'];
        $form->setData($usuario);
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Configurar perfil');
        $valores    =   array(
            'form'  =>  $form,
            'titulo'    =>  'Configurar perfil',
            'imagen'    =>  $usuario['imagen']
        );
        return new ViewModel($valores);
    }

    public function verificar_usuario()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()){
            $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
            $u  =   new User($this->dbAdapter);
            $user   =   $u->buscarUsuarioPorId($this->zfcUserAuthentication()->getIdentity()->getId());
            if($user['id_type'] == 1 || $user['id_type'] == 2){
                return;
            }else{
                $this->flashMessenger()->setNamespace("msg")->addMessage("No tiene los permisos necesarios para ingresar a esta sección");
                return	$this->redirect()->toUrl($this->url()->fromRoute('home'));    
            }
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Para tener acceso a esta sección debe iniciar sesión");
            return	$this->redirect()->toUrl($this->url()->fromRoute('home')); 
        }
    }

    public function verificar_admin()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()){
            $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
            $u  =   new User($this->dbAdapter);
            $user   =   $u->buscarUsuarioPorId($this->zfcUserAuthentication()->getIdentity()->getId());
            if($user['id_type'] == 1){
                return;
            }else{
                $this->flashMessenger()->setNamespace("msg")->addMessage("No tiene los permisos necesarios para ingresar a esta sección");
                return	$this->redirect()->toUrl($this->url()->fromRoute('home'));    
            }
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Para tener acceso a esta sección debe iniciar sesión");
            return	$this->redirect()->toUrl($this->url()->fromRoute('home'));
        }
    }
}
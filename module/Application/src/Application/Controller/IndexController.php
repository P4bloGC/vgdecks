<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Modulos\Form\UserForm;
use Modulos\Model\Entity\Tournaments,
Modulos\Model\Entity\User,
Modulos\Model\Entity\Decks;

class IndexController extends AbstractActionController
{
    private $dbAdapter;
    private $id_idioma;
    private $localizacion;

    public function indexAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend/Db/Adapter');
        $t                  =   new Tournaments($this->dbAdapter);
        $d                  =   new Decks($this->dbAdapter);
        $u                  =   new User($this->dbAdapter);
        // $this->layout('layout/portada');
        $valores            =   array(
            'tournaments'   =>  $t->TorneosRecientes(),
            'decks'         =>  $d->DecksRecientes(),
            'populares'     =>  $d->DecksPopulares(),
            'users'         =>  $u->buscarUsuariosConMasDecks(),
        );
        return new ViewModel($valores);
    }

    public function contactoAction()
    {
        if($this->getRequest()->getPost('enviar_mensaje')){
            $datos  =   $this->request->getPost();
            // Varios destinatarios
            $para  = 'yagami2k5@gmail.com'; // atención a la coma
            // título
            $asunto = $datos['asunto'];
            // mensaje
            $mensaje = $datos['mensaje'];
            // Para enviar un correo HTML, debe establecerse la cabecera Content-type
            $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
            $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            // Cabeceras adicionales
            $cabeceras .= "From: Notificación <".$datos['email_usuario'].">" . "\r\n";
            // Enviarlo
            mail($para, $asunto, $mensaje, $cabeceras);
            $this->flashMessenger()->setNamespace("msg")->addMessage("Mensaje enviado exitosamente");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/application/index/contacto');
        }
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $form   =   new UserForm($this->dbAdapter);
        $valores    =   array(
            'titulo'    =>  'Formulario de contacto',
            'form'  =>  $form
        );
        return new ViewModel($valores);
    }

    public function idiomaAction()
    {
        //Con el idioma que nos llega del formulario creamos una sesión
        $idioma_post=$this->params()->fromPost("idioma", "es_ES");
        $idioma=new Container('idioma');
        $idioma->lang=$idioma_post;

        //Conseguimos el id del idioma
        // $modelo=new PruebasModel($this->getDbAdapter());
        $idioma->id=1;

        //echo $idioma->lang;
        //echo $idioma->id;

        //Establecemos el nuevo idioma
        $translator = $this->getServiceLocator()->get('translator');
        $translator->setLocale($idioma->lang)->setFallbackLocale($idioma->lang);
        $this->redirect()->toRoute("home");

    }
}

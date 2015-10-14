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
use Modulos\Form\CardsForm,
Modulos\Form\DecksForm;
use Modulos\Model\Entity\Cards,
Modulos\Model\Entity\Decks;

class CartasController extends AbstractActionController
{
    public function buscarAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $page               =   $this->params()->fromRoute('id',1);
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Buscar carta');
        if($this->getRequest()->isPost()){
            $datos              =   $this->request->getPost();
            $c                  =   new Cards($this->dbAdapter);
            $form               =   new CardsForm($this->dbAdapter);
            $form->setData($datos);
            $valores            =   array(
                'cards'  =>  $c->buscarCards($datos,$page, 25, 2),
                'form'   =>  $form,
                'titulo' => 'Buscar carta',
                'route'  =>  'vercartas',
                'action' =>  'buscar', 
            );
            return new ViewModel($valores);
        }
        $c                  =   new Cards($this->dbAdapter);
        $form               =   new CardsForm($this->dbAdapter);
        $valores            =   array(
            'cards'   =>  $c->getAll($page, 25, 2),
            'titulo'  =>  'Buscar carta',
            'form'    =>    $form,
            'route'   =>  'vercartas',
            'action'  =>  'buscar',
        );
        return new ViewModel($valores);
    }
    
    public function detalleAction()
    {
        $this->dbAdapter =  $this->getServiceLocator()->get('Zend/Db/Adapter');       
        $id =   $this->params()->fromQuery('id', null);
        $c  =   new Cards($this->dbAdapter);
        $form  =   new DecksForm($this->dbAdapter);
        $card   =   $c->getCardPorId($id);
        $card['card_name']  =   $card['name'];
        $form->setData($card);
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($card['card_name']);
        $valores    =   array(
            'titulo'    =>  '['.$card['cardID'].'] '.$card['name'],
            'card'      =>  $card,
            'form'      =>   $form
        );
        return new ViewModel($valores);
    }
}

<?php
namespace Modulos\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Modulos\Form\DecksForm;
use Modulos\Model\Entity\Cards,
Modulos\Model\Entity\Clans,
Modulos\Model\Entity\User,
Modulos\Model\Entity\Decks,
Modulos\Model\Entity\Likes,
Modulos\Model\Entity\DeckCards,
Modulos\Model\Entity\Tournaments;

class DecksController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function torneosAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $rango              =   $this->params()->fromRoute('id', 0);
        $c                  =   new Clans($this->dbAdapter);
        $d                  =   new Decks($this->dbAdapter);
        $clans              =   $c->getClans();
        $total              =   $d->decksTotales(1);
        $fecha = date('Y-m-j');
        if($total['cantidad'] == 0){
            $total = 1;
        }
        switch($rango){
            case 0:
                $meses = '-6 month';
                $subtitulo  =   'Tier decks de los últimos 6 meses';
                break;
            case 1:
                $meses = '-3 month';
                $subtitulo  =   'Tier decks de los últimos 3 meses';
                break;
            case 2:
                $meses = '-12 month';
                $subtitulo  =   'Tier decks del ultimo año';
                break;
            case 3:
                $meses = '2015-01-01';
                $subtitulo  =   'Tier decks';
                break;
        }
        $nuevafecha = strtotime ($meses, strtotime($fecha)) ;
        $nuevafecha = date ( 'Y-m-j', $nuevafecha ); 
        foreach($clans as $index => $var){    
            if($rango == 3){
                $clans[$index]['cantidad'] = $d->contarTodosLosDecks($var['id_clan'], 1);
            }else{
                $clans[$index]['cantidad'] = $d->contarDecksPorMeses($var['id_clan'], $fecha, $nuevafecha);  
            }
            $tops[$index]['tops']   =   $d->obtenerTierDecks($fecha, $var['id_clan'], $nuevafecha);
        }
        foreach ($clans as $key => $row) { // ordena cantidad mazos
            $mid[$key]  = $row['cantidad'];
        }
        array_multisort($mid, SORT_DESC, $clans);
        foreach ($tops as $key => $row) { // ordena top tiers
            $mid[$key]  = $row['tops'];
        }
        array_multisort($mid, SORT_DESC, $tops);
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Torneos');
        $valores            =   array(
            'clans'         =>  $clans,
            'titulo'        =>  'Torneos',
            'total'         =>  $total['cantidad'],
            'rango'         =>  $rango,
            'tops'          =>  $tops,
            'subtitulo'     =>  $subtitulo
        );
        return new ViewModel($valores);
    }

    public function arquetiposAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $rango  =   $this->params()->fromRoute('id', 0);
        $c  =   new Clans($this->dbAdapter);
        $d  =   new Decks($this->dbAdapter);
        $clans  =   $c->getClans();
        $total  =   $d->decksTotales(2);
        if($total['cantidad'] == 0){
            $total['cantidad'] = 1;
        }
        $fecha  =   date('Y-m-j');
        foreach($clans as $index => $var){    
            $clans[$index]['cantidad'] = $d->contarTodosLosDecks($var['id_clan'], 2); 
        }
        foreach ($clans as $key => $row) { // ordena cantidad mazos
            $mid[$key]  = $row['cantidad'];
        }
        array_multisort($mid, SORT_DESC, $clans);
        $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Arquetipos');
        $valores            =   array(
            'clans'         =>  $clans,
            'titulo'        =>  'Arquetipos',
            'total'         =>  $total['cantidad']
        );
        return new ViewModel($valores);
    }

    public function verAction()
    {
        $id =   $this->params()->fromRoute('id',0);
        if($this->getRequest()->isPost()){
            $ip = $_SERVER['REMOTE_ADDR'];
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            $this->validar_ip($ip,$id);
        }else{
            $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
            $dc                 =   new DeckCards($this->dbAdapter);
            $d                  =   new Decks($this->dbAdapter);
            $c                  =   new Cards($this->dbAdapter);
            $datos              =   $d->getData($id);
            if($datos['username'] == 'Desconocido' && $datos['ver_name'] == 1){
                $datos['username'] = $datos['display_name'];
            }
            $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($datos['deck_name']);
            $valores            =   array(
                'datos' =>  $datos,
                'deck'  =>  $dc->getDeck($id),
                'otros' =>  $d->getDecks($datos['tournament_id']),
                'id'    =>  $id,
                'grade_curve'   =>  $dc->getGradeCurve($id),
                'triggers'  =>  $c->getTriggersStatistics($id)
            );
            return new ViewModel($valores);
        }
    }

    public function verarquetipoAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id                 =   $this->params()->fromRoute('id',0);
        $page               =   $this->params()->fromRoute('id2',1);
        $d                  =   new Decks($this->dbAdapter);
        $dc                 =   new DeckCards($this->dbAdapter);
        $fecha              =   date('Y-m-j');
        $grade4             =   $dc->contarGrade($id, 'Grade 4 /  Triple Drive!!!', 1);
        $allgrade4          =   $dc->contarAllGrade($id, 'Grade 4 /  Triple Drive!!!', 1);
        $grade3             =   $dc->contarGrade($id, 'Grade 3 /  Twin Drive!!', 1);
        $allgrade3          =   $dc->contarAllGrade($id, 'Grade 3 /  Twin Drive!!', 1);
        $grade2             =   $dc->contarGrade($id, 'Grade 2 /  Intercept', 1);
        $allgrade2          =   $dc->contarAllGrade($id, 'Grade 2 /  Intercept', 1);
        $grade1             =   $dc->contarGrade($id, 'Grade 1 /  Boost', 1);
        $allgrade1          =   $dc->contarAllGrade($id, 'Grade 1 /  Boost', 1);
        $decks              =   $d->getDecksArchetype($id, $page, 25, 1);
        //$this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($decks[1]['deck_archetype']);
        $valores            =   array(
            'decks' =>  $decks,
            'cantidad'  =>  $d->contarTodosLosDecks($id, 1),
            'route'     =>  'verarquetipo',
            'action'    =>  "verarquetipo/$id",
            'id'        =>  $id,
            'grade4'    =>  $grade4,
            'allgrade4' =>  $allgrade4,
            'grade3'    =>  $grade3,
            'allgrade3' =>  $allgrade3,
            'grade2'    =>  $grade2,
            'allgrade2' =>  $allgrade2,
            'grade1'    =>  $grade1,
            'allgrade1' =>  $allgrade1
        );
        return new ViewModel($valores);
    }

    public function verarquetipocasAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id                 =   $this->params()->fromRoute('id',0);
        $page               =   $this->params()->fromRoute('id2',1);
        $d                  =   new Decks($this->dbAdapter);
        $dc                 =   new DeckCards($this->dbAdapter);
        $decks              =   $d->getDecksArchetype($id, $page, 25, 2);
        $cantidad           =   $d->contarTodosLosDecks($id, 2);
        $grade4             =   $dc->contarGrade($id, 'Grade 4 /  Triple Drive!!!', 2);
        $allgrade4          =   $dc->contarAllGrade($id, 'Grade 4 /  Triple Drive!!!', 2);
        $grade3             =   $dc->contarGrade($id, 'Grade 3 /  Twin Drive!!', 2);
        $allgrade3          =   $dc->contarAllGrade($id, 'Grade 3 /  Twin Drive!!', 2);
        $grade2             =   $dc->contarGrade($id, 'Grade 2 /  Intercept', 2);
        $allgrade2          =   $dc->contarAllGrade($id, 'Grade 2 /  Intercept', 2);
        $grade1             =   $dc->contarGrade($id, 'Grade 1 /  Boost', 2);
        $allgrade1          =   $dc->contarAllGrade($id, 'Grade 1 /  Boost', 2);
        $valores            =   array(
            'decks' =>  $decks,
            'cantidad'  =>  $cantidad,
            'route'     =>  'verarquetipo',
            'action'    =>  "verarquetipocas/$id",
            'id'        =>  $id,
            'grade4'    =>  $grade4,
            'allgrade4' =>  $allgrade4,
            'grade3'    =>  $grade3,
            'allgrade3' =>  $allgrade3,
            'grade2'    =>  $grade2,
            'allgrade2' =>  $allgrade2,
            'grade1'    =>  $grade1,
            'allgrade1' =>  $allgrade1
        );
        return new ViewModel($valores);
    }

    public function agregarAction()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            if($this->getRequest()->getPost('ingresar_casual')){
                $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
                $decks              =   $this->request->getPost();
                $this->validarDecks($decks); 
                $d                  =   new Decks($this->dbAdapter);
                $dc                 =   new DeckCards($this->dbAdapter);
                $c                  =   new Cards($this->dbAdapter);
                $u                  =   new User($this->dbAdapter);
                $id_user    =    $this->zfcUserAuthentication()->getIdentity()->getId();
                $d->addDeckCasual($decks['deck_name1'], $decks['deck_player1'], $decks['archetype1'], $decks['deck_comment1'], $id_user);
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
                $u->sumarDeck($id_user);
                $this->flashMessenger()->setNamespace("msg")->addMessage("Deck ingresado exitosamente");
                return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/decks/ver/'.$deck_id['deck_id']); 
            }else{
                $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
                $form               =   new DecksForm($this->dbAdapter);
                $c  =   new Cards($this->dbAdapter);
                $cards  =   $c->getNames();
                $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set('Mi deck');
                $valores            =   array(
                    'titulo'    =>  'Mi deck',
                    'form'  =>  $form,
                    'cards' =>  $cards,
                );
                return new ViewModel($valores);
            }
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Para enviar un deck debe iniciar sesión");
           return	$this->redirect()->toUrl($this->url()->fromRoute('home'));
        }
    }

    public function buscarAction()
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
            'route'  =>  'verdecks',
            'action' =>  'buscar', 
        );
        return new ViewModel($valores);
    }

    public function analisisAction()
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $id                 =   $this->params()->fromRoute('id', 0);
        $dc                 =   new DeckCards($this->dbAdapter);
        if($id == 3){
            $cartas =   $dc->contarCartas($id);
        }

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
    public function validar_ip($ip, $id)
    {
        $this->dbAdapter    =   $this->getServiceLocator()->get('Zend\Db\Adapter');
        $l  =   new Likes($this->dbAdapter);
        $d  =   new Decks($this->dbAdapter);
        $duplicada =   $l->buscarIpDuplicada($ip, $id);
        if(empty($duplicada)){
            $l->agregarLike($id, $ip);
            $d->sumarLike($id);
            $this->flashMessenger()->setNamespace("msg")->addMessage("Me gusta agregado exitosamente, gracias por votar!");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/decks/ver/'.$id); 
        }else{
            $this->flashMessenger()->setNamespace("msg")->addMessage("Me gusta duplicados no son permitidos");
            return	$this->redirect()->toUrl($this->getRequest()->getBaseUrl().'/modulos/decks/ver/'.$id);
        }
    }
}


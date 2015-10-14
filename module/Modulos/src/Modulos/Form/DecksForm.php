<?php
namespace Modulos\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Captcha;
use Zend\Form\Factory;
use Zend\Db\Adapter\AdapterInterface; 


class DecksForm extends Form
{
    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->setDbAdapter($dbAdapter);
        parent::__construct();

       //nombre del torneo
        $this->add(array(
            'name'      => 'name',
            'attributes'=> array(
                'id'    => 'ame',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del torneo',
            ),
        ));
        //nombre del torneo2
        $this->add(array(
            'name'      => 'name2',
            'attributes'=> array(
                'id'    => 'ame',
                'type'  => 'input',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del torneo',
            ),
        ));
        //nombre de carta
        $this->add(array(
            'name'      => 'card_name',
            'attributes'=> array(
                'id'    => 'card_name',
                'type'  => 'input',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del torneo',
            ),
        ));
        //carta
        $this->add(array(
            'name'      => 'carta',
            'attributes'=> array(
                'id'    => 'carta',
                'type'  => 'input',
                'class' => 'form-control input-sm',
                'placeholder' => '4 Blaster Blade'
            ),
            'options' => array(
                'label' => 'Carta',
            ),
        ));
        //Cantidad de jugadores
        $this->add(array(
            'name'      => 'num_players',
            'attributes'=> array(
                'id'    => 'num_players',
                'type'  => 'number',
                'min'   =>  0,
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Cantidad de jugadores',
            ),
        ));
        //Cantidad de jugadores 2
        $this->add(array(
            'name'      => 'num_players2',
            'attributes'=> array(
                'id'    => 'num_players2',
                'type'  => 'number',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Cantidad de jugadores',
            ),
        ));
        //fecha del torneo
        $this->add(array(
            'name'      => 'date',
            'attributes'=> array(
                'id'    => 'date',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'value' =>  date('Y-m-j'),
            ),
            'options' => array(
                'label' => 'Fecha del torneo',
            ),
        ));
         //fecha del torneo 2
        $this->add(array(
            'name'      => 'date2',
            'attributes'=> array(
                'id'    => 'date2',
                'type'  => 'input',
                'class' => 'form-control input-sm',
                'value' =>  date('Y-m-j'),
            ),
            'options' => array(
                'label' => 'Fecha del torneo',
            ),
        ));
        //Boton ingresar
        $this->add(array(
            'name' => 'ingresar',
            'type' => 'Submit',
            'attributes' => array('value' => 'Enviar torneo', 'class' => 'btn btn-default'),
        ));
         //Boton buscar
        $this->add(array(
            'name' => 'buscar',
            'type' => 'Submit',
            'attributes' => array('value' => 'Buscar deck', 'class' => 'btn btn-default'),
        ));
          //Boton buscar torneo
        $this->add(array(
            'name' => 'buscar_torneo',
            'type' => 'Submit',
            'attributes' => array('value' => 'Buscar torneo', 'class' => 'btn btn-default'),
        ));
        //Boton actualizar cantidades
        $this->add(array(
            'name' => 'actualizar',
            'type' => 'Submit',
            'attributes' => array('value' => 'Actualizar cantidades', 'class' => 'btn btn-default'),
        ));
         //Boton actualizar torneo
        $this->add(array(
            'name' => 'modificar_torneo',
            'type' => 'Submit',
            'attributes' => array('value' => 'Actualizar torneo', 'class' => 'btn btn-default'),
        ));
        //Boton actualizar cantidades
        $this->add(array(
            'name' => 'nueva_carta',
            'type' => 'Submit',
            'attributes' => array('value' => 'Agregar nueva carta', 'class' => 'btn btn-default'),
        ));
        
         //Boton actualizar datos basicos
        $this->add(array(
            'name' => 'datos_basicos',
            'type' => 'Submit',
            'attributes' => array('value' => 'Actualizar datos básicos', 'class' => 'btn btn-default'),
        ));
        
        //Boton actualizar comentario
        $this->add(array(
            'name' => 'actualizar_comentario',
            'type' => 'Submit',
            'attributes' => array('value' => 'Actualizar comentario', 'class' => 'btn btn-default'),
        ));
        
        //Boton actualizar comentario
        $this->add(array(
            'name' => 'ingresar_casual',
            'type' => 'Submit',
            'attributes' => array('value' => 'Enviar deck', 'class' => 'btn btn-default'),
        ));
        //Boton like
        $this->add(array(
            'name' => 'like',
            'type' => 'Submit',
            'attributes' => array('value' => 'Like', 'class' => 'btn btn-default'),
        ));
        //deck 1
        $this->add(array(
            'name'      => 'deck1',
            'attributes'=> array(
                'id'    => 'textarea1',
                'type'  => 'textarea',
                'class' => 'form-control typeahead',
                'required' => 'required',
                'style' => 'height:240px;',
                'placeholder' => 'Ejemplo: 4 King of Knights, Alfred',
            ),
            'options' => array(
                'label' => '1st',
            ),
        ));
        //deck 2
        $this->add(array(
            'name'      => 'deck2',
            'attributes'=> array(
                'id'    => 'textarea2',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
                'required' => 'required'
            ),
            'options' => array(
                'label' => '2st',
            ),
        ));
        //deck 3
        $this->add(array(
            'name'      => 'deck3',
            'attributes'=> array(
                'id'    => 'textarea3',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
                'required' => 'required'
            ),
            'options' => array(
                'label' => '3st',
            ),
        ));
        //deck 4
        $this->add(array(
            'name'      => 'deck4',
            'attributes'=> array(
                'id'    => 'textarea4',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
                'required' => 'required'
            ),
            'options' => array(
                'label' => '4st',
            ),
        ));
        //deck 5
        $this->add(array(
            'name'      => 'deck5',
            'attributes'=> array(
                'id'    => 'textarea5',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
                'required' => 'required'
            ),
            'options' => array(
                'label' => '5st',
            ),
        ));
        //deck 6
        $this->add(array(
            'name'      => 'deck6',
            'attributes'=> array(
                'id'    => 'textarea6',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
                'required' => 'required'
            ),
            'options' => array(
                'label' => '6st',
            ),
        ));
         //deck 7
        $this->add(array(
            'name'      => 'deck7',
            'attributes'=> array(
                'id'    => 'textarea7',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
                'required' => 'required'
            ),
            'options' => array(
                'label' => '7st',
            ),
        ));
        //deck 8
        $this->add(array(
            'name'      => 'deck8',
            'attributes'=> array(
                'id'    => 'textarea8',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
                'required' => 'required'
            ),
            'options' => array(
                'label' => '8st',
            ),
        ));
        //deck name 
        $this->add(array(
            'name'      => 'deck_name',
            'attributes'=> array(
                'id'    => 'deck_name',
                'type'  => 'input',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
        //deck name 1
        $this->add(array(
            'name'      => 'deck_name1',
            'attributes'=> array(
                'id'    => 'deck_name1',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
        //deck name 2
        $this->add(array(
            'name'      => 'deck_name2',
            'attributes'=> array(
                'id'    => 'deck_name2',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
        //deck name 3
        $this->add(array(
            'name'      => 'deck_name3',
            'attributes'=> array(
                'id'    => 'deck_name3',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
       //deck name 4
        $this->add(array(
            'name'      => 'deck_name4',
            'attributes'=> array(
                'id'    => 'deck_name4',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
        //deck name 5
        $this->add(array(
            'name'      => 'deck_name5',
            'attributes'=> array(
                'id'    => 'deck_name5',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
        //deck name 6
        $this->add(array(
            'name'      => 'deck_name6',
            'attributes'=> array(
                'id'    => 'deck_name6',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        )); 
        //deck name 7
        $this->add(array(
            'name'      => 'deck_name7',
            'attributes'=> array(
                'id'    => 'deck_name7',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
       //deck name 8
        $this->add(array(
            'name'      => 'deck_name8',
            'attributes'=> array(
                'id'    => 'deck_name8',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
                'style' =>  'text-transform: capitalize;'
            ),
            'options' => array(
                'label' => 'Nombre del deck',
            ),
        ));
        //deck player 
        $this->add(array(
            'name'      => 'deck_player',
            'attributes'=> array(
                'id'    => 'deck_player',
                'type'  => 'input',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
        //deck player 1
        $this->add(array(
            'name'      => 'deck_player1',
            'attributes'=> array(
                'id'    => 'deck_player1',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck player 2
        $this->add(array(
            'name'      => 'deck_player2',
            'attributes'=> array(
                'id'    => 'deck_player2',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck player 3
        $this->add(array(
            'name'      => 'deck_player3',
            'attributes'=> array(
                'id'    => 'deck_player3',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck player 4
        $this->add(array(
            'name'      => 'deck_player4',
            'attributes'=> array(
                'id'    => 'deck_player4',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck player 5
        $this->add(array(
            'name'      => 'deck_player5',
            'attributes'=> array(
                'id'    => 'deck_player5',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck player 6
        $this->add(array(
            'name'      => 'deck_player6',
            'attributes'=> array(
                'id'    => 'deck_player6',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck player 7
        $this->add(array(
            'name'      => 'deck_player7',
            'attributes'=> array(
                'id'    => 'deck_player7',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck player 8
        $this->add(array(
            'name'      => 'deck_player8',
            'attributes'=> array(
                'id'    => 'deck_player8',
                'type'  => 'input',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Nombre del jugador',
            ),
        ));
         //deck place
        $this->add(array(
            'name'      => 'deck_place',
            'attributes'=> array(
                'id'    => 'deck_place',
                'type'  => 'number',
                'required'  =>  'required',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Posición',
            ),
        ));
        
          //deck id
        $this->add(array(
            'name'      => 'deck_id',
            'attributes'=> array(
                'id'    => 'deck_id',
                'type'  => 'number',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Deck id',
            ),
        ));
        //torneo id
        $this->add(array(
            'name'      => 'id',
            'attributes'=> array(
                'id'    => 'id',
                'type'  => 'number',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Torneo id',
            ),
        ));
        //Select arquetipo 
        $this->add(array(
        'name'    => 'archetype',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype', 'class' => 'form-control input-sm'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
        //Select arquetipo 1
        $this->add(array(
        'name'    => 'archetype1',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype1', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
         //Select arquetipo 2
        $this->add(array(
        'name'    => 'archetype2',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype2', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
         //Select arquetipo 3
        $this->add(array(
        'name'    => 'archetype3',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype3', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
         //Select arquetipo 4
        $this->add(array(
        'name'    => 'archetype4',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype4', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
         //Select arquetipo 5
        $this->add(array(
        'name'    => 'archetype5',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype5', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
         //Select arquetipo 6
        $this->add(array(
        'name'    => 'archetype6',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype6', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
         //Select arquetipo 7
        $this->add(array(
        'name'    => 'archetype7',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype7', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
         //Select arquetipo 8
        $this->add(array(
        'name'    => 'archetype8',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype8', 'class' => 'form-control', 'required' => 'required'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
        //deck comment
        $this->add(array(
            'name'      => 'deck_comment1',
            'attributes'=> array(
                'id'    => 'deck_comment1',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:400px'
            ),
            'options' => array(
                'label' => '5st',
            ),
        ));
        //deck comment
        $this->add(array(
            'name'      => 'deck_comment',
            'attributes'=> array(
                'id'    => 'editor',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:400px'
            ),
            'options' => array(
            ),
        ));

    }

    public function getArchetypes()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT id_clan, name_clan FROM clans';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();
        foreach ($result as $res) 
        {
            $selectData[$res['id_clan']] = $res['name_clan'];
        }
        return $selectData;
    }

    public function setDbAdapter(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }

    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }
}

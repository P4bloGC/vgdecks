<?php
namespace Modulos\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Captcha;
use Zend\Form\Factory;
use Zend\Db\Adapter\AdapterInterface; 


class CardsForm extends Form
{
    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->setDbAdapter($dbAdapter);
        parent::__construct();

       //nombre carta
        $this->add(array(
            'name'      => 'name',
            'attributes'=> array(
                'id'    => 'name',
                'type'  => 'text',
                'class' => 'form-control input-sm',
                
            ),
            'options' => array(
                'label' => 'Nombre de la carta',
            ),
        ));
        //text carta
        $this->add(array(
            'name'      => 'text',
            'attributes'=> array(
                'id'    => 'name',
                'type'  => 'text',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'text',
            ),
        ));
        //id
        $this->add(array(
            'name'      => 'cardid',
            'attributes'=> array(
                'id'    => 'cardid',
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'onblur' => 'this.value=this.value.toUpperCase()',
        
            ),
            'options' => array(
                'label' => 'Nombre de la carta',
            ),
        ));
        //poder
        $this->add(array(
            'name'      => 'power',
            'attributes'=> array(
                'id'    => 'power',
                'type'  => 'input',
                'class' => 'form-control input-sm',

            ),
            'options' => array(
                'label' => 'Poder',
            ),
        ));
        //shield
        $this->add(array(
            'name'      => 'shield',
            'attributes'=> array(
                'id'    => 'shield',
                'type'  => 'input',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Shield',
            ),
        ));
        //illustrator
        $this->add(array(
            'name'      => 'illustrator',
            'attributes'=> array(
                'id'    => 'illustrator',
                'type'  => 'input',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Illustrator',
            ),
        ));
        //Boton buscar
        $this->add(array(
            'name' => 'buscar',
            'type' => 'Submit',
            'attributes' => array('value' => 'Buscar carta', 'class' => 'btn btn-default'),
        ));
         //Boton ingresar carta
        $this->add(array(
            'name' => 'ingresar',
            'type' => 'Submit',
            'attributes' => array('value' => 'Ingresar nueva carta', 'class' => 'btn btn-default'),
        ));
        //Select triggers
        $this->add(array(
        'name'    => 'triggers',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'triggers', 'class' => 'form-control'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getTriggers(),
            'empty_option'  => 'Ninguno'
        )
        ));
        //Select grade
        $this->add(array(
        'name'    => 'grade',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'grade', 'class' => 'form-control'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => array(
                '0' =>  '0',
                '1' =>  '1',
                '2' =>  '2',
                '3' =>  '3',
                '4' =>  '4'
            ),
        )
        ));
        //Select nation
        $this->add(array(
        'name'    => 'nation',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'nation', 'class' => 'form-control'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getNation(),
            'empty_option'  => 'Seleccionar una nation'
        )
        ));
         //Select arquetipo 
        $this->add(array(
        'name'    => 'archetype',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'archetype', 'class' => 'form-control'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getArchetypes(),
            'empty_option'  => 'Seleccione un arquetipo'
        )
        ));
        //Select uclass 
        $this->add(array(
        'name'    => 'uclass',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'uclass', 'class' => 'form-control'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getUclass(),
            'empty_option'  => 'Seleccione una clase'
        )
        ));
        //Select skillicon 
        $this->add(array(
        'name'    => 'skillicon',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'skillicon', 'class' => 'form-control'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getSkillicon(),
            'empty_option'  => 'Seleccione un skillicon'
        )
        ));
        $this->add(array(
        'name'    => 'race',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'race', 'class' => 'form-control'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getRace(),
            'empty_option'  => 'Seleccione una race'
        )
        ));
        $this->add(array(
        'type' => 'Zend\Form\Element\MultiCheckbox',
        'name' => 'vista',
        'options' => array(
            'label' => 'What do you like ?',
            'value_options' => array(
                 array(
                   'value' => '0',
                   'label' => 'Listado',
                   'selected' => true,
               ),
               array(
                   'value' => '1',
                   'label' => 'Detalle',
               ),
            ),
        )
        ));
        //effect
        $this->add(array(
            'name'      => 'effect',
            'attributes'=> array(
                'id'    => 'effect',
                'type'  => 'textarea',
                'class' => 'form-control',
                'style' => 'height:240px',
            ),
            'options' => array(
                'label' => 'effect',
            ),
        ));
        //file
         $this->add(array(
            'name' => 'img',
            'attributes' => array(
                'type'  => 'file',
                'required'  =>  'required',
            ),
            'options' => array(
                'label' => 'Subir imagen',
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
            $selectData[$res['name_clan']] = $res['name_clan'];
        }
        return $selectData;
    }
    
    public function getNation()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT nation FROM nations';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();
        foreach ($result as $res) 
        {
            $selectData[$res['nation']] = $res['nation'];
        }
        return $selectData;
    }
    
    public function getRace()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT race FROM race';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();
        foreach ($result as $res) 
        {
            $selectData[$res['race']] = $res['race'];
        }
        return $selectData;
    }
    
     public function getTriggers()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT id, triger FROM triggers';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();
        foreach ($result as $res) 
        {
            $selectData[$res['triger']] = $res['triger'];
        }
        return $selectData;
    }

    public function getUclass()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT id, uclass FROM uclass';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();
        foreach ($result as $res) 
        {
            $selectData[$res['uclass']] = $res['uclass'];
        }
        return $selectData;
    }

    public function getSkillicon()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT id, skillicon FROM skillicons';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();
        foreach ($result as $res) 
        {
            $selectData[$res['skillicon']] = $res['skillicon'];
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

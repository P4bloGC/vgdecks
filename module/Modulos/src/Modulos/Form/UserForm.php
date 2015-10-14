<?php
namespace Modulos\Form;

use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Captcha;
use Zend\Form\Factory;
use Zend\Db\Adapter\AdapterInterface; 

class UserForm extends Form
{
    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->setDbAdapter($dbAdapter);
        parent::__construct();

       //username
        $this->add(array(
            'name'      => 'username',
            'attributes'=> array(
                'id'    => 'username',
                'type'  => 'text',
                'class' => 'form-control input-sm',
                
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
        //user id
        $this->add(array(
            'name'      => 'user_id',
            'attributes'=> array(
                'id'    => 'user_id',
                'type'  => 'number',
                'min'   =>  1,
                'class' => 'form-control input-sm',
                
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
        //display name  2
        $this->add(array(
            'name'      => 'display_name2',
            'attributes'=> array(
                'id'    => 'display_name2',
                'type'  => 'text',
                'class' => 'form-control input-sm',
                
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
        //imagen
        $this->add(array(
            'name'      => 'imagen',
            'attributes'=> array(
                'id'    => 'imagen',
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'placeholder' => 'Insertar link de imagen'
            ),
            'options' => array(
                'label' => 'text',
            ),
        ));
        //nombre
        $this->add(array(
            'name'      => 'display_name',
            'attributes'=> array(
                'id'    => 'display_name',
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'readonly' => 'readonly'
            ),
            'options' => array(
                'label' => 'text',
            ),
        ));
        //email
        $this->add(array(
            'name'      => 'email',
            'attributes'=> array(
                'id'    => 'email',
                'type'  => 'text',
                'class' => 'form-control input-sm',
                'readonly' => 'readonly'
            ),
            'options' => array(
                'label' => 'text',
            ),
        ));
        //email 2
        $this->add(array(
            'name'      => 'email2',
            'attributes'=> array(
                'id'    => 'email2',
                'type'  => 'text',
                'class' => 'form-control input-sm',
            ),
            'options' => array(
                'label' => 'text',
            ),
        ));
          //Boton buscar usuario
        $this->add(array(
            'name' => 'buscar_usuario',
            'type' => 'Submit',
            'attributes' => array('value' => 'Buscar usuario', 'class' => 'btn btn-default'),
        ));
        //Boton ingresar carta
        $this->add(array(
            'name' => 'editar',
            'type' => 'Submit',
            'attributes' => array('value' => 'Modificar', 'class' => 'btn btn-default'),
        ));
        //ver nombre
        $this->add(array(
        'type' => 'Zend\Form\Element\Radio',
        'name' => 'ver_name',
        'options' => array(
            'label' => '¿Ocultar nombre?',
            'value_options' => array(
                '0' => 'Si',
                '1' => 'No',
            ),
        ),
    ));
        //ver email
        $this->add(array(
        'type' => 'Zend\Form\Element\Radio',
        'name' => 'ver_email',
        'options' => array(
            'label' => '¿Ocultar email?',
            'value_options' => array(
                '0' => 'Si',
                '1' => 'No',
            ),
        ),
    ));
         //Select tipo de usuario 
        $this->add(array(
        'name'    => 'id_type',
        'type'    => 'Zend\Form\Element\Select',
        'attributes' => array('id' => 'id_type', 'class' => 'form-control input-sm'),
        'options' => array(
            'label'         => 'Dynamic DbAdapter Select',
            'value_options' => $this->getTypeUsers(),
            'empty_option'  => 'Seleccione un tipo de usuario'
        )
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
            'attributes' => array('value' => 'Submit', 'class' => 'btn btn-default'),
        ));
    
        //Mensaje
        $this->add(array(
            'name'      => 'mensaje',
            'attributes'=> array(
                'id'    => 'mensaje',
                'type'  => 'textarea',
                'class' => 'form-control typeahead',
                'required' => 'required',
                'style' => 'height:240px;',
                'placeholder' => 'Escriba aquí su mensaje',
            ),
            'options' => array(
                'label' => 'Mensaje',
            ),
        ));
        
        //email_usuario
        $this->add(array(
            'name'      => 'email_usuario',
            'attributes'=> array(
                'id'    => 'email_usuario',
                'type'  => 'email',
                'class' => 'form-control input-sm',
                'required'  =>  'required',
                'placeholder'   =>  'Ingresa tu email',
            ),
            'options' => array(
                'label' => 'text',
            ),
        ));
        
        //asunto
        $this->add(array(
            'name'      => 'asunto',
            'attributes'=> array(
                'id'    => 'asuntto',
                'type'  => 'text',
                'required'  =>  'required',
                'class' => 'form-control input-sm',
            ),
            'options' => array(
                'label' => 'text',
            ),
        ));
        
         //Boton enviar mensaje
        $this->add(array(
            'name' => 'enviar_mensaje',
            'type' => 'Submit',
            'attributes' => array('value' => 'Enviar mensaje', 'class' => 'btn btn-default'),
        ));
    }
    
    public function getTypeUsers()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT id_type, type FROM type_user';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
        $selectData = array();
        foreach ($result as $res) 
        {
            $selectData[$res['id_type']] = $res['type'];
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

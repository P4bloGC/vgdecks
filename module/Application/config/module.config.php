<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id[/:id2]]]]', 
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'verarquetipo'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/decks/[:action[/:id[/:id2]]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Decks',
                                'action'     => 'verarquetipo'
                        ),
                    ),
           ),
           'vercartas'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/cartas/[:action[/:id]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Cartas',
                                'action'     => 'buscar'
                        ),
                    ),
           ),
            'verdecks'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/decks/[:action[/:id]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Decks',
                                'action'     => 'buscar'
                        ),
                    ),
           ),
            'verdecks2'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/admin/[:action[/:id]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Admin',
                                'action'     => 'decks'
                        ),
                    ),
           ),
             'vertorneos'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/torneos/[:action[/:id]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Torneos',
                                'action'     => 'index'
                        ),
                    ),
           ),
            'vertorneos2'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/admin/[:action[/:id]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Admin',
                                'action'     => 'torneos'
                        ),
                    ),
           ),
            'verusers'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/admin/[:action[/:id]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Admin',
                                'action'     => 'usuarios'
                        ),
                    ),
           ),
             'admin'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/admin',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\Admin',
                                'action'     => 'index'
                        ),
                    ),
           ),
              'contacto'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/contacto',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'contacto'
                        ),
                    ),
           ),
             'decksusuario'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                    //acción  parametro1 parametro2
                        'route' => '/modulos/user/[:action[/:id]]',
                        //'route'    =>  '/modulos/decks/[:action[/:id2]]', 
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulos\Controller\User',
                                'action'     => 'misdecks'
                        ),
                    ),
           ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'es_ES',
        'translation_file_patterns' => array(
            array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../config/language',
                'pattern'  => '%s.php',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/blanco'           => __DIR__ . '/../view/layout/blanco.phtml',
            'layout/admin'           => __DIR__ . '/../view/layout/admin.phtml',
            'numpaginacion'           => __DIR__ . '/../view/layout/numpaginacion.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);

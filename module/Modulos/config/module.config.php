<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Modulos\Controller\Torneos' => 'Modulos\Controller\TorneosController',
            'Modulos\Controller\Decks' => 'Modulos\Controller\DecksController',
            'Modulos\Controller\Cartas' => 'Modulos\Controller\CartasController',
            'Modulos\Controller\About' => 'Modulos\Controller\AboutController',
            'Modulos\Controller\User' => 'Modulos\Controller\UserController',
            'Modulos\Controller\Admin' => 'Modulos\Controller\AdminController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'modulo' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/modulos',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Modulos\Controller',
                        'controller'    => 'Torneos',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id]]]',
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
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Modulo' => __DIR__ . '/../view',
        ),
    ),
);
<?php
return array(

    'controllers' => array(
        'invokables' => array(
            'ApiRest\Controller\CustomersRest' => 'ApiRest\Controller\CustomersRestController',
        ),
    ),
    
    // REST routes
    'router' => array(
        'routes' => array(
            'api-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/customers[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ApiRest\Controller\CustomersRest',
                    ),
                ),
            ),
        ),
    ),
    
    'db' => array(
        'driver' => 'Pdo_Sqlite',
        'database' => __DIR__ . '/../data/development.db'
    ),
    
    // Direct JSON, no need for HTML views
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
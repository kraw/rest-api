<?php
return array(

    'controllers' => array(
        'invokables' => array(
            'RestApi\Controller\CustomersRest' => 'RestApi\Controller\CustomersRestController',
            'RestApi\Controller\CustomerSearchRest' => 'RestApi\Controller\CustomerSearchRestController',
        ),
    ),
    
    // REST routes
    'router' => array(
        'routes' => array(
            'search' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/customers/search',
                    'verb' => 'get',
                    'defaults' => array(
                        'controller' => 'RestApi\Controller\CustomerSearchRest',
                    )
                )
            ),          
            'api-rest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/customers[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'RestApi\Controller\CustomersRest',
                    ),
                ),
            ),
        ),
    ),
    
    // Direct JSON, no need for HTML views
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    
    // DB access; I tried to put it in a global.php file but it wouldnt't get loaded!
    'db' => array(
        'driver' => 'Pdo_Sqlite',
        'database' => __DIR__ . '/../data/db/restapi.db'
    ),
    
    // Will create objects like models and tablegateways
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);
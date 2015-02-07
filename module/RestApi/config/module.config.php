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
);
<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
return [
    'front' => false,
    'admin' => [
        'dashboard' => [
            'label'      => _a('Dashboard'),
            'permission' => [
                'resource' => 'dashboard',
            ],
            'route'      => 'admin',
            'controller' => 'dashboard',
            'action'     => 'index',
        ],
        'detail' => [
            'label'      => _a('Detail'),
            'permission' => [
                'resource' => 'detail',
            ],
            'route'      => 'admin',
            'controller' => 'detail',
            'action'     => 'index',
        ],
        'log'       => [
            'label'      => _a('Log'),
            'permission' => [
                'resource' => 'log',
            ],
            'route'      => 'admin',
            'controller' => 'log',
            'action'     => 'index',
        ],
    ],
];
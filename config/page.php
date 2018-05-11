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
    // Admin section
    'admin' => [
        [
            'title'      => _a('Dashboard'),
            'controller' => 'dashboard',
            'permission' => 'dashboard',
        ],
        [
            'title'      => _a('Detail'),
            'controller' => 'detail',
            'permission' => 'detail',
        ],
        [
            'label'      => _a('Log'),
            'controller' => 'log',
            'permission' => 'log',
        ],
    ],
];
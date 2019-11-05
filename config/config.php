<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

return [
    'category' => [
        [
            'title' => _a('Log'),
            'name'  => 'log',
        ],
    ],
    'item'     => [
        'storage' => [
            'title'       => _a('Log storage engine'),
            'description' => ' ',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        'mysql'    => _a('MySQL'),
                        'mongodb'  => _a('MongoDb ( and  MySQL )'),
                        'arangodb' => _a('ArangoDb ( and  MySQL )'),
                    ],
                ],
            ],
            'filter'      => 'string',
            'value'       => 'news',
            'category'    => 'log',
        ],
    ],
];
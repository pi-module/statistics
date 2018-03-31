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
    // Module meta
    'meta'       => [
        'title'       => _a('Statistics'),
        'description' => _a('Statistics system for pi, save all actions from all modules'),
        'version'     => '0.0.5',
        'license'     => 'New BSD',
        'logo'        => 'image/logo.png',
        'readme'      => 'docs/readme.txt',
        'demo'        => 'http://piengine.org',
        'icon'        => 'fa-area-chart',
    ],
    // Author information
    'author'     => [
        'Name'    => 'Hossein Azizabadi',
        'email'   => 'azizabadi@faragostaresh.com',
        'website' => 'http://piengine.org',
        'credits' => 'Pi Engine Team',
    ],
    // resource
    'resource'   => [
        'database'   => 'database.php',
        'permission' => 'permission.php',
        'page'       => 'page.php',
        'navigation' => 'navigation.php',
    ],
];
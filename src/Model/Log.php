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

namespace Module\Statistics\Model;

use Pi\Application\Model\Model;

class Log extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'section',
            'module',
            'entity',
            'entity_id',
            'action',
            'time_create',
            'uid',
            'ip',
            'source',
            'session',
        ];
}
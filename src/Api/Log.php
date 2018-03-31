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

namespace Module\Statistics\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/**
 * Pi::api('log', 'statistics')->save($module, $entity, $entityId, $action, $source, $time);
 */
class Log extends AbstractApi
{
    public function save($module, $entity, $entityId, $action, $source = 'web', $time = null)
    {
        $log              = Pi::model('log', 'statistics')->createRow();
        $log->module      = $module;
        $log->entity      = $entity;
        $log->entity_id   = $entityId;
        $log->action      = $action;
        $log->time_create = $time ?: time();
        $log->ip          = $_SERVER['REMOTE_ADDR'];
        $log->source      = $source;
        $log->save();
    }
}
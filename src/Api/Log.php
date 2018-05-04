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
 * Pi::api('log', 'statistics')->save($module, $entity, $entityId, $options);
 */
class Log extends AbstractApi
{
    public function save($module, $entity, $entityId = 0, $options = [])
    {
        $log              = Pi::model('log', 'statistics')->createRow();
        $log->module      = $module;
        $log->entity      = $entity;
        $log->entity_id   = $entityId;
        $log->action      = isset($options['action']) ? $options['action'] : 'hits';
        $log->source      = isset($options['source']) ? $options['source'] : 'web';
        $log->section     = isset($options['section']) ? $options['section'] : 'front';
        $log->time_create = isset($options['time']) ? $options['time'] : time();
        $log->ip          = isset($options['ip']) ? $options['ip'] : Pi::user()->getIp();
        $log->uid         = isset($options['uid']) ? $options['uid'] : Pi::user()->getId();
        $log->session     = isset($options['session']) ? $options['session'] : Pi::service('session')->getId();
        $log->save();
    }
}
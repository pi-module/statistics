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
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Set params
        $params = [
            'module'      => $module,
            'entity'      => $entity,
            'entity_id'   => $entityId,
            'action'      => isset($options['action']) ? $options['action'] : 'hits',
            'source'      => isset($options['source']) ? $options['source'] : 'web',
            'section'     => isset($options['section']) ? $options['section'] : 'front',
            'time_create' => isset($options['time']) ? $options['time'] : time(),
            'date_create' => isset($options['time']) ? date('Y-m-d H:i:s', $options['time']) : date('Y-m-d H:i:s'),
            'ip'          => isset($options['ip']) ? $options['ip'] : Pi::user()->getIp(),
            'uid '        => isset($options['uid']) ? $options['uid'] : Pi::user()->getId(),
            'session'     => isset($options['session']) ? $options['session'] : Pi::service('session')->getId(),
        ];

        // Save log on MySql
        $log = Pi::model('log', 'statistics')->createRow();
        $log->assign($params);
        $log->save();

        // Check and save log on ArangoDB or MongoDb
        switch ($config['storage']) {
            case 'mongodb':
                return Pi::service('mongoDb')->insertOne($params, 'statistics');
                break;

            case 'arangodb':
                return Pi::service('arangoDb')->insert($params, 'statistics');
                break;
        }
    }
}
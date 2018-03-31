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

namespace Module\Statistics\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;

class ExampleController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $statisticsEntity   = $this->params('statisticsEntity', 'item');
        $statisticsEntityId = $this->params('statisticsEntityId', 1);
        $statisticsModule   = $this->params('statisticsModule', 'guide');
        $statisticsAction   = $this->params('statisticsAction', 'hits');

        // Set date
        $data = [];

        // Set where
        $where = [
            'module'    => $statisticsModule,
            'entity'    => $statisticsEntity,
            'entity_id' => $statisticsEntityId,
            'action'    => $statisticsAction,
        ];

        // Set hourly visit
        // $data['hourly'] = Pi::api('chart', 'statistics')->data('hourly', 'entity', $where);

        // Set daily visit
        $data['daily'] = Pi::api('chart', 'statistics')->data('daily', 'entity', $where);

        // Set monthly visit
        $data['monthly'] = Pi::api('chart', 'statistics')->data('monthly', 'entity', $where);

        // Set sync url
        $url = Pi::url($this->url('', [
            'controller'         => 'example',
            'action'             => 'sync',
            'confirm'            => 1,
            'statisticsEntity'   => $statisticsEntity,
            'statisticsEntityId' => $statisticsEntityId,
            'statisticsModule'   => $statisticsModule,
            'statisticsAction'   => $statisticsAction,
        ]));


        // Set view
        $this->view()->setTemplate('example-index');
        $this->view()->assign('data', $data);
        $this->view()->assign('url', $url);
    }

    public function syncAction()
    {
        // Get inf0
        $start              = $this->params('start');
        $confirm            = $this->params('confirm', 0);
        $statisticsEntity   = $this->params('statisticsEntity');
        $statisticsEntityId = $this->params('statisticsEntityId');
        $statisticsModule   = $this->params('statisticsModule');
        $statisticsAction   = $this->params('statisticsAction');

        // Check request
        if ($confirm == 0) {
            $url = Pi::url($this->url('', [
                'controller'         => 'example',
                'action'             => 'sync',
                'confirm'            => 1,
                'statisticsEntity'   => $statisticsEntity,
                'statisticsEntityId' => $statisticsEntityId,
                'statisticsModule'   => $statisticsModule,
                'statisticsAction'   => $statisticsAction,
            ]));
        } elseif ($confirm == 1) {
            // Get start time
            if (!$start) {
                $lastUpdate = Pi::api('sync', 'statistics')->lastUpdateEntity(
                    $statisticsModule,
                    $statisticsAction,
                    $statisticsEntity,
                    $statisticsEntityId
                );
                if ($lastUpdate) {
                    $start = strtotime($lastUpdate);
                } else {
                    $start = false;
                }
            }
            // Check start time and do sync
            if (!$start) {
                $message = __('All statistics synced yet, please try sync option tomorrow.');
                return $this->jump([
                    'controller'         => 'example',
                    'action'             => 'sync',
                    'confirm'            => 1,
                    'statisticsEntity'   => $statisticsEntity,
                    'statisticsEntityId' => $statisticsEntityId + 1,
                    'statisticsModule'   => $statisticsModule,
                    'statisticsAction'   => $statisticsAction,
                ], $message);
            } else {
                $end = $start + (86400 * 200);

                if ($end > strtotime(date('Y-m-d', strtotime("-1 days")) . '23:59:59')) {
                    $message = __('All statistics synced yet, please try sync option tomorrow.');
                    $this->jump([
                        'controller'         => 'example',
                        'action'             => 'sync',
                        'confirm'            => 1,
                        'statisticsEntity'   => $statisticsEntity,
                        'statisticsEntityId' => $statisticsEntityId + 1,
                        'statisticsModule'   => $statisticsModule,
                        'statisticsAction'   => $statisticsAction,
                    ], $message);
                }

                // Set start and end
                $start = date('Y-m-d', $start) . ' 00:00:00';
                $end   = date('Y-m-d', $end) . ' 23:59:59';

                // Try sync
                // Pi::api('sync', 'statistics')->entity($statisticsModule, $statisticsAction, $statisticsEntity, $statisticsEntityId, 'hourly', $start, $end);
                Pi::api('sync', 'statistics')->entity($statisticsModule, $statisticsAction, $statisticsEntity, $statisticsEntityId, 'daily', $start, $end);
                Pi::api('sync', 'statistics')->entity($statisticsModule, $statisticsAction, $statisticsEntity, $statisticsEntityId, 'monthly', $start, $end);

                // Set next url
                $url = Pi::url($this->url('', [
                    'controller'         => 'example',
                    'action'             => 'sync',
                    'confirm'            => 1,
                    'start'              => strtotime($start) + (86400 * 200),
                    'statisticsEntity'   => $statisticsEntity,
                    'statisticsEntityId' => $statisticsEntityId,
                    'statisticsModule'   => $statisticsModule,
                    'statisticsAction'   => $statisticsAction,
                ]));
            }
        }

        // Set view
        $this->view()->setTemplate('sync-index');
        $this->view()->assign('url', $url);
        $this->view()->assign('confirm', $confirm);
    }
}
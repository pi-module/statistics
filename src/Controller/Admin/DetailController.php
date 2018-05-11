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

class DetailController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $statisticsModule   = $this->params('statisticsModule');
        $statisticsEntity   = $this->params('statisticsEntity');
        $statisticsEntityId = $this->params('statisticsEntityId');
        $statisticsAction   = $this->params('statisticsAction', 'hits');

        if (empty($statisticsModule) || empty($statisticsEntity)) {
            // Set view
            $this->view()->setTemplate('detail-information');
        } else {
            // Set date
            $data = [];

            // Set where
            $where = [
                'module'    => $statisticsModule,
                'entity'    => $statisticsEntity,
                'entity_id' => $statisticsEntityId,
                'action'    => $statisticsAction,
            ];

            // Set daily visit
            $data['daily'] = Pi::api('chart', 'statistics')->data('daily', 'entity', $where);

            // Set monthly visit
            $data['monthly'] = Pi::api('chart', 'statistics')->data('monthly', 'entity', $where);

            // Set sync url
            $url = Pi::url($this->url('', [
                'controller'         => 'detail',
                'action'             => 'sync',
                //'confirm'            => 1,
                'statisticsEntity'   => $statisticsEntity,
                'statisticsEntityId' => $statisticsEntityId,
                'statisticsModule'   => $statisticsModule,
                'statisticsAction'   => $statisticsAction,
            ]));

            // Set view
            $this->view()->setTemplate('detail-index');
            $this->view()->assign('data', $data);
            $this->view()->assign('url', $url);
        }
    }

    public function syncAction()
    {
        // Get inf0
        $start              = $this->params('start');
        $statisticsModule   = $this->params('statisticsModule');
        $statisticsEntity   = $this->params('statisticsEntity');
        $statisticsEntityId = $this->params('statisticsEntityId');
        $statisticsAction   = $this->params('statisticsAction');

        // Get start time
        if (!$start) {
            $lastUpdate = Pi::api('sync', 'statistics')->lastUpdateEntity(
                $statisticsModule,
                $statisticsEntity,
                $statisticsEntityId,
                $statisticsAction
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
            $detailUrl = Pi::url($this->url('', [
                'controller'         => 'detail',
                'action'             => 'index',
                'statisticsModule'   => $statisticsModule,
                'statisticsEntity'   => $statisticsEntity,
                'statisticsEntityId' => $statisticsEntityId,
                'statisticsAction'   => $statisticsAction,
            ]));
            return $this->jump($detailUrl, $message);
        } else {
            $end = $start + (86400 * 200);

            if ($end > strtotime(date('Y-m-d', strtotime("-1 days")) . '23:59:59')) {
                $message = __('All statistics synced yet, please try sync option tomorrow.');
                $detailUrl = Pi::url($this->url('', [
                    'controller'         => 'detail',
                    'action'             => 'index',
                    'statisticsModule'   => $statisticsModule,
                    'statisticsEntity'   => $statisticsEntity,
                    'statisticsEntityId' => $statisticsEntityId,
                    'statisticsAction'   => $statisticsAction,
                ]));
                return $this->jump($detailUrl, $message);
            } else {
                // Set start and end
                $start = date('Y-m-d', $start) . ' 00:00:00';
                $end   = date('Y-m-d', $end) . ' 23:59:59';

                // Try sync
                Pi::api('sync', 'statistics')->entity($statisticsModule, $statisticsEntity, $statisticsEntityId, $statisticsAction, 'daily', $start, $end);
                Pi::api('sync', 'statistics')->entity($statisticsModule, $statisticsEntity, $statisticsEntityId, $statisticsAction, 'monthly', $start, $end);

                // Set next url
                $url = Pi::url($this->url('', [
                    'controller'         => 'detail',
                    'action'             => 'sync',
                    //'confirm'            => 1,
                    'start'              => strtotime($start) + (86400 * 200),
                    'statisticsModule'   => $statisticsModule,
                    'statisticsEntity'   => $statisticsEntity,
                    'statisticsEntityId' => $statisticsEntityId,
                    'statisticsAction'   => $statisticsAction,
                ]));
            }
        }

        // Set view
        $this->view()->setTemplate('detail-sync');
        $this->view()->assign('url', $url);
    }
}
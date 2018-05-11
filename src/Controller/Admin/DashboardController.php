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

class DashboardController extends ActionController
{
    public function indexAction()
    {
        $data = [];

        // Set hourly visit
        $data['hourly'] = Pi::api('chart', 'statistics')->data('hourly');

        // Set daily visit
        $data['daily'] = Pi::api('chart', 'statistics')->data('daily');

        // Set monthly visit
        $data['monthly'] = Pi::api('chart', 'statistics')->data('monthly');

        // Set sync url
        $url = Pi::url($this->url('', [
            'action'  => 'sync',
        ]));

        // Set view
        $this->view()->setTemplate('dashboard-index');
        $this->view()->assign('data', $data);
        $this->view()->assign('url', $url);
    }

    public function syncAction()
    {
        // Get inf0
        $start   = $this->params('start');

        // Get start time
        if (!$start) {
            $lastUpdate = Pi::api('sync', 'statistics')->lastUpdate();
            if ($lastUpdate) {
                $start = strtotime($lastUpdate);
            } else {
                $start = false;
            }
        }
        // Check start time and do sync
        if (!$start) {
            $message = __('All statistics synced yet, please try sync option tomorrow.');
            return $this->jump(['action' => 'index'], $message);
        } else {
            $end = $start + 86400;

            if ($end > strtotime(date('Y-m-d', strtotime("-1 days")) . '23:59:59')) {
                $message = __('All statistics synced yet, please try sync option tomorrow.');
                $this->jump(['action' => 'index'], $message);
            }

            // Set start and end
            $start = date('Y-m-d', $start) . ' 00:00:00';
            $end   = date('Y-m-d', $end) . ' 23:59:59';

            // Try sync
            Pi::api('sync', 'statistics')->total('hourly', $start, $end);
            Pi::api('sync', 'statistics')->total('daily', $start, $end);
            Pi::api('sync', 'statistics')->total('monthly', $start, $end);

            // Set next url
            $url = Pi::url($this->url('', [
                'action'  => 'sync',
                'start'   => strtotime($start) + 86400,
            ]));
        }

        // Set view
        $this->view()->setTemplate('dashboard-sync');
        $this->view()->assign('url', $url);
    }
}
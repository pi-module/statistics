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

        // Set view
        $this->view()->setTemplate('dashboard-index');
        $this->view()->assign('data', $data);
    }
}
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
 * Pi::api('chart', 'statistics')->data($type, $for, $where);
 */
class Chart extends AbstractApi
{
    public function data($type = 'monthly', $for = 'total', $where = [])
    {
        // Set table
        switch ($for) {
            case 'total':
                $table = [
                    'monthly' => 'total_monthly',
                    'daily'   => 'total_daily',
                    'hourly'  => 'total_hourly',
                ];
                break;

            case 'entity':
                $table = [
                    'monthly' => 'module_monthly',
                    'daily'   => 'module_daily',
                    'hourly'  => 'module_hourly',
                ];
                break;
        }

        // Set color
        $color = [
            'red'    => 'rgb(255, 99, 132)',
            'orange' => 'rgb(255, 159, 64)',
            'yellow' => 'rgb(255, 205, 86)',
            'green'  => 'rgb(75, 192, 192)',
            'blue'   => 'rgb(54, 162, 235)',
            'purple' => 'rgb(153, 102, 255)',
            'grey'   => 'rgb(201, 203, 207)',
        ];

        // Set data array
        $data = [
            'labels'   => [],
            'datasets' => [],
        ];

        // generate date
        switch ($type) {
            // generate hourly date
            case 'hourly':
                $days = ['yesterday', 'twoDaysAgo', 'threeDaysAgo', 'fourDaysAgo', 'fiveDaysAgo', 'sixDaysAgo', 'sevenDaysAgo'];
                foreach ($days as $day) {
                    switch ($day) {
                        case 'yesterday':
                            // Set time
                            $timeStart = sprintf('%s 00:00:00', date('Y-m-d', strtotime('-1 day')));
                            $timeEnd   = sprintf('%s 23:59:59', date('Y-m-d', strtotime('-1 day')));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['hourly'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['hourly'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total = $row->toArray();
                                // Set label
                                $data['labels'][] = $total['hour'];
                                $count[]          = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m/d', strtotime($total['date'])),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['red'],
                                'borderColor'      => $color['red'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'twoDaysAgo':
                            // Set time
                            $timeStart = sprintf('%s 00:00:00', date('Y-m-d', strtotime('-2 day')));
                            $timeEnd   = sprintf('%s 23:59:59', date('Y-m-d', strtotime('-2 day')));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['hourly'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['hourly'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total = $row->toArray();
                                // Set label
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m/d', strtotime($total['date'])),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['orange'],
                                'borderColor'      => $color['orange'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'threeDaysAgo':
                            // Set time
                            $timeStart = sprintf('%s 00:00:00', date('Y-m-d', strtotime('-3 day')));
                            $timeEnd   = sprintf('%s 23:59:59', date('Y-m-d', strtotime('-3 day')));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['hourly'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['hourly'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total = $row->toArray();
                                // Set label
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m/d', strtotime($total['date'])),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['yellow'],
                                'borderColor'      => $color['yellow'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'fourDaysAgo':
                            // Set time
                            $timeStart = sprintf('%s 00:00:00', date('Y-m-d', strtotime('-4 day')));
                            $timeEnd   = sprintf('%s 23:59:59', date('Y-m-d', strtotime('-4 day')));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['hourly'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['hourly'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total = $row->toArray();
                                // Set label
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m/d', strtotime($total['date'])),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['green'],
                                'borderColor'      => $color['green'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'fiveDaysAgo':
                            // Set time
                            $timeStart = sprintf('%s 00:00:00', date('Y-m-d', strtotime('-5 day')));
                            $timeEnd   = sprintf('%s 23:59:59', date('Y-m-d', strtotime('-5 day')));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['hourly'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['hourly'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total = $row->toArray();
                                // Set label
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m/d', strtotime($total['date'])),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['blue'],
                                'borderColor'      => $color['blue'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'sixDaysAgo':
                            // Set time
                            $timeStart = sprintf('%s 00:00:00', date('Y-m-d', strtotime('-6 day')));
                            $timeEnd   = sprintf('%s 23:59:59', date('Y-m-d', strtotime('-6 day')));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['hourly'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['hourly'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total = $row->toArray();
                                // Set label
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m/d', strtotime($total['date'])),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['purple'],
                                'borderColor'      => $color['purple'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'sevenDaysAgo':
                            // Set time
                            $timeStart = sprintf('%s 00:00:00', date('Y-m-d', strtotime('-7 day')));
                            $timeEnd   = sprintf('%s 23:59:59', date('Y-m-d', strtotime('-7 day')));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['hourly'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['hourly'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total = $row->toArray();
                                // Set label
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m/d', strtotime($total['date'])),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['blue'],
                                'borderColor'      => $color['blue'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;
                    }
                }
                break;

            // generate daily date
            case 'daily':
                $months         = ['thisMonth', 'lastMonth', 'threeMonthsAgo', 'fourMonthsAgo', 'fiveMonthsAgo', 'sixMonthsAgo'];
                $data['labels'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
                foreach ($months as $month) {
                    switch ($month) {
                        case 'thisMonth':
                            // Set time
                            $timeStart = date('Y-m-d', strtotime('first day of this month'));
                            $timeEnd   = date('Y-m-d');

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['daily'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['daily'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total   = $row->toArray();
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m', strtotime('first day of this month')),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['red'],
                                'borderColor'      => $color['red'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'lastMonth':
                            // Set time
                            $timeStart = date('Y-m-d', strtotime('first day of -1 month'));
                            $timeEnd   = date('Y-m-d', strtotime('last day of -1 month'));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['daily'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['daily'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total   = $row->toArray();
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m', strtotime('first day of -1 month')),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['orange'],
                                'borderColor'      => $color['orange'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'twoMonthsAgo':
                            // Set time
                            $timeStart = date('Y-m-d', strtotime('first day of -2  month'));
                            $timeEnd   = date('Y-m-d', strtotime('last day of -2 month'));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['daily'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['daily'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total   = $row->toArray();
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m', strtotime('first day of -2 month')),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['yellow'],
                                'borderColor'      => $color['yellow'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'threeMonthsAgo':
                            // Set time
                            $timeStart = date('Y-m-d', strtotime('first day of -3 month'));
                            $timeEnd   = date('Y-m-d', strtotime('last day of -3 month'));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['daily'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['daily'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total   = $row->toArray();
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m', strtotime('first day of -3 month')),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['green'],
                                'borderColor'      => $color['green'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'fourMonthsAgo':
                            // Set time
                            $timeStart = date('Y-m-d', strtotime('first day of -4 month'));
                            $timeEnd   = date('Y-m-d', strtotime('last day of -4 month'));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['daily'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['daily'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total   = $row->toArray();
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m', strtotime('first day of -4 month')),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['blue'],
                                'borderColor'      => $color['blue'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'fiveMonthsAgo':
                            // Set time
                            $timeStart = date('Y-m-d', strtotime('first day of -5 month'));
                            $timeEnd   = date('Y-m-d', strtotime('last day of -5 month'));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['daily'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['daily'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total   = $row->toArray();
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m', strtotime('first day of -5 month')),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['purple'],
                                'borderColor'      => $color['purple'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;

                        case 'sixMonthsAgo':
                            // Set time
                            $timeStart = date('Y-m-d', strtotime('first day of -6 month'));
                            $timeEnd   = date('Y-m-d', strtotime('last day of -6 month'));

                            // Set where
                            $where['date >= ?'] = $timeStart;
                            $where['date <= ?'] = $timeEnd;

                            // Select
                            $select = Pi::model($table['daily'], 'statistics')->select()->where($where);
                            $rowset = Pi::model($table['daily'], 'statistics')->selectWith($select);
                            $count  = [];
                            foreach ($rowset as $row) {
                                $total   = $row->toArray();
                                $count[] = $total['total_count'];
                            }
                            $data['datasets'][] = [
                                'label'            => date('Y/m', strtotime('first day of -6 month')),
                                'fill'             => 'false',
                                'backgroundColor'  => $color['grey'],
                                'borderColor'      => $color['grey'],
                                'pointRadius'      => 5,
                                'pointHoverRadius' => 4,
                                'data'             => $count,
                            ];
                            break;
                    }

                }
                break;

            // generate monthly date
            case 'monthly':
                // Set time
                $timeStart = sprintf('%s-%s-1', date('Y', strtotime('-1 year')), date('m', strtotime('-1 year')));
                $timeEnd   = sprintf('%s-%s-1', date('Y'), date('m'));

                // Set where
                $where['date >= ?'] = $timeStart;
                $where['date <= ?'] = $timeEnd;

                // Select
                $select = Pi::model($table['monthly'], 'statistics')->select()->where($where);
                $rowset = Pi::model($table['monthly'], 'statistics')->selectWith($select);
                $count  = [];
                foreach ($rowset as $row) {
                    $total            = $row->toArray();
                    $data['labels'][] = date('Y/m', strtotime($total['date']));
                    $count[]          = $total['total_count'];
                }
                $data['datasets'][] = [
                    'label'            => __('Total monthly visit'),
                    'fill'             => 'false',
                    'backgroundColor'  => $color['green'],
                    'borderColor'      => $color['green'],
                    'pointRadius'      => 5,
                    'pointHoverRadius' => 4,
                    'data'             => $count,
                ];
                break;
        }

        return $data;
    }
}
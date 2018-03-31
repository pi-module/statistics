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

use DateInterval;
use DatePeriod;
use DateTime;
use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Expression;

/**
 * Pi::api('sync', 'statistics')->total($type, $start, $end);
 * Pi::api('sync', 'statistics')->entity($module, $action, $entity, $entityId, $type, $start, $end);
 * Pi::api('sync', 'statistics')->lastUpdate();
 * Pi::api('sync', 'statistics')->lastUpdateEntity($module, $action, $entity, $entityId);
 */
class Sync extends AbstractApi
{
    public function total($type = 'daily', $start = '', $end = '')
    {
        // Set date
        $start = $start ? $start : Pi::api('sync', 'statistics')->lastUpdate();
        $end   = $end ? $end : date('Y-m-d', strtotime("-1 days")) . ' 23:59:59';

        // Set time list
        $timeArray = [];
        $start     = new DateTime($start);
        $end       = (new DateTime($end));
        $interval  = new DateInterval('P1D');
        $period    = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $timeArray[$dt->format('Y')][$dt->format('m')][$dt->format('d')] = $dt->format('d');
        }

        // Set columns
        $columns = ['count' => new Expression('COUNT(*)')];

        // Set type
        switch ($type) {
            case 'hourly':
                foreach ($timeArray as $year => $yearValue) {
                    foreach ($yearValue as $month => $monthValue) {
                        foreach ($monthValue as $day => $dayValue) {
                            for ($hour = 0; $hour <= 24; $hour++) {
                                // Get count
                                $timeStart = strtotime(sprintf('%s-%s-%s %s:00:00', $year, $month, $day, $hour));
                                $timeEnd   = strtotime(sprintf('%s-%s-%s %s:59:59', $year, $month, $day, $hour));
                                $where     = [
                                    'time_create >= ?' => $timeStart,
                                    'time_create <= ?' => $timeEnd,
                                ];
                                $select    = Pi::model('log', 'statistics')->select()->columns($columns)->where($where);
                                $count     = Pi::model('log', 'statistics')->selectWith($select)->current()->count;

                                // find and save count
                                $where  = [
                                    'date' => sprintf('%s-%s-%s', $year, $month, $day),
                                    'hour' => $hour,
                                ];
                                $select = Pi::model('total_hourly', 'statistics')->select()->where($where);
                                $total  = Pi::model('total_hourly', 'statistics')->selectWith($select)->current();
                                if (!$total) {
                                    $total       = Pi::model('total_hourly', 'statistics')->createRow();
                                    $total->date = sprintf('%s-%s-%s', $year, $month, $day);
                                    $total->hour = $hour;
                                }
                                $total->total_count = $count;
                                $total->save();
                            }
                        }
                    }
                }
                break;

            case 'daily':
                foreach ($timeArray as $year => $yearValue) {
                    foreach ($yearValue as $month => $monthValue) {
                        foreach ($monthValue as $day => $dayValue) {
                            // Get count
                            $timeStart = strtotime(sprintf('%s-%s-%s 00:00:00', $year, $month, $day));
                            $timeEnd   = strtotime(sprintf('%s-%s-%s 23:59:59', $year, $month, $day));
                            $where     = [
                                'time_create >= ?' => $timeStart,
                                'time_create <= ?' => $timeEnd,
                            ];
                            $select    = Pi::model('log', 'statistics')->select()->columns($columns)->where($where);
                            $count     = Pi::model('log', 'statistics')->selectWith($select)->current()->count;

                            // find and save count
                            $where  = [
                                'date' => sprintf('%s-%s-%s', $year, $month, $day),
                            ];
                            $select = Pi::model('total_daily', 'statistics')->select()->where($where);
                            $total  = Pi::model('total_daily', 'statistics')->selectWith($select)->current();
                            if (!$total) {
                                $total       = Pi::model('total_daily', 'statistics')->createRow();
                                $total->date = sprintf('%s-%s-%s', $year, $month, $day);
                            }
                            $total->total_count = $count;
                            $total->save();
                        }
                    }
                }
                break;

            case 'monthly':
                foreach ($timeArray as $year => $yearValue) {
                    foreach ($yearValue as $month => $monthValue) {
                        // Get count
                        $monthEndDay = strtotime('last day of this month', strtotime(sprintf(
                            '%s-%s-01 00:00:00',
                            $year,
                            $month
                        )));
                        $monthEndDay = date('d', $monthEndDay);
                        $timeStart   = strtotime(sprintf('%s-%s-1 00:00:00', $year, $month));
                        $timeEnd     = strtotime(sprintf('%s-%s-%s 00:00:00', $year, $month, $monthEndDay));
                        $where       = [
                            'time_create >= ?' => $timeStart,
                            'time_create <= ?' => $timeEnd,
                        ];
                        $select      = Pi::model('log', 'statistics')->select()->columns($columns)->where($where);
                        $count       = Pi::model('log', 'statistics')->selectWith($select)->current()->count;

                        // find and save count
                        $where  = [
                            'date' => sprintf('%s-%s-1', $year, $month),
                        ];
                        $select = Pi::model('total_monthly', 'statistics')->select()->where($where);
                        $total  = Pi::model('total_monthly', 'statistics')->selectWith($select)->current();
                        if (!$total) {
                            $total       = Pi::model('total_monthly', 'statistics')->createRow();
                            $total->date = sprintf('%s-%s-1', $year, $month);
                        }
                        $total->total_count = $count;
                        $total->save();
                    }
                }
                break;
        }
    }

    public function entity($module, $action, $entity, $entityId, $type = 'daily', $start = '', $end = '')
    {
        // Set date
        $start = $start ? $start : Pi::api('sync', 'statistics')->lastUpdate();
        $end   = $end ? $end : date('Y-m-d', strtotime("-1 days")) . ' 23:59:59';

        // Set time list
        $timeArray = [];
        $start     = new DateTime($start);
        $end       = new DateTime($end);
        $interval  = new DateInterval('P1D');
        $period    = new DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $timeArray[$dt->format('Y')][$dt->format('m')][$dt->format('d')] = $dt->format('d');
        }

        // Set columns
        $columns = ['count' => new Expression('COUNT(*)')];

        // Set type
        switch ($type) {
            /* case 'hourly':
                foreach ($timeArray as $year => $yearValue) {
                    foreach ($yearValue as $month => $monthValue) {
                        foreach ($monthValue as $day => $dayValue) {
                            for ($hour = 0; $hour <= 24; $hour++) {
                                // Get count
                                $timeStart = strtotime(sprintf('%s-%s-%s %s:00:00', $year, $month, $day, $hour));
                                $timeEnd   = strtotime(sprintf('%s-%s-%s %s:59:59', $year, $month, $day, $hour));
                                $where     = [
                                    'time_create >= ?' => $timeStart,
                                    'time_create <= ?' => $timeEnd,
                                    'module'           => $module,
                                    'entity'           => $entity,
                                    'entity_id'        => $entityId,
                                    'action'           => $action,
                                ];
                                $select    = Pi::model('log', 'statistics')->select()->columns($columns)->where($where);
                                $count     = Pi::model('log', 'statistics')->selectWith($select)->current()->count;

                                // find and save count
                                $where  = [
                                    'date'      => sprintf('%s-%s-%s', $year, $month, $day),
                                    'hour'      => $hour,
                                    'module'    => $module,
                                    'entity'    => $entity,
                                    'entity_id' => $entityId,
                                    'action'    => $action,
                                ];
                                $select = Pi::model('module_hourly', 'statistics')->select()->where($where);
                                $total  = Pi::model('module_hourly', 'statistics')->selectWith($select)->current();
                                if (!$total) {
                                    $total       = Pi::model('module_hourly', 'statistics')->createRow();
                                    $total->date = sprintf('%s-%s-%s', $year, $month, $day);
                                    $total->hour = $hour;
                                }
                                $total->module      = $module;
                                $total->entity      = $entity;
                                $total->entity_id   = $entityId;
                                $total->action      = $action;
                                $total->total_count = $count;
                                $total->save();
                            }
                        }
                    }
                }

                break; */

            case 'daily':
                foreach ($timeArray as $year => $yearValue) {
                    foreach ($yearValue as $month => $monthValue) {
                        foreach ($monthValue as $day => $dayValue) {
                            // Get count
                            $timeStart = strtotime(sprintf('%s-%s-%s 00:00:00', $year, $month, $day));
                            $timeEnd   = strtotime(sprintf('%s-%s-%s 23:59:59', $year, $month, $day));
                            $where     = [
                                'time_create >= ?' => $timeStart,
                                'time_create <= ?' => $timeEnd,
                                'module'           => $module,
                                'entity'           => $entity,
                                'entity_id'        => $entityId,
                                'action'           => $action,
                            ];
                            $select    = Pi::model('log', 'statistics')->select()->columns($columns)->where($where);
                            $count     = Pi::model('log', 'statistics')->selectWith($select)->current()->count;

                            // find and save count
                            $where  = [
                                'date'      => sprintf('%s-%s-%s', $year, $month, $day),
                                'module'    => $module,
                                'entity'    => $entity,
                                'entity_id' => $entityId,
                                'action'    => $action,
                            ];
                            $select = Pi::model('module_daily', 'statistics')->select()->where($where);
                            $total  = Pi::model('module_daily', 'statistics')->selectWith($select)->current();
                            if (!$total) {
                                $total       = Pi::model('module_daily', 'statistics')->createRow();
                                $total->date = sprintf('%s-%s-%s', $year, $month, $day);
                            }
                            $total->module      = $module;
                            $total->entity      = $entity;
                            $total->entity_id   = $entityId;
                            $total->action      = $action;
                            $total->total_count = $count;
                            $total->save();
                        }
                    }
                }
                break;

            case 'monthly':
                foreach ($timeArray as $year => $yearValue) {
                    foreach ($yearValue as $month => $monthValue) {
                        // Get count
                        $monthEndDay = strtotime('last day of this month', strtotime(sprintf(
                            '%s-%s-01 00:00:00',
                            $year,
                            $month
                        )));
                        $monthEndDay = date('d', $monthEndDay);
                        $timeStart   = strtotime(sprintf('%s-%s-1 00:00:00', $year, $month));
                        $timeEnd     = strtotime(sprintf('%s-%s-%s 00:00:00', $year, $month, $monthEndDay));
                        $where       = [
                            'time_create >= ?' => $timeStart,
                            'time_create <= ?' => $timeEnd,
                            'module'           => $module,
                            'entity'           => $entity,
                            'entity_id'        => $entityId,
                            'action'           => $action,
                        ];
                        $select      = Pi::model('log', 'statistics')->select()->columns($columns)->where($where);
                        $count       = Pi::model('log', 'statistics')->selectWith($select)->current()->count;

                        // find and save count
                        $where  = [
                            'date'      => sprintf('%s-%s-1', $year, $month),
                            'module'    => $module,
                            'entity'    => $entity,
                            'entity_id' => $entityId,
                            'action'    => $action,
                        ];
                        $select = Pi::model('module_monthly', 'statistics')->select()->where($where);
                        $total  = Pi::model('module_monthly', 'statistics')->selectWith($select)->current();
                        if (!$total) {
                            $total       = Pi::model('module_monthly', 'statistics')->createRow();
                            $total->date = sprintf('%s-%s-1', $year, $month);
                        }
                        $total->module      = $module;
                        $total->entity      = $entity;
                        $total->entity_id   = $entityId;
                        $total->action      = $action;
                        $total->total_count = $count;
                        $total->save();
                    }
                }
                break;
        }
    }

    public function lastUpdate()
    {
        $order  = ['date DESC'];
        $limit  = 1;
        $select = Pi::model('total_daily', 'statistics')->select()->order($order)->limit($limit);
        $row    = Pi::model('total_daily', 'statistics')->selectWith($select)->current();
        if ($row) {
            $row        = $row->toArray();
            $lastUpdate = sprintf('%s 23:59:59', $row['date']);
        } else {
            $columns = ['time_create'];
            $order   = ['time_create ASC'];
            $limit   = 1;
            $select  = Pi::model('log', 'statistics')->select()->columns($columns)->order($order)->limit($limit);
            $row     = Pi::model('log', 'statistics')->selectWith($select)->current();
            if ($row) {
                $row        = $row->toArray();
                $lastUpdate = date('Y-m-d H:i:s', $row['time_create']);;
            } else {
                $lastUpdate = false;
            }
        }

        return $lastUpdate;
    }

    public function lastUpdateEntity($module, $action, $entity, $entityId)
    {
        $where  = [
            'module'    => $module,
            'action'    => $action,
            'entity'    => $entity,
            'entity_id' => $entityId,
        ];
        $order  = ['date DESC'];
        $limit  = 1;
        $select = Pi::model('module_daily', 'statistics')->select()->where($where)->order($order)->limit($limit);
        $row    = Pi::model('module_daily', 'statistics')->selectWith($select)->current();
        if ($row) {
            $row        = $row->toArray();
            $lastUpdate = sprintf('%s 23:59:59', $row['date']);
        } else {
            $columns = ['time_create'];
            $order   = ['time_create ASC'];
            $limit   = 1;
            $select  = Pi::model('log', 'statistics')->select()->columns($columns)->where($where)->order($order)->limit($limit);
            $row     = Pi::model('log', 'statistics')->selectWith($select)->current();
            if ($row) {
                $row        = $row->toArray();
                $lastUpdate = date('Y-m-d H:i:s', $row['time_create']);;
            } else {
                $lastUpdate = false;
            }
        }

        return $lastUpdate;
    }
}
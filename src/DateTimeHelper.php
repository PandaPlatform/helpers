<?php

/*
 * This file is part of the Panda Helpers Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Support\Helpers;

use DateInterval;
use DateTime;
use Exception;

/**
 * Class DateTimeHelper
 * @package Panda\Support\Helpers
 */
class DateTimeHelper
{
    /**
     * @param DateTime $date1
     * @param DateTime $date2
     *
     * @return int 0=equal -1 date1 is smaller and +1 date1 is greater
     */
    public static function compareDateWithoutTime($date1, $date2)
    {
        $date1 = DateTime::createFromFormat('Y-m-d H:i:s', $date1->format('Y-m-d') . ' 00:00:00');
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s', $date2->format('Y-m-d') . ' 00:00:00');
        if ($date1 == $date2) {
            return 0;
        }
        if ($date1 < $date2) {
            return -1;
        }

        return 1;
    }

    /**
     * @param DateTime $date1
     * @param DateTime $date2
     *
     * @return int 0=equal -1 date1 is smaller and +1 date1 is greater
     */
    public static function compareTimeWithoutDate($date1, $date2)
    {
        $date1 = DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 ' . $date1->format('H:i:s'));
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 ' . $date2->format('H:i:s'));
        if ($date1 == $date2) {
            return 0;
        }
        if ($date1 < $date2) {
            return -1;
        }

        return 1;
    }

    /**
     * @param DateTime $date
     * @param int      $hour
     * @param int      $minute
     * @param int      $second
     *
     * @return int
     */
    public static function compareDateWithHourMinuteSecond($date, $hour, $minute, $second = 0)
    {
        $date1 = DateTime::createFromFormat('Y-m-d H:i:s', sprintf('2000-01-01 %02d:%02d:%02d', $hour, $minute, $second));

        return self::compareTimeWithoutDate($date, $date1);
    }

    /**
     * Count working days between the two given dates.
     * The comparison includes both begin and end dates.
     *
     * @param DateTime $beginDate
     * @param DateTime $endDate
     * @param int[]    $nonWorkingDays array containing the non working days (i.e. [6,7] for Saturday and Sunday)
     *
     * @return int Number of working days
     * @throws Exception
     */
    public static function getWorkingDaysCount($beginDate, $endDate, $nonWorkingDays = [])
    {
        $workdays = 0;
        if (!is_null($beginDate) && !is_null($endDate)) {
            if ($beginDate > $endDate) {
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $oneDayInterval = new DateInterval('P1D');
            $date = clone $beginDate;
            while (self::compareDateWithoutTime($date, $endDate) <= 0) {
                $weekday = $date->format('N');
                if (!in_array($weekday, $nonWorkingDays)) {
                    $workdays++;
                }
                $date->add($oneDayInterval);
            }
        }

        return $workdays;
    }

    /**
     * @param DateTime $beginDate
     * @param DateTime $endDate
     *
     * @return int
     */
    public static function getMinutesCount($beginDate, $endDate)
    {
        // Get date diff
        $diff = $endDate->diff($beginDate);

        // Calculate all minutes
        $minutes = $diff->days * 24 * 60;
        $minutes += $diff->h * 60;
        $minutes += $diff->i;

        return $minutes;
    }

    /**
     * @param DateTime $beginDate
     * @param DateTime $endDate
     *
     * @return int
     */
    public static function getSecondsCount($beginDate, $endDate)
    {
        // Get minutes count
        $minutes = static::getMinutesCount($beginDate, $endDate);

        // Get seconds
        $diff = $endDate->diff($beginDate);
        $seconds = $diff->s;

        // Return all seconds
        return $seconds + $minutes * 60;
    }

    /**
     * Method find the average time for given array of datetime objects
     * it ignores any non DateTIme values in the given array
     *
     * @param DateTime[] $array
     *
     * @return string|bool
     */
    public static function getAverageTimeForArrayOfDateTimes($array)
    {
        if (!is_array($array)) {
            return false;
        }

        $averageTime = null;

        $averageSecondsFromDateBeginSum = 0;
        $processedItemsCounter = 0;

        foreach ($array as $datetime) {
            if (!is_object($datetime) || !($datetime instanceof DateTime)) {
                continue;
            }

            $hours = (int)$datetime->format('H');
            $minutes = (int)$datetime->format('i');
            $seconds = (int)$datetime->format('s');

            $averageSecondsFromDateBeginSum += $hours * 3600 + $minutes * 60 + $seconds;
            $processedItemsCounter += 1;
        }

        $averageTime = gmdate('H:i:s', intval($averageSecondsFromDateBeginSum / $processedItemsCounter) % 86400);

        return $averageTime;
    }
}

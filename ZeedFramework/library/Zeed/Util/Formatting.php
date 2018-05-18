<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-9-23
 * @version    SVN: $Id$
 */

class Zeed_Util_Formatting
{

    /**
     * Determines the difference between two timestamps.
     *
     * The difference is returned in a human readable format such as "1 hour",
     * "5 mins", "2 days".
     *
     * @param int $from Unix timestamp from which the difference begins.
     * @param int $to Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
     *
     * @return string Human readable time difference.
     */
    public static function humanTimeDiff( $from, $to = null ) {
        if ( null === $to ) {
            $to = time();
        } else {
            $to = (int) $to;
        }

        $diff = (int) abs($to - $from);
        if ($diff <= 3600) {
            $mins = round($diff / 60);
            if ($mins <= 1) {
                $mins = 1;
            }
            /* translators: min=minute */
//            $since = sprintf(_n('%s min', '%s mins', $mins), $mins);
            $since = sprintf('%s 分钟前', $mins);
        } else if (($diff <= 86400) && ($diff > 3600)) {
            $hours = round($diff / 3600);
            if ($hours <= 1) {
                $hours = 1;
            }
//            $since = sprintf(_n('%s hour', '%s hours', $hours), $hours);
            $since = sprintf('%s 小时前', $hours);
        } elseif ($diff >= 86400) {
            $days = round($diff / 86400);
            if ($days <= 1) {
                $days = 1;
            }
//            $since = sprintf(_n('%s day', '%s days', $days), $days);
            $since = sprintf('%s 天前', $days);
        }

        return $since;
    }
}

// End ^ Native EOL ^ encoding

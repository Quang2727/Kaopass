<?php

/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class StringHelper extends Helper {

    public static function cutString($str, $len, $more) {
        if ($str == "" || $str == NULL) {
            return $str;
        }
        if (is_array($str)) {
            return $str;
        }
        $str = strip_tags($str, '');
        $str = trim($str);
        if (mb_strlen($str) <= $len) {
            return $str;
        }
        $str = mb_substr($str, 0, $len);
        if ($str != "") {
            if (!mb_substr_count($str, " ")) {
                if ($more)
                    $str .= " ...";
                return $str;
            }
            while (mb_strlen($str) && ($str[mb_strlen($str) - 1] != " ")) {
                $str = mb_substr($str, 0, -1);
            }
            $str = mb_substr($str, 0, -1);
            if ($more) {
                $str .= " ...";
            }
        }
        return $str;
    }

    /**
     * Trim long text into new text has $max_cut characters + dotdotdot
     *
     * @param string $str
     * @param int $max_cut
     * @return string
     * @author Mai Nhut Tan
     * @since 2013/09/27
     */
    function getShortText($str, $max_cut = 160) {
        $str = $this->supertrim(( strip_tags($str)));
        $length = mb_strlen($str, 'utf-8');

        if ($length > $max_cut) {
            return mb_substr($str, 0, $max_cut, 'utf-8') . '...';
        }

        return $str;
    }

    /**
     * Trim long text into new text has $max_cut characters + dotdotdot
     *
     * @param string $str
     * @param int $max_cut
     * @return string
     * @author Mai Nhut Tan
     * @since 2013/09/27
     */
    function getSimpleText($str) {
        $str = strip_tags($str);

        return $str;
    }

    /**
     * Trim all whitespaces, tabs, linebreaks
     *
     * @param string $str
     * @return string
     * @author Mai Nhut Tan
     * @since 2013/09/27
     */
    function supertrim($str) {
        return trim(preg_replace('/\s+/', ' ', $str));
    }

    /**
     * Convert absolute time to relative time
     *
     * @param string $datetime
     * @return string
     * @author Mai Nhut Tan
     * @since 2013/09/27
     */
    function displayPostTime($datetime) {
        $unix = strtotime($datetime);

        if (!$unix)
            return 'không xác định';

        $diff = time() - $unix;

        if ($diff > 0) {
            //second diff
            if ($diff < 86400) {
                if ($diff < 60)
                    return 'Vừa tức thời';

                if ($diff < 120)
                    return '1 phút trước';

                if ($diff < 3600)
                    return floor($diff / 60) . 'phút trước';

                return floor($diff / 3600) . 'giờ trước';
            }

            // day diff
            $diff = floor($diff / 86400);

            if ($diff < 7)
                return $diff . 'ngày trước';

            //week diff
            $wdiff = floor($diff / 7);
            if ($wdiff < 5)
                return $wdiff . 'tuần trước';

            if ($diff < 30)
                return '1ヶ月前';

            //month diff
            $diff = floor($diff / 30);
            if ($diff < 12)
                return $diff . 'tháng trước';

            if ($diff == 12) {
                return '1 năm';
            }

            return 'hơn 1 năm trước';
        } else if ($diff == 0) {
            return '今';
        } else {
            return date(DATETIME_FORMAT, $unix);
        }
    }

    public static function convert_url($string) {
        $bad_characters_filter = '/[-_\s\;\/\?\:\@\&\=\+\$\,\"\<\>\.\!\~\*\'\“\”\(\)]+/iu';
        $string = mb_substr(trim(preg_replace($bad_characters_filter, ' ', $string)), 0, QUESTION_SUMMARY_TITLE_LENGTH);
        $string = iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $string);
        return str_replace(' ', '-', $string);
    }

    /**
     * Convert string to friendly slug
     *
     * @author Mai Nhut Tan
     * @since 2013/09/27
     */
    function convertURL($string) {
        return urlencode($string);//$this->convert_url($string);
    }

    // 一覧表示用にタイトルを短くする
    function make_title_for_list($str, $max_cut = 160) {
        $length = mb_strlen($str, 'utf-8');

        if ($length > $max_cut) {
            $output = mb_substr($str, 0, $max_cut, 'utf-8');

            return $output . '...';
        }

        return $str;
    }
}

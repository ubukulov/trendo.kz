<?php
/**
 * Created by PhpStorm.
 * User: Dauren
 * Date: 22.04.2019
 * Time: 0:42
 */

/**
 * @param $number integer
 * @return  string
 */
function format_price($number)
{
    return number_format($number, 0, '', '&thinsp;');
}
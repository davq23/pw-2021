<?php

namespace Utils;

/**
 * Rules that allow insertion of variables in URL path
 * 
 * @author David Quintero <davidquinterogranadillo@gmail.com>
 * @package Utils
 * @version 1.0
 */
class RouteRules 
{
    /**
     * Allows insertion of a natural number (1, 2, 3, ...) in the URL path
     *
     * @param string $data String to check
     * @return bool
     */
    public static function isNatural($data) 
    {
        return is_numeric($data) && (int)$data > 0;
    }

    /**
     * Allows the use of several URL path fragments alongside a plain one
     *
     * @param string $data String to check
     * @return bool
     */
    public static function isEmpty($data) 
    {
        return empty($data);
    }
}
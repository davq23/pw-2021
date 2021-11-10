<?php

namespace Utils;

/**
 * Miscellaneous functions
 *
 * @author David Quintero <davidquinterogranadillo@gmail.com>
 * @package Utils
 * @version 1.0
 */
class Utils
{

    /**
     * Utility for knowing if a string ends with a sub-string
     *
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    public static function endsWith($haystack, $needle): bool {
        $length = strlen($needle);
        return $length > 0 ? substr($haystack, -$length) === $needle : true;
    }

    public static function toSnakeCase(string $subject): string {
        return preg_replace('([a-z])([A-Z]+)', '$1_$2', $subject);
    }

}

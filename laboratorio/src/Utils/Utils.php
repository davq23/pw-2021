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
    * Utility for knowing if a string ends with a substring
    *
    * @param string $haystack
    * @param string $needle
    * @return boolean
    */
   public static function endsWith($haystack, $needle) 
   {
       $length = strlen($needle);
       return $length > 0 ? substr($haystack, -$length) === $needle : true;
   }
}
<?php

use SIENSIS\KpaCrud\Libraries\KpaCrud;

if (!function_exists('renderJS')) {
    /**
     * renderJS - This helper function generates all HTML script tags in pages
     *
     * @param  array<int,string> $js_files  URL Array of javascript files needed
     * @param  array<int,string> $hidden  file_id Array of javascript files to hide @since 1.4.1a
     * @return void
     * 
     * @see \SIENSIS\KpaCrud\Libraries\KpaCrud::$js_files
     * 
     * @package KpaCrud\Helpers
     * 
     * @version 1.4.1a
     * @author JMFXR <dev@siensis.com>
     */
    function renderJS($js_files, $hidden)
    {
        echo PHP_EOL . "<!-- JS SIENSIS KpaCrud Library -->" . PHP_EOL;
        // dd($js_files, $hidden);
        foreach ($js_files as $key => $file) {
            if (!in_array($key, $hidden)) {
                echo "<script type='text/javascript' src='$file'></script>" . PHP_EOL;

            }
        }
    }
}

if (!function_exists('renderCSS')) {
    /**
     * renderCSS - This helper function generates all HTML link rel stylesheet tags in pages
     *
     * @param  array<int,string> $css_files URL Array of CSS files needed
     * @param  array<int,string> $hidden  file_id Array of css files to hide @since 1.4.1a
     * @return void
     * 
     * @see \SIENSIS\KpaCrud\Libraries\KpaCrud::$css_files
     * 
     * @package KpaCrud\Helpers
     * 
     * @version 1.0
     * @author JMFXR <dev@siensis.com>
     */
    function renderCSS($css_files, $hidden)
    {
        echo PHP_EOL . "<!-- CSS SIENSIS KpaCrud Library -->" . PHP_EOL;
        foreach ($css_files as $key => $file) {
            if (!in_array($key, $hidden)) 
                echo "<link rel='stylesheet' type='text/css' href='$file'>" . PHP_EOL;
        }
    }
}

if (!function_exists('str_ends_with')) {
    /**
     * str_ends_with - Check if a string ends with a given substring. Function available in PHP 8
     *
     * @param string $haystack  The string to search in
     * @param string $needle    The substring to search for in the haystack param
     * @return boolean          Returns true if haystack ends with needle, false otherwise
     *
     * @package KpaCrud\Helpers
     *      
     * @see https://www.php.net/manual/es/function.str-ends-with.php
     * @version 1.3.0.2a
     * @author JMFXR <dev@siensis.com>
     */
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, -$needle_len));
    }
}

/* End of file crudrender_helper.php (20220322) */
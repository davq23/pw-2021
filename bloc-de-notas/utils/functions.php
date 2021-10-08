<?php

function filterSanitizePath(string $path) : string
{
    $pathSanitized = str_replace('../', '', $path);
    return str_replace('./', '', $pathSanitized);
}

// Does not support flag GLOB_BRACE
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

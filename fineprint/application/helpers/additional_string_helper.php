<?php
function strtolowerunderscore($string)
{
    $string = preg_replace("/\s+/", " ", $string);
    $string = str_replace(" ", "_", $string);
    $string = preg_replace("/[^A-Za-z0-9_]/","",$string);
    $string = strtolower($string);

    return $string;
}

function format_bytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function clean_file_path($path)
{
    // This function cleans a file path and removes any '..' characters
    do
    {
        $path = str_replace('//', '/', $path);
    }
    while(strpos($path, '//') !== FALSE);

    do
    {
        $path = str_replace('..', '', $path);
    }
    while(strpos($path, '..') !== FALSE);
    
    return $path;
}

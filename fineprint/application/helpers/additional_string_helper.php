<?php
function strtolowerunderscore($string)
{
    $string = preg_replace("/\s+/", " ", $string);
    $string = str_replace(" ", "_", $string);
    $string = preg_replace("/[^A-Za-z0-9_]/","",$string);
    $string = strtolower($string);

    return $string;
}
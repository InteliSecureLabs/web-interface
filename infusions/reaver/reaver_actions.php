<?php

require_once("iwlist_parser.php");
require_once("reaver_functions.php");

if (isset($_GET['reaver']))
{
    if (isset($_GET['install']))
    {

        $cmd = "opkg update && opkg install reaver ";
        if (isset($_GET['onusb']))
        {
            $cmd.=" --dest usb &";
        }
        else
        {
            $cmd .=" &";
        }
        echo shell_exec($cmd);
    }
    else if (isInstalled("reaver")==1)
    {
        if (isset($_GET['refresh']))
        {
            if (isset($_GET['bssid']) && $_GET['bssid'] != "")
            {
                $bssid = $_GET['bssid'];
                $cmd = "cat /pineapple/logs/reaver-$bssid.log";

                exec($cmd, $output);
                foreach ($output as $outputline)
                {
                    echo ("$outputline\n");
                }
            }
            else
                echo "No BSSID provided...";
        }
        else if (isset($_GET['start']))
        {
//            if (isRunning('reaver') == "1")
//                exec('killall reaver && wait 5');

            $victime = $_GET['victime'];
            $int = $_GET['interface'];



            if ($victime != "" && $int != "")
            {
                $cmd = "reaver -i $int -b $victime ";

                if (isset($_GET['c']) && $_GET['c'] == "true")
                {
                    if (isset($_GET['ch']) && $_GET['ch'] != "")
                        $cmd .=" -f -c " . $_GET['ch'];
                }

                if (isset($_GET['S']) && $_GET['S'] == "true")
                    $cmd .=" -S ";

                if (isset($_GET['a']) && $_GET['a'] == "true")
                    $cmd .=" -a ";

                $cmd .= " -vv >> /pineapple/logs/reaver-$victime.log -D &";

                exec($cmd);

                echo "Attack Started !";
                echo "\n$cmd";
            }
            else
            {
                echo "no target ($victime) or interface ($int) provided";
            }
        }
        else if (isset($_GET['stop']))
        {
            echo exec("kill `ps -ax | grep reaver | grep -v -e grep | grep -v -e tail | grep -v -e logread | grep -v -e php | awk {'print $1'}`");

            echo "Attack Stopped !";
        }
    }
    else
    {
        echo 'reaver is not installed...';
    }
}
else if (isset($_GET['interface']) && $_GET['interface'] != "")
{
    $interface = $_GET['interface'];
    if (isset($_GET['up']))
    {
        shell_exec("ifconfig " . $interface . " up &");
        echo "$interface up";
    }
    else if (isset($_GET['down']))
    {
        shell_exec("ifconfig " . $interface . " down &");
        echo "$interface down";
    }
    else if (isset($_GET['mon_start']))
    {
        shell_exec("airmon-ng start " . $interface . " &");
        echo "Monitor started on $interface";
    }
    else if (isset($_GET['mon_stop']))
    {
        shell_exec("airmon-ng stop " . $interface . " &");
        echo "Monitor stopped on $interface";
    }
    else if (isset($_GET['available_ap']))
    {
        // List APs
        $iwlistparse = new iwlist_parser();
        $p = $iwlistparse->parseScanDev($interface);

        if (!empty($p))
        {
            echo '<em>Click on a row to select the target AP</em>';
            echo '<table id="survey-grid" class="grid" cellspacing="0">';
            echo '<tr class="header">';
            echo '<td>SSID</td>';
            echo '<td>BSSID</td>';
            echo '<td>Signal level</td>';
            echo '<td colspan="2">Quality level</td>';
            echo '<td>Ch</td>';
            echo '<td>Encryption</td>';
            echo '<td>Cipher</td>';
            echo '<td>Auth</td>';
            echo '</tr>';
        }
        else
        {
            echo "<em>No access-point found, please retry or change the wifi interface used (in left panel)...</em>";
        }

        for ($i = 1; $i <= count($p[$interface]); $i++)
        {
            $quality = $p[$interface][$i]["Quality"];

            if ($quality <= 25)
                $graph = "red";
            else if ($quality <= 50)
                $graph = "yellow";
            else if ($quality <= 100)
                $graph = "green";
            echo '<tr class="odd" name="' . $p[$interface][$i]["ESSID"] . ',' . $p[$interface][$i]["Address"] . ',' . $p[$interface][$i]["Channel"] . '">';

            echo '<td>' . $p[$interface][$i]["ESSID"] . '</td>';
//            $MAC_address = explode(":", $p[$interface][$i]["Address"]);


            echo '<td>' . $p[$interface][$i]["Address"] . '</td>';
            echo '<td>' . $p[$interface][$i]["Signal level"] . '</td>';
            echo "<td>" . $quality . "%</td>";
            echo "<td width='150'>";
            echo '<div class="graph-border">';
            echo '<div class="graph-bar" style="width: ' . $quality . '%; background: ' . $graph . ';"></div>';
            echo '</div>';
            echo "</td>";
            echo '<td>' . $p[$interface][$i]["Channel"] . '</td>';

            if ($p[$interface][$i]["Encryption key"] == "on")
            {
                $WPA = strstr($p[$interface][$i]["IE"], "WPA Version 1");
                $WPA2 = strstr($p[$interface][$i]["IE"], "802.11i/WPA2 Version 1");

                $auth_type = str_replace("\n", " ", $p[$interface][$i]["Authentication Suites (1)"]);
                $auth_type = implode(' ', array_unique(explode(' ', $auth_type)));

                $cipher = $p[$interface][$i]["Pairwise Ciphers (2)"] ? $p[$interface][$i]["Pairwise Ciphers (2)"] : $p[$interface][$i]["Pairwise Ciphers (1)"];
                $cipher = str_replace("\n", " ", $cipher);
                $cipher = implode(',', array_unique(explode(' ', $cipher)));

                if ($WPA2 != "" && $WPA != "")
                    echo '<td>WPA,WPA2</td>';
                else if ($WPA2 != "")
                    echo '<td>WPA2</td>';
                else if ($WPA != "")
                    echo '<td>WPA</td>';
                else
                    echo '<td>WEP</td>';

                echo '<td>' . $cipher . '</td>';
                echo '<td>' . $auth_type . '</td>';
            }
            else
            {
                echo '<td>None</td>';
                echo '<td>&nbsp;</td>';
                echo '<td>&nbsp;</td>';
            }

            echo '</tr>';
        }
    }
}
else if (isset($_GET['list']))
{
    if (isset($_GET['radio']))
    {
        $wifi_interfaces=  getWirelessInterfaces();
        echo '<table>';

        for ($i = 0; $i < count($wifi_interfaces); $i++)
        {
            $interface = $wifi_interfaces[$i];
            //$mac_address = exec("uci get wireless.radio" . $i . ".macaddr");
            //$disabled = exec("uci get wireless.radio" . $i . ".disabled");

            $mode = exec("uci get wireless.@wifi-iface[" . $i . "].mode");
            //$interface = exec("ifconfig | grep -i " . $mac_address . " | awk '{print $1}'");
            //$interface = $interface != "" ? $interface : "-";

            $disabled = exec("ifconfig  | grep " . $interface . " | awk '{ print $1}'");
            $disabled = $disabled != "" ? false : true;

            echo '<tr>';

            echo '<td>radio' . $i . '</td>';
            echo '<td>' . $interface . ' (mode ' . $mode . ')</td>';
            echo '<td>';
            if (!$disabled)
                echo '<font color="lime"><strong>enabled</strong></font>&nbsp;[<a id="down_int" href="javascript:down_int(\'' . $interface . '\');">Disable</a>]';
            else
                echo '<font color="red"><strong>disabled</strong></font>&nbsp;[<a id="enable_int" href="javascript:up_int(\'' . $interface . '\');">Enable</a>]';

            echo '</td>';

            echo '</tr>';
        }
        echo '</table>';
    }
    else if (isset($_GET['int']))
    {
        $wifi_interfaces=  getEnabledWirelessInterfaces();
        if ($wifi_interfaces==NULL)
        {
            echo 'No enabled wifi interface found...';
        }
        else
        {
            echo '<select id="interfaces">';
            foreach ($wifi_interfaces as $value)
            {
                echo '<option value="' . $value . '">' . $value . '</option>';
            }
            echo '</select>&nbsp;';
            echo '[<a id="start_mon" href="javascript:start_mon();">Start mon</a>]';
        }
    }
    else if (isset($_GET['mon']))
    {
        $monitored_interfaces=getMonitoredInterfaces();
        if ($monitored_interfaces==NULL)
        {
            echo 'No monitor interface found...';
        }
        else
        {
            echo '<select id="mon">';
            foreach ($monitored_interfaces as $value)
            {

                echo '<option value="' . $value . '">' . $value . '</option>';
            }
            echo '</select>&nbsp;';
            echo '[<a id="stop_mon" href="javascript:stop_mon();">Stop mon</a>]';
        }
    }
}
else
{
    echo "NO TRIGGER; please report to hackrylix@gmail.com...";
}
?>

<?php
require_once ("reaver_functions.php");
?>
<html>
    <head>
        <title>Pineapple Control Center - <?php echo getModuleName() . " [v" . getModuleVersion() . "]"; ?></title>
        <script type="text/javascript" src="reaver.js"></script>
        <script type="text/javascript" src="/includes/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="reaver.css" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    </head>
    <body bgcolor="black" text="white" alink="green" vlink="green" link="green">
        <script type="text/javascript">
            $(document).ready(function(){ init(); });
        </script>

        <?php include("/pineapple/includes/navbar.php"); ?>
        <div id="modulePanel">
            <div id="leftPanel">
                <div class="panelTitle"> <?php echo getModuleName() . " [v" . getModuleVersion() . "]"; ?></div>
                <div class="panelContent">
                    <?php
                    /* @var $is_reaver_installed boolean */
                    if (isInstalled("reaver")==1)
                    {
                        echo "reaver";
                        echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font>";
                    }
                    else
                    {
                        echo "reaver";
                        echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font>";
                        echo '<input type="button" onclick="install_reaver()" value="install reaver" />';
                        if (isUsbMounted())
                            echo '[<input id="onusb" type="checkbox" value="1" /> on usb]';
                        echo "<br /><br />";
                    }

                    echo '<hr />';
                    echo 'Radio interfaces :<br /><div id="list_radio"></div><hr />';
                    echo 'Available wifi interfaces :<br /><div id="list_int"></div><hr />';
                    echo 'Monitored wifi interfaces :<br /><div id="list_mon"></div><hr />';
                    echo 'Log :<br /><textarea id="log" disabled="disabled" cols="30" rows="10"></textarea>';
                    echo '<div align="center" ><hr /><p><img src="loading.gif" id="loading" /></p></div>';
                    ?>


                </div>

            </div>

            <div id="rightPanel">
                <div class="panelTitle">Main</div>
                <div class="panelContent">
                    <?php
                    echo '<div>AP List | <input type="button" id="refresh_ap" onclick="refresh_available_ap();" value="Scan AP" /></div>';
                    echo '<div id="list_ap"></div>';
                    echo '<hr/>';
                    ?>

                    Victime :<br />
                    <input type="text" disabled style="background-color: black; color: white;" id="ap" />
                    <input type="text" disabled style="background-color: black; color: white;" id="victime" />
                    <input type="text" size="2" disabled style="background-color: black; color: white;" id="channel" />
                    <input type="button" id="button_start" onclick="start_attack();" value="Attack target" />
                    <input type="button" id="button_stop"  onclick="stop_attack();" value="Stop attack" />
                    <br />
                    <input type="checkbox" id="option_S" />&nbsp;Use small DH keys to improve crack speed<br />
                    <input type="checkbox" id="option_a" />&nbsp;Auto detect the best advanced options for the target AP<br />
                    <input type="checkbox" id="option_c" />&nbsp;Set the 802.11 channel for the interface (implies -f : Disable channel hopping)<br />
                    <hr />
                    Output :<br />
                    <input type="button" id="button_refresh"  onclick="refresh_output();" value="Refresh output" />
                    <!--<input type="button" id="button_clear"  onclick="clear_output();" value="Clear output" />--> 
                    |&nbsp;Auto-refresh <select id="auto_time">
                        <option value="2000">2 sec</option>
                        <option value="5000" selected="selected">5 sec</option>
                        <option value="10000">10 sec</option>
                        <option value="15000">15 sec</option>
                        <option value="20000">20 sec</option>
                        <option value="25000">25 sec</option>
                        <option value="30000">30 sec</option>
                    </select>
                    <input type="button" id="start_ar" onclick="start_refresh();" value="On" /><input type="button" id="stop_ar" onclick="stop_refresh();" value="Off" />
                    <br />
                    <textarea id='output' disabled="disabled" name='output' cols='80' rows='20'></textarea>
                    <br />
                </div>

            </div>
        </div>

    </body>
</html>

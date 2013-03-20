<?php

if(isset($_GET[goback])){
echo '<script type="text/javascript">
        window.location = "index.php"
        </script>';
}


$filename =$_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file! Empty?');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 $message = $strings["ssh-fileUpdate"]." " . $filename . "<br /><br />";
}

if (isset($_GET[deletekey])) {
        exec("rm -rf /etc/dropbear/id_rsa");
        $message = "<pre>".$strings["ssh-deleteKey-message"]." /etc/dropbear/id_rsa</pre>";
        }

if (isset($_GET[generatekey])) {
        exec("rm -rf /etc/dropbear/id_rsa");
        exec("dropbearkey -t rsa -f /etc/dropbear/id_rsa");
        $message = "<pre>".$strings["ssh-generateKey-message"]." /etc/dropbear/id_rsa</pre>";
        }

if (isset($_GET[connect])) {
        if (exec("ps aux | grep [s]sh | grep -v -e ssh.php | grep -v grep") == "") {
                exec("echo /pineapple/ssh/ssh-connect.sh | at now");
                sleep(2);
        } else {
        }
}

if (isset($_GET[disconnect])) {
        if (exec("ps aux | grep [s]sh | grep -v -e ssh.php | grep -v grep") == "") {
        } else {
                exec("kill `ps aux | grep -v -e ssh.php | awk '/[s]sh/{print $1}'`");
                sleep(2);
        }
}

if (isset($_GET[enablekeepalive])) {
        if (exec("grep ssh-keepalive.sh /etc/crontabs/root") == "") {
                exec("echo '*/5 * * * * /pineapple/ssh/ssh-keepalive.sh' >> /etc/crontabs/root");
		exec("/etc/init.d/crond restart");
                $message = "<pre>".$strings["ssh-keepAlive-enabled"]." <a href='jobs.php'><b>".$strings["ssh-jobs"]."</b></a>.</pre>";
        } else {
        }
}

if (isset($_GET[disablekeepalive])) {
        exec("sed -i '/ssh-keepalive.sh/d' /etc/crontabs/root");
        exec("/etc/init.d/crond restart");
}

$sshonboot = (exec("grep ssh-connect.sh /etc/rc.local"));

if (isset($_GET[enable])) {
	if (exec("grep ssh-connect.sh /etc/rc.local") == "") {
		exec("sed -i '/exit 0/d' /etc/rc.local");
		exec("echo /pineapple/ssh/ssh-connect.sh >> /etc/rc.local");
		exec("echo exit 0 >> /etc/rc.local");
		$sshonboot = "true";
	} else {
	}
}

if (isset($_GET[disable])) {
	exec("sed -i '/ssh-connect.sh/d' /etc/rc.local");
	$sshonboot = "";
}


?>


<div class=sidePanelLeft style="min-width:400px;">

<div class=sidePanelTitle><?=$strings["ssh-options"]?></div>
<div class=sidePanelContent>
<?php
if ($sshonboot != ""){
echo $strings["ssh-boot"]." <font color=\"lime\"><b>".$strings["ssh-enabled"]."</b></font>.&nbsp; | <a href=\"index.php?ssh&disable&disablekeepalive\"><b>".$strings["ssh-disable"]."</b></a><br />";
} else { echo $strings["ssh-boot"]." <font color=\"red\"><b>".$strings["ssh-disabled"]."</b></font>. | <a href=\"index.php?ssh&enable\"><b>".$strings["ssh-enable"]."</b></a><br />"; }
if (exec("grep ssh-keepalive.sh /etc/crontabs/root") == "") {
echo $strings["ssh-persist"]." <font color='red'><b>".$strings["ssh-disabled"]."</b></font>. | <a href='index.php?ssh&enablekeepalive&enable'><b>".$strings["ssh-enable"]."</b></a><br />";
} else { echo $strings["ssh-persist"]." <font color='lime'><b>".$strings["ssh-enabled"]."</b></font>.&nbsp; | <a href='index.php?ssh&disablekeepalive'><b>".$strings["ssh-disable"]."</b></a><br />"; }
if (exec("ps aux | grep [s]sh | grep -v -e ssh.php | grep -v grep") == "") {
         echo $strings["ssh-session"]." <font color=\"red\"><b>".$strings["ssh-disconnected"]."</b></font> | <a href=\"index.php?ssh&connect\"><b>".$strings["ssh-connect"]."</b></a><br /><br />";
} else {
        echo $strings["ssh-session"]." <font color=\"lime\"><b>".$strings["ssh-connected"]."</b></font>. &nbsp; | <a href=\"index.php?ssh&disconnect\"><b>".$strings["ssh-disconnect"]."</b></a><br /><br />";
}

?>
</div>

</div>
<br />
<br />
<br />
<br />
<br />
<br />
<div class=content>
<?php echo "<font color=lime>".$message."</font><br />" ?>
<div class=contentTitle><?=$strings["ssh-connectCommand"]?></div>
<div class=contentContent>
<?php
$filename = "/pineapple/ssh/ssh-connect.sh";
  $fh = fopen($filename, "r") or die("Could not open file! Empty?");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<form action='index.php?ssh' method= 'post' ><input type='hidden' name='filename' value='ssh/ssh-connect.sh'>
<input type='text' name='newdata' style='width:100%' value='$data' /><center><input type='submit' value='".$strings["ssh-connectCommand-button"]."'></form>";
?>
</div><br /><br />

<div class=contentTitle><?=$strings["ssh-publicKey"]?></div>
<div class=contentContent>
<?php
echo "<small>".$strings["ssh-publicKey-note"]."</small><br /><br />";
        echo "<textarea name='newdata' rows='8' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>";
        $cmd="dropbearkey -f /etc/dropbear/id_rsa -y";
        exec ($cmd, $output);
        foreach($output as $outputline) {
        echo ("$outputline\n");}
        echo "</textarea>";
?>
<br /><br /><?=$strings["ssh-publicKey-noKey"]?> <a href="index.php?ssh&generatekey"><b><?=$strings["ssh-publicKey-generate"]?></b></a> | <a href="index.php?ssh&deletekey"><b><?=$strings["ssh-publicKey-delete"]?></b></a> <?=$strings["ssh-publicKey-delete-note"]?></a><br />
</div><br /><br />

<div class=contentTitle><?=$strings["ssh-knownHosts"]?></div>
<div class=contentContent>
<?php

$filename = "/root/.ssh/known_hosts";
$fh = fopen($filename, "r") or die("Could not open file!");
$data = fread($fh, filesize($filename)) or die("Could not read file!");
fclose($fh);
echo "
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' rows='8' style='min-width:100%; background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/root/.ssh/known_hosts'>
<center><input type='submit' value='".$strings["ssh-knownHosts-button"]."'>
</form>";
?>

</div><br /><br />
</div>
<div class=content style="min-width:950px">
<div class=contentTitle><?=$strings["ssh-help"]?></div>
<div class=contentContent>
<pre>
<?=$strings["ssh-help-content"]?>


<b>SSH</b>
Uage: ssh [options] [user@]host[/port] [command]
Options are:
-p &lt;remoteport&gt;
-l &lt;username&gt;
-t    Allocate a pty
-T    Don't allocate a pty
-N    Don't run a remote command
-f    Run in background after auth
-y    Always accept remote host key if unknown
-s    Request a subsystem (use for sftp)
-i &lt;identityfile&gt;   (multiple allowed)
-A    Enable agent auth forwarding
-L <[listenaddress:]listenport:remotehost:remoteport> Local port forwarding
-g    Allow remote hosts to connect to forwarded ports
-R <[listenaddress:]listenport:remotehost:remoteport> Remote port forwarding
-W &lt;receive_window_buffer&gt; (default 24576, larger may be faster, max 1MB)
-K &lt;keepalive&gt;  (0 is never, default 0)
-I &lt;idle_timeout&gt;  (0 is never, default 0)
-J &lt;proxy_program&gt; Use program pipe rather than TCP connection

<b>AutoSSH</b>
usage: autossh [-V] [-M monitor_port[:echo_port]] [-f] [SSH_OPTIONS]

    -M specifies monitor port. May be overridden by environment
       variable AUTOSSH_PORT. 0 turns monitoring loop off.
       Alternatively, a port for an echo service on the remote
       machine may be specified. (Normally port 7.)
    -f run in background (autossh handles this, and does not
       pass it to ssh.)
    -V print autossh version and exit.

Environment variables are:
    AUTOSSH_GATETIME    - how long must an ssh session be established
                          before we decide it really was established
                          (in seconds)
    AUTOSSH_LOGFILE     - file to log to (default is to use the syslog
                          facility)
    AUTOSSH_LOGLEVEL    - level of log verbosity
    AUTOSSH_MAXLIFETIME - set the maximum time to live (seconds)
    AUTOSSH_MAXSTART    - max times to restart (default is no limit)
    AUTOSSH_MESSAGE     - message to append to echo string (max 64 bytes)
    AUTOSSH_PATH        - path to ssh if not default
    AUTOSSH_PIDFILE     - write pid to this file
    AUTOSSH_POLL        - how often to check the connection (seconds)
    AUTOSSH_FIRST_POLL  - time before first connection check (seconds)
    AUTOSSH_PORT        - port to use for monitor connection
    AUTOSSH_DEBUG       - turn logging to maximum verbosity and log to
                          stderr

</pre>
</div><br /><br />


</div>

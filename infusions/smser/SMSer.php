<?php

$action = $_GET['action'];

$account = $_POST['account'];
$password = $_POST['password'];
$number = $_POST['number'];
$gateway = $_POST['gateway'];
$cmd1 = $_POST['cmd1'];
$cmd2 = $_POST['cmd2'];
$cmd3 = $_POST['cmd3'];
$sendtest = $_POST['sendtest'];

$cronjob = $_POST['cronjob'];

function installpython() {
  exec('opkg install python -d usb');
  echo '<font color="lime"><b>Python has been installed!</b></font><br />';
}

function installpyssl() {
  exec('opkg install python-openssl -d usb');
  echo '<font color="lime"><b>Python-openssl has been installed!</b></font><br />';
}

function setuplogs() {
  $logpath = realpath("SMSer.log");
  exec("ln -s " . $logpath . " /pineapple/logs/");
  echo '<font color="lime"><b>Log file has been configured!</b></font><br />';
}

function getInfo($account, $password, $number, $gateway, $cmd1, $cmd2, $cmd3, $sendtest) {
  $script = realpath("smser.py");
  exec('echo "*/1 * * * * '.$script.' -e '.$account.' -p '.$password.' -n '.$number.'@'.$gateway.' --cmd1 \"'.$cmd1.'\" --cmd2 \"'.$cmd2.'\" --cmd3 \"'.$cmd3.'\"" > cron.conf');
  if ($sendtest == "yes") {
    exec($script.' -e '.$account.' -p '.$password.' -n '.$number.'@'.$gateway.' -m "Hey there! You got to love newbi3/frozenjava"');
  }
  echo '<font color="lime"><b>Info submitted! Now submit the cron job below when you are read!</b></font>';
}

function clearlogs() {
  exec('echo "[SMSer LOGS]" > SMSer.log');
  echo '<font color="lime"><b>The logs have been cleared!</b></font>';
}

function resetcron() {
  exec('echo "*/1 * * * * SMSer.py -e address@gmail.com -p gmailPassword -n 1234567890@texting.com" > cron.conf');
  echo '<font color="lime"><b>The cron job has been reset</b></font>';
}

function submitcron($cronjob) {
  exec('echo "' . $cronjob . '" >> /etc/crontabs/root');
  exec('/etc/init.d/cron restart');
  echo '<font color="lime"><b>Your cronjob was submited!</b></font>';
}

?>
<html>
  <head>
    <title>Pineapple File Browser</title>
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <link rel="stylesheet" type="text/css" href="/includes/styles.css" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  </head>
  <body>
    <?php include("/pineapple/includes/navbar.php"); ?><br />
    <div class="sidepanelLeft">
      <div class="sidepanelTitle">Configuration</div>
      <div class="sidepanelContent">
        <center>
          <form method="post" action="SMSer.php?action=info"> 
            <label>Gmail</label>
            <input type="text" name="account">
            <br /><br />
            <label>Password</label>
            <input type="password" name="password">
            <br /><br />
            <label>Number</label>
            <input type="text" name="number">
            <br /><br />
            <label>Gateway</label>
            <input type="text" name="gateway">
            <br /><br />
            <label>Command 1</label>
            <input type="text" name="cmd1">
            <br /><br />
            <label>Command 2</label>
            <input type="text" name="cmd2">
            <br /><br />
            <label>Command 3</label>
            <input type="text" name="cmd3">
            <br /><br />
            <input type="checkbox" name="sendtest" value="yes">Send a test message
            <br /><br />
            <input type="submit" value="Submit Info">
          </form>
          <a href="http://en.wikipedia.org/wiki/List_of_SMS_gateways">External list of SMS gateways</a>
        </center>
      </div>
    </div>
    <div class="content">
      <div class="contentTitle">Information</div>
      <div class="contentContent">
        <center>
          <?php
            if ($action == "python") {
              installpython();
            } elseif ($action == "pyssl") {
              installpyssl();
            } elseif ($action == "linklogs") {
              setuplogs();
            } elseif ($action == "info") {
              getInfo($account, $password, $number, $gateway, $cmd1, $cmd2, $cmd3, $sendtest);
            } elseif ($action == "resetjob") {
              resetcron();
            } elseif ($action == "clearlogs") {
              clearlogs();
            } elseif ($action == "submitjob") {
              submitcron($cronjob);
            } else {
              echo "";
            }
          ?>
        </center>
        <h3>Key Words</h3>
        <h5>YOU CAN USE KEY WORDS IN ANY ORDER AS LONG AS THEY ARE IN THE MESSAGE</h5>
        <p>
          hey, hello, hola, yo -- Generate random greeting and responds<br />
          open front door -- Activates karma and responds<br />
          close front door -- Deactivates karma and responds<br />
          preaheat oven, put oven -- Activates dns spoofing and responds<br />
          off oven, stop oven -- Deactivates dns spoofing and responds<br />
          dog, cat -- Runs custom command 1 and responds<br />
          car, van, truck -- Runs custom command 2 and responds<br />
          hamster, mouse, rat -- Runs custom command 3 and responds<br />
          thank, thanks, gracias, appreciate -- Responds saying "you are welcome"<br />
        </p>
        <h5>EXAMPLE: "Hey joe, can you put a pizza in the oven and feed a mouse to the cat for me? Thanks"</h5>
        <h3>Switches</h3>
        <p>
          --verbose : Run program verbosely<br />
          --noreply : Do not send a reply<br />
          --nolog : Do not keep loops<br />
          --cmd1 "COMMAND" : Custom command 1<br />
          --cmd2 "COMMAND" : Custom command 2<br />
          --cmd3 "COMMAND" : Custom command 3<br />
          -e account@gmail.com : Your gmail account name<br />
          -p gmailpassword : Your gmail password<br />
          -n number@gateway : Your phonenumber@smsgateway<br />
          -m "message" : Send a specific message<br />
          -i INBOX : Specify an inbox to check, default is inbox<br />
        </p>
        <h5>NOTE: If your string has a space in it wrap it in " "</h5>
        <b><u>CRON</u></b> <a href="SMSer.php?action=resetjob">Reset</a>
        <form action="SMSer.php?action=submitjob" method="post">
          <textarea name="cronjob" cols="82" rows="5"><?php $f=fopen("cron.conf", 'r'); $data=fread($f, filesize("cron.conf")); fclose($f); echo $data;?></textarea>
          <br />
          <input type="submit" value="Submit Job">
        </form>
        <b><u>LOGS</u></b> <a href="SMSer.php?action=clearlogs">Clear</a><br />
        <textarea cols="82" rows="10"><?php $f=fopen("SMSer.log", 'r'); $data=fread($f, filesize("SMSer.log")); fclose($f); echo $data;?></textarea>
      </div>
    </div>
    <div class="sidepanelRight">
      <div class="sidepanelTitle">Dependencies</div>
      <div class="sidepanelContent">
        <?php
          if (is_dir("/usb") == false) {
            echo '<center><font color="red"><b>FLASH DRIVE NEEDED</b></font></center><br />';
          }
          if (file_exists("/usb/usr/bin/python")) {
            echo 'Python : <font color="lime"><b>INSTALLED</b></font><br />';
          } else {
            echo 'Python : <a href="SMSer.php?action=python"><font color="red"><b>NOT INSTALLED</b></font></a><br />';
          }
          if (file_exists("/usb/usr/lib/opkg/info/python-openssl.list")) {
            echo 'Python-openssl : <font color="lime"><b>INSTALLED</b></font><br />';
          } else {
            echo 'Python-openssl : <a href="SMSer.php?action=pyssl"><font color="red"><b>NOT INSTALLED</b></font></a><br />';
          }
          if (file_exists("/pineapple/logs/SMSer.log")) {
            echo 'Logs : <font color="lime"><b>CONFIGURED</b></font>';
          } else {
            echo 'Logs : <a href="SMSer.php?action=linklogs"><font color="red"><b>CONFIGURE</b></font></a>';
          }
        ?>
      </div>
    </div>
  </body>
</html>

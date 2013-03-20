<?php
$status = "done";

if(isset($_GET[status])){
	echo $status; exit(0);
}

if(isset($_GET[size])){
	$size = $_GET[size];
	$name = $_GET[name];
	$dest = $_GET[dest];
	$got = 0;

	if($dest == "usb"){
		//get size on USB
		$got = exec("du /usb/tmp/infusions/mk4-module-$name* | awk '{print $1}'");
	}else{
		//get size on internal
		$got = exec("du /tmp/infusions/mk4-module-$name* | awk '{print $1}'");
	}

        if($got >= $size){ echo "complete"; exit(0); }

        $percent = round(($got/$size)*100, 1);

        echo "<br />[ ";
        for($i = 0; $i <= $percent/2; $i++){
                if($i == 0){

                }else echo "|";
        }
        for($i = 0; $i <= (100-$percent)/2; $i++){
                echo "&nbsp;";
        }
        echo " ]<br />";
        echo "$percent %"; exit(0);
}

exec("echo \"sh /pineapple/modules/installer.sh $_GET[name] $_GET[version] $_GET[dest] $_GET[md5]\" | at now");



?>

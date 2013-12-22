<?php

require("networkmanager_vars.php");
require("iwlist_parser.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/networkmanager.js"></script>
<link rel="stylesheet" type="text/css" href="css/networkmanager.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript">
	$(document).ready(function(){ 
		refresh_available_ap();
	});
	
	function refresh_available_ap() {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();

		$.ajax({
			type: "GET",
			data: "available_ap&int="+$("#interfaces").val(),
			url: "networkmanager_interfaces.php",
			success: function(msg){
				$("#list_ap").html(msg);
				$("#refresh_text").html(''); clearInterval(showDots);
				$('#survey-grid tr').click(function() { 
					window.opener.$("#" + "<?php echo $_GET['w']?>").val($(this).attr("name"));
					window.close();
					return false;
				});
			}
		});
	}
</script>
	
<?php

echo '<select id="interfaces" name="interfaces">';
foreach($wifi_interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>&nbsp;';
echo '[<a id="refresh" href="javascript:refresh_available_ap();">Refresh</a>] <span id="refresh_text"></span><br/><br/>';

echo '<em>Click on row to add AP name to SSID field</em><br/><br/>';

echo '<div id="list_ap"></div>';
	
?>

</body>
</html>
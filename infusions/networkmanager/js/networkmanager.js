var showDots;

var showLoadingDots = function() {
    if (!$("#loadingDots").length>0) return false;
    showDots = setInterval(function(){            
        var d = $("#loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

function init() {
	refresh();
	refresh_interfaces();
}

function toggle_options(interface) {
	
	// Channel
	if($('#'+interface+'_mode').val() == "ap")
	{
		$("#"+interface+"_channel option[value=auto]").attr('disabled','disabled')
		if($("#"+interface+"_channel").val() == "auto")
			$("#"+interface+"_channel").val(1);
	}
	else if($('#'+interface+'_mode').val() == "sta")
	{
		$("#"+interface+"_channel option[value=auto]").removeAttr('disabled');
		$("#"+interface+"_channel").val(0);
	}
	
	// Security
	switch($('#'+interface+'_security_mode').val()) {
		case 'wep':
			$('#'+interface+'_key_div').show();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').hide();
			$('#'+interface+'_wep_mode_div').show();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'psk': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').show();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'wpa': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			if($('#'+interface+'_mode').val() == "sta")
			{
				$('#'+interface+'_eap_type_div').show();
				$('#'+interface+'_identity_div').show();
				$('#'+interface+'_password_div').show();
				$('#'+interface+'_server_div').hide();
				$('#'+interface+'_port_div').hide();
				$('#'+interface+'_shared_div').hide();
			}
			else if($('#'+interface+'_mode').val() == "ap")
			{
				$('#'+interface+'_server_div').show();
				$('#'+interface+'_port_div').show();
				$('#'+interface+'_shared_div').show();
				$('#'+interface+'_eap_type_div').hide();
				$('#'+interface+'_identity_div').hide();
				$('#'+interface+'_password_div').hide();
			}
		break;
		case 'psk2':
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').show();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'wpa2': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide(); 
			if($('#'+interface+'_mode').val() == "sta")
			{
				$('#'+interface+'_eap_type_div').show();
				$('#'+interface+'_identity_div').show();
				$('#'+interface+'_password_div').show();
				$('#'+interface+'_server_div').hide();
				$('#'+interface+'_port_div').hide();
				$('#'+interface+'_shared_div').hide();
			}
			else if($('#'+interface+'_mode').val() == "ap")
			{
				$('#'+interface+'_server_div').show();
				$('#'+interface+'_port_div').show();
				$('#'+interface+'_shared_div').show();
				$('#'+interface+'_eap_type_div').hide();
				$('#'+interface+'_identity_div').hide();
				$('#'+interface+'_password_div').hide();
			}
		break;
		case 'mixed-psk':
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').show();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
		case 'mixed-wpa':
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').show();
			$('#'+interface+'_wep_mode_div').hide();
			if($('#'+interface+'_mode').val() == "sta")
			{
				$('#'+interface+'_eap_type_div').show();
				$('#'+interface+'_identity_div').show();
				$('#'+interface+'_password_div').show();
				$('#'+interface+'_server_div').hide();
				$('#'+interface+'_port_div').hide();
				$('#'+interface+'_shared_div').hide();
			}
			else if($('#'+interface+'_mode').val() == "ap")
			{
				$('#'+interface+'_server_div').show();
				$('#'+interface+'_port_div').show();
				$('#'+interface+'_shared_div').show();
				$('#'+interface+'_eap_type_div').hide();
				$('#'+interface+'_identity_div').hide();
				$('#'+interface+'_password_div').hide();
			}
		break;
		case 'none': 
			$('#'+interface+'_key_div').hide();
			$('#'+interface+'_shared_key_div').hide();
			$('#'+interface+'_encryption_div').hide();
			$('#'+interface+'_wep_mode_div').hide();
			$('#'+interface+'_eap_type_div').hide();
			$('#'+interface+'_identity_div').hide();
			$('#'+interface+'_password_div').hide();
			$('#'+interface+'_server_div').hide();
			$('#'+interface+'_port_div').hide();
			$('#'+interface+'_shared_div').hide();
		break;
	}
}

function refresh() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		async: false,
		url: "networkmanager_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function refresh_interfaces() {
	$('#sidePanelContent').load('networkmanager_interfaces.php?interface');
}

function interface_toggle(interface, action) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "action="+action+"&int="+interface,
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
		}
	});

}

function save(data) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: $("#"+data).serialize(),
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			$("#commit").css("color","green");
			$("#revert").css("color","red");
		}
	});
}

function detect() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "detect=1",
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
		}
	});
}

function release(interface) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "release=1&int="+interface,
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
		}
	});
}

function connect(interface) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "connect=1&int="+interface,
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
		}
	});
}

function macchanger(interface, radio) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "macchanger=1&int="+interface+"&phy="+radio,
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
		}
	});
}

function remove(interface) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "remove=1&phy="+interface,
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
			$("#commit").css("color","green");
			$("#revert").css("color","red");
		}
	});
}

function commit() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "commit=1",
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
			$("#commit").css("color","black");
			$("#revert").css("color","black");
		}
	});
}

function revert() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
		
	$.ajax({
		type: "POST",
		data: "revert=1",
		url: "networkmanager_actions.php",
		success: function(msg){
			$("#refresh_text").html(msg); clearInterval(showDots);
			refresh(); refresh_interfaces();
			$("#commit").css("color","black");
			$("#revert").css("color","black");
		}
	});
}

function show_ap(what) {

	var top = 30;
	var left = Math.floor(screen.availWidth * .66) - 10;
	var width = 800
	var height = 400

	var win = window.open("ap.php?w="+what, 'Available AP', 'top=' + top + ',left=' + left + ',width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}

function execute(what) {

	var top = 30;
	var left = Math.floor(screen.availWidth * .66) - 10;
	var width = 700
	var height = 400

	var win = window.open("execute.php?cmd="+escape(what), 'Execute', 'top=' + top + ',left=' + left + ',width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}
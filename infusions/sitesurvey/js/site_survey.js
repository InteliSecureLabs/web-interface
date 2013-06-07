var auto_refresh;
var showDots;

var showLoadingDots = function() {
    clearInterval(showDots);
	if (!$("#loadingDots").length>0) return false;
    showDots = setInterval(function(){            
        var d = $("#loadingDots");
        d.text().length >= 3 ? d.text('') : d.append('.');
    },300);
}

function init() {
	
	refresh(0);
	refresh_history();
	refresh_captures();
	refresh_config();
	
	$("#tabs2 ul").idTabs();

	$("#auto_refresh").toggle(function() {
			$("#auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			$('#auto_what').attr('disabled', 'disabled');
						
			if($("#auto_what").val() == 1)
				$('#output').load('site_survey_actions.php?background_refresh=start&int='+$("#interfaces").val()+'&mon='+$("#monitorInterfaces").val());
			
			auto_refresh = setInterval(
			function ()
			{
				refresh(0);
			},
			$("#auto_time").val());
		}, function() {
			$("#auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
			$('#auto_what').removeAttr('disabled');
			
			if($("#auto_what").val() == 1)		
				$('#output').load('site_survey_actions.php?background_refresh=stop');
							
            clearInterval(auto_refresh);
			auto_refresh = null;
	});
}

function monitor_toggle(action) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "monitor&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "site_survey_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			if(action == "stop")
				$('#output').val(action+" monitor "+$("#monitorInterfaces").val()+"...");
			else
				$('#output').val(action+" monitor on "+$("#interfaces").val()+"...");
			refresh_monitors();
		}
	});
}

function interface_toggle(action) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "interface&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "site_survey_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			$('#output').val(action+" "+$("#interfaces").val()+"...");
			refresh_interfaces();
		}
	});
}

function auto_toggle() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "auto&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "site_survey_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			$('#output').val("toggle "+$("#interfaces").val()+"...");
		}
	});
}

function refresh_interfaces() {
	$('#interfaces_l').load('site_survey_interfaces.php?interface');
}

function refresh_monitors() {
	$('#monitorInterface_l').load('site_survey_interfaces.php?monitor');
}

function refresh(clients) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "POST",
		data: "mon="+$("#monitorInterfaces").val()+"&int="+$("#interfaces").val()+"&clients="+clients,
		url: "site_survey_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function refresh_captures() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "captures",
		url: "site_survey_attacks.php",
		success: function(msg){
			$("#content_captures").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function refresh_history() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "history",
		url: "site_survey_attacks.php",
		success: function(msg){
			$("#content_history").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function refresh_config() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "get_conf",
		url: "site_survey_conf.php",
		success: function(msg){
			$("#content_conf").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function set_config() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: $("#form_conf").serialize(),
		url: "site_survey_conf.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh(0);
			refresh_captures();
		}
	});
}

function execute_custom_script(cmd) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "execute&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val()+"&cmd="+cmd,
		url: "site_survey_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Custom script is running...');
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh(0);
			refresh_history();
		}
	});
}

function cancel_custom_script() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "cancel&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "site_survey_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Custom script has been stopped...');
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh(0);
			refresh_history();
		}
	});
}

function showTab()
{
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function load_file(what) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		url: "site_survey_actions.php",
		success: function(msg){
			$("#output").val(msg);
			showTab();		
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function delete_file(what, which) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "delete&file=" + which + "&" + what,
		url: "site_survey_actions.php",
		success: function(msg){
			$("#content_history").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh_history();
			refresh_captures();
		}
	});
}

function usb_toggle(action) {
	$('#output').load('site_survey_actions.php?usb&action='+action);
	if(action == 'enable'){
		$('#data_link').html('<strong>Uninstall from USB</strong>');
		$('#data_status').html('<font color="lime"><strong>on usb</strong></font>');
		$('#data_link').attr("href", "javascript:usb_toggle('disable');");
	}
	else{
		$('#data_link').html('<strong>Install on USB</strong>');
		$('#data_status').html('<font color="red"><strong>not on usb</strong></font>');
		$('#data_link').attr("href", "javascript:usb_toggle('enable');");
	}
}

function deauth(ap, client, time) {
	$("#refresh_text").html('<em>Running<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val()+"&deauthtarget="+ap+"&deauthtargetClient="+client+"&deauthtimes="+time,
		url: "site_survey_attacks.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function capture(ap, channel) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val()+"&ap="+ap+"&channel="+channel,
		url: "site_survey_attacks.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Capture is running...');
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh(0);
			refresh_captures();
		}
	});
}

function cancel_capture() {	
	$.ajax({
		type: "GET",
		data: "cancel",
		url: "site_survey_attacks.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Capture has been stopped...');
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh(0);
			refresh_captures();
		}
	});
}
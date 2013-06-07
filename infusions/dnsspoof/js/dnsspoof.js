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

	refresh_history();
	
	$("#tabs ul").idTabs();
				
    $("#auto_refresh").toggle(function() {
			$("#auto_refresh").html('<font color="lime">On</font>');
			$('#auto_time').attr('disabled', 'disabled');
			
			auto_refresh = setInterval(
			function ()
			{
				refresh();
			},
			$("#auto_time").val());
		}, function() {
			$("#auto_refresh").html('<font color="red">Off</font>');
			$('#auto_time').removeAttr('disabled');
				
            clearInterval(auto_refresh);
			auto_refresh = null;
		});	
}

function dnsspoof_toggle(action) {
	$('#output').load('dnsspoof_actions.php?dnsspoof&'+action);

	if(action == 'stop') {
		$("#dnsspoof_link").html('<strong>Start</strong>');
		$("#dnsspoof_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#dnsspoof_link").attr("href", "javascript:dnsspoof_toggle('start');");
		$('#output').val('dnsspoof has been stopped...');	
				
		refresh_history();
	}
	else {
		$("#dnsspoof_link").html('<strong>Stop</strong>');
		$("#dnsspoof_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#dnsspoof_link").attr("href", "javascript:dnsspoof_toggle('stop');");
		$('#output').val('dnsspoof is running...');
				
		refresh_history();
	}
}

function refresh() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "lastlog",
		url: "dnsspoof_data.php",
		success: function(msg){
			$("#output").val(msg);
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
		url: "dnsspoof_data.php",
		success: function(msg){
			$("#content_history").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
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
		url: "dnsspoof_actions.php",
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
		url: "dnsspoof_actions.php",
		success: function(msg){
			$("#content_history").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh_history();
		}
	});
}

function usb_toggle(action) {
	$('#output').load('dnsspoof_actions.php?usb&action='+action);
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

function fake_toggle(action) {
	$('#output').load('dnsspoof_actions.php?fake&action='+action);
	if(action == 'install'){
		$('#fake_link').html('<strong>Uninstall</strong>');
		$('#fake_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#fake_link').attr("href", "javascript:fake_toggle('uninstall');");
	}
	else{
		$('#fake_link').html('<strong>Install</strong>');
		$('#fake_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#fake_link').attr("href", "javascript:fake_toggle('install');");
	}
}

function boot_toggle(action) {
	$('#output').load('dnsspoof_actions.php?boot&action='+action);
	if(action == 'disable') {
		$("#boot_link").html('<strong>Enable</strong>');
		$("#boot_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#boot_link").attr("href", "javascript:boot_toggle('enable');");
	}
	else {
		$("#boot_link").html('<strong>Disable</strong>');
		$("#boot_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#boot_link").attr("href", "javascript:boot_toggle('disable');");
	}
}

function update_conf(data, what) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		url: "dnsspoof_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>updated</b></font>'); clearInterval(showDots);
		}
	});
}
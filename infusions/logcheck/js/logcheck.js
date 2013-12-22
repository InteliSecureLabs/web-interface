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
	
	refresh();
	
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

function refresh() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "POST",
		url: "logcheck_data.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function test_email() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "test_email",
		url: "logcheck_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function update_conf(data, what) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "POST",
		data: "set_conf="+what+"&newdata="+data,
		url: "logcheck_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>updated</b></font>'); clearInterval(showDots);
		}
	});
}

function update_settings(data) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: $("#"+data).serialize(),
		url: "logcheck_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>updated</b></font>'); clearInterval(showDots);
		}
	});
}

function logcheck_toggle(action) {
	$('#output').load('logcheck_actions.php?logcheck&'+action);
	if(action == 'stop') {
		$("#logcheck_link").html('<strong>Start</strong>');
		$("#logcheck_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#logcheck_link").attr("href", "javascript:logcheck_toggle('start');");
	}
	else {
		$("#logcheck_link").html('<strong>Stop</strong>');
		$("#logcheck_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#logcheck_link").attr("href", "javascript:logcheck_toggle('stop');");
	}
}

function boot_toggle(action) {
	$('#output').load('logcheck_actions.php?boot&action='+action);
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

function daemon_toggle(action) {
	$('#output').load('logcheck_actions.php?daemon&action='+action);
	if(action == 'enable'){
		$('#cron_link').html('<strong>Uninstall</strong>');
		$('#cron_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#cron_link').attr("href", "javascript:daemon_toggle('disable');");
	}
	else{
		$('#cron_link').html('<strong>Install</strong>');
		$('#cron_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#cron_link').attr("href", "javascript:daemon_toggle('enable');");
	}
}

function install(where) {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		url: "logcheck_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			location.reload(true);
		}
	});
}
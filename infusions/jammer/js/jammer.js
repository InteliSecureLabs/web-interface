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
	refresh_available_ap('whitelist');
	refresh_available_ap('blacklist');
	
	refresh_config();
	
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

function append(what, which) {
	if($('#'+which).val() != "")
		$('#'+which).val($('#'+which).val() + '\n' + what);
	else
		$('#'+which).val(what);
}

function refresh() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "log",
		url: "jammer_data.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function refresh_available_ap(which) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "available_ap&mon="+$("#monitorInterfaces").val()+"&int="+$("#interfaces").val(),
		url: "jammer_data.php",
		success: function(msg){
			$("#list_"+which).html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			$('#list_' + which + ' li').click(function() { 
				var append_value = '# ' + $(this).attr("name") + '\n' + $(this).attr("address");
				append(append_value,which);
				return false;
			});
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
		url: "jammer_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>updated</b></font>'); clearInterval(showDots);
		}
	});
}

function jammer_toggle(action) {
	$('#output').load('jammer_actions.php?jammer&'+action+'&int='+$("#interfaces").val()+'&mon='+$("#monitorInterfaces").val());
	if(action == 'stop') {
		$("#jammer_link").html('<strong>Start</strong>');
		$("#jammer_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#jammer_link").attr("href", "javascript:jammer_toggle('start');");
		$('#output').val("Starting WiFi Jammer...");
	}
	else {
		$("#jammer_link").html('<strong>Stop</strong>');
		$("#jammer_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#jammer_link").attr("href", "javascript:jammer_toggle('stop');");
		$('#output').val("Starting WiFi Jammer...");
	}
}

function boot_toggle(action) {
	$('#output').load('jammer_actions.php?boot&action='+action);
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

function monitor_toggle(action) {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "monitor&"+action+"&int="+$("#interfaces").val()+"&mon="+$("#monitorInterfaces").val(),
		url: "jammer_actions.php",
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
		url: "jammer_actions.php",
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
		url: "jammer_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			$('#output').val("toggle "+$("#interfaces").val()+"...");
		}
	});
}

function refresh_interfaces() {
	$('#interfaces_l').load('jammer_interfaces.php?interface');
}

function refresh_monitors() {
	$('#monitorInterface_l').load('jammer_interfaces.php?monitor');
}

function refresh_config() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "get_conf",
		url: "jammer_conf.php",
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
		url: "jammer_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			$('#output').val('Configuration has been saved.');
		}
	});
}
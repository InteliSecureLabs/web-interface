var auto_refresh;
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
		url: "monitor_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function force() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "force",
		url: "monitor_actions.php",
		success: function(msg){
			$("#output").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh();
		}
	});
}

function daemon_toggle(action) {
	$('#output').load('monitor_actions.php?daemon&action='+action);
	if(action == 'enable'){
		$('#vnstatdi_link').html('<strong>Uninstall</strong>');
		$('#vnstatdi_status').html('<font color="lime"><strong>installed</strong></font>');
		$('#vnstatdi_link').attr("href", "javascript:daemon_toggle('disable');");
	}
	else{
		$('#vnstatdi_link').html('<strong>Install</strong>');
		$('#vnstatdi_status').html('<font color="red"><strong>not installed</strong></font>');
		$('#vnstatdi_link').attr("href", "javascript:daemon_toggle('enable');");
	}
}

function usb_toggle(action) {
	$('#output').load('monitor_actions.php?usb&action='+action);
	if(action == 'enable'){
		$('#db_link').html('<strong>Uninstall from USB</strong>');
		$('#db_status').html('<font color="lime"><strong>persistent</strong></font>');
		$('#db_link').attr("href", "javascript:usb_toggle('disable');");
	}
	else{
		$('#db_link').html('<strong>Install on USB</strong>');
		$('#db_status').html('<font color="red"><strong>not persistent</strong></font>');
		$('#db_link').attr("href", "javascript:usb_toggle('enable');");
	}
}

function reset() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "reset",
		url: "monitor_actions.php",
		success: function(msg){
			$("#output").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh();
		}
	});
}
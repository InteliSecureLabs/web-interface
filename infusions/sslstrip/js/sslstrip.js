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
	refresh_custom();
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

function sslstrip_toggle(action) {
	
	if($('#verbose:checkbox:checked').val() == "verbose")
		$('#output').load('sslstrip_actions.php?sslstrip&verbose&'+action);
	else
		$('#output').load('sslstrip_actions.php?sslstrip&'+action);

	if(action == 'stop') {
		$("#sslstrip_link").html('<strong>Start</strong>');
		$("#sslstrip_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#sslstrip_link").attr("href", "javascript:sslstrip_toggle('start');");
		$('#output').val('sslstrip has been stopped...');	
		
		$('#verbose').removeAttr('disabled');
		
		refresh_history();
	}
	else {
		$("#sslstrip_link").html('<strong>Stop</strong>');
		$("#sslstrip_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#sslstrip_link").attr("href", "javascript:sslstrip_toggle('stop');");
		$('#output').val('sslstrip is running...');
		
		$('#verbose').attr('disabled', 'disabled');
		
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
		data: "lastlog&filter="+$("#filter").val(),
		url: "sslstrip_data.php",
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
		url: "sslstrip_data.php",
		success: function(msg){
			$("#content_history").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function refresh_custom() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "custom",
		url: "sslstrip_data.php",
		success: function(msg){
			$("#content_custom").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function showTab()
{
	$("#Output").show(); 
	$("#History").hide();
	$("#History_link").removeClass("selected"); 
	$("#Custom").hide();
	$("#Custom_link").removeClass("selected"); 
	$("#Output_link").addClass("selected");
}

function load_file(what, which) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "load&file=" + which + "&" + what,
		url: "sslstrip_actions.php",
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
		url: "sslstrip_actions.php",
		success: function(msg){
			$("#content_history").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh_history();
			refresh_custom();
		}
	});
}

function boot_toggle(action) {
	$('#output').load('sslstrip_actions.php?boot&action='+action);
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

function install(where) {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		url: "sslstrip_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			location.reload(true);
		}
	});
}

function execute_custom_script(cmd) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "execute&cmd="+cmd,
		url: "sslstrip_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Custom script is running...');
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh_history();
			refresh_custom();
		}
	});
}

function refresh_config() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "get_conf",
		url: "sslstrip_conf.php",
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
		data: "set_conf=1&commands="+$.base64.encode($("#command_File").val()),
		url: "sslstrip_conf.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			refresh();
			refresh_history();
		}
	});
}
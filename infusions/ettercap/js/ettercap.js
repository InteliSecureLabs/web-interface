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
	$("#tabs2 ul").idTabs();
	
	$('#interface').change(function() { update() });
	$('#visualization_format').change(function() { update() });
	$('#mitm_options').change(function() { update_mitm_param(); update() });
	$('#mitm_options_param').change(function() { update() });
	$('#proto_options').change(function() { update() });
	$(':checkbox').click(function() { update() });
	$('#filter').change(function() { update() });
	
	$('#filter_editor').change(function() { show_filter() });
	
	$('#target_1').keyup(function() { update() });
	$('#target_2').keyup(function() { update() });
	
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
		url: "ettercap_data.php",
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
		url: "ettercap_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function ettercap_toggle(action) {
	$('#output').load('ettercap_actions.php?sslstrip&'+action);
	
	if(action == 'start') {
		start();
		$("#launch").html('<font color="red"><strong>Stop</strong></font>');
		$("#launch").attr("href", "javascript:ettercap_toggle('stop');");
	}
	else {
		cancel();
		$("#launch").html('<font color="lime"><strong>Start</strong></font>');
		$("#launch").attr("href", "javascript:ettercap_toggle('capture');");
	}
}

function update() {
	$('#command').val("ettercap " + interface() + options() + proto() + visualization() + filter() + mitm() + target_1() + target_2());
}

function update_mitm_param() {
	
	if($("#mitm_options").val() != "--")
	{
		$('#mitm_options_param').find('option').remove();
		
		$('#mitm_options_param').append($("<option></option>").text("--"));
		
		if($("#mitm_options option:selected").text() == "arp")
		{
			$('#mitm_options_param').append($("<option></option>").attr("value","oneway").text("oneway"));
			$('#mitm_options_param').append($("<option></option>").attr("value","remote").text("remote"));
			$('#mitm_options_param').append($("<option></option>").attr("value","oneway,remote").text("oneway,remote"));
		}
		else if($("#mitm_options option:selected").text() == "port")
		{ 
			$('#mitm_options_param').append($("<option></option>").attr("value","remote").text("remote"));
			$('#mitm_options_param').append($("<option></option>").attr("value","tree").text("tree"));
			$('#mitm_options_param').append($("<option></option>").attr("value","remote,tree").text("remote,tree"));
		}
		else
		{
			$('#mitm_options_param').find('option').remove();
			$('#mitm_options_param').append($("<option></option>").text("--"));
		}
	}
	else
	{
		$('#mitm_options_param').find('option').remove();
		$('#mitm_options_param').append($("<option></option>").text("--"));
	}
}

function start() {
	$.ajax({
		type: "GET",
		data: "launch&cmd="+$('#command').val(),
		url: "ettercap_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Ettercap is running...');
			$("#refresh_text").html(''); clearInterval(showDots);
			
			refresh_history();
		}
	});
}

function cancel() {
	$.ajax({
		type: "GET",
		data: "cancel",
		url: "ettercap_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Ettercap has been stopped...');
			$("#refresh_text").html(''); clearInterval(showDots);
			
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

function delete_file(what) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "delete&file=" + what,
		url: "ettercap_actions.php",
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
		url: "ettercap_actions.php",
		success: function(msg){
			$("#output").val(msg);
			showTab();
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function usb_toggle(action) {
	$('#output').load('ettercap_actions.php?usb&action='+action);
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

function target_1() {
	var return_value = "";
		
	if($("#target_1").val() != "--")
		return_value = $("#target_1").val() + " ";
	
	return return_value;
}

function target_2() {
	var return_value = "";
		
	if($("#target_2").val() != "--")
		return_value = $("#target_2").val() + " ";
	
	return return_value;
}

function options(which) {
	var return_value = "";

    $('input:checked').each(function() {
      return_value += $(this).val() + " ";
    });
	
	return return_value;
}

function interface() {
    var return_value = "";
	
	if($("#interface").val() != "--")
		return_value = $("#interface").val() + " ";
	
	return return_value;
}

function visualization() {
    var return_value = "";
	
	if($("#visualization_format").val() != "--")
		return_value = $("#visualization_format").val() + " ";
	
	return return_value;
}

function proto() {
    var return_value = "";
	
	if($("#proto_options").val() != "--")
		return_value = $("#proto_options").val() + " ";
	
	return return_value;
}

function mitm() {
    var return_value = "";
	
	if($("#mitm_options").val() != "--")
		if($("#mitm_options_param").val() != "--")
			return_value = $("#mitm_options").val() + ":" +$("#mitm_options_param").val() + " ";
		else
			return_value = $("#mitm_options").val() + " ";
	
	return return_value;
}

function filter() {
    var return_value = "";
	
	if($("#filter").val() != "--")
		return_value = $("#filter").val() + " ";
	
	return return_value;
}

function show_filter() {
	
	if($("#filter_editor").val() != "--")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$('#filter_name').val($("#filter_editor").val());
		
		$.ajax({
			type: "GET",
			data: "show_filter&which=" + $("#filter_editor").val(),
			url: "ettercap_filters.php",
			success: function(msg){
				$("#filter_content").val(msg);
				$("#refresh_text").html(''); clearInterval(showDots);
			}
		});
	}
	else
	{
		$('#filter_name').val("");
		$('#filter_content').val("");
	}
}

function delete_filter() {	
	if($("#filter_editor").val() != "--")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "GET",
			data: "delete_filter&which=" + $("#filter_editor").val(),
			url: "ettercap_filters.php",
			success: function(msg){
				$("#filter_editor option:selected").remove();
				$('#filter_name').val("");
				$('#filter_content').val("");
				
				$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
				
				refresh_filter();
			}
		});
	}
}

function save_filter() {	
	if($("#filter_content").val() != "")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "POST",
			data: "save_filter=1&which="+$("#filter_editor").val()+"&newdata="+escape($("#filter_content").val()),
			url: "ettercap_filters.php",
			success: function(msg){
				$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
			}
		});
	}
}

function new_filter() {	
	if($("#filter_name").val() != "" && $("#filter_name").val() != $("#filter_editor").val())
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "POST",
			data: "new_filter=1&which="+$("#filter_name").val()+"&newdata="+escape($("#filter_content").val()),
			url: "ettercap_filters.php",
			success: function(msg){
				$('#filter_editor').append($("<option></option>").attr("value",$("#filter_name").val()).text($("#filter_name").val()+".filter"));
				$('#filter_editor').val($("#filter_name").val()+".filter");
				$("#refresh_text").html('<font color="lime"><b>done</b></font>'); clearInterval(showDots);
			}
		});
	}
}

function compile_filter() {	
	if($("#filter_editor").val() != "--")
	{
		if(auto_refresh == null) {
			$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
			showLoadingDots();
		}
		
		$.ajax({
			type: "GET",
			data: "compile_filter=1&which="+$("#filter_editor").val(),
			url: "ettercap_filters.php",
			success: function(msg){
				$("#output").val(msg);
				$("#refresh_text").html('<font color="lime"><b>saved</b></font>'); clearInterval(showDots);
				refresh_filter();
			}
		});
	}
}

function refresh_filter() {
	
	var previous_val = $('#filter option:selected').text();
	
	$.ajax({
		type: "GET",
		data: "filter_list",
		url: "ettercap_filters.php",
		success: function(msg){
			$('#filter').html(msg);
			$('#filter').val(previous_val);
			update();
		}
	});	
}

function install(where) {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		url: "ettercap_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			location.reload(true);
		}
	});
}
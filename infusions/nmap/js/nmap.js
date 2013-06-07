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

var showOutput = function() {
    clearInterval(auto_refresh);

    auto_refresh = setInterval(function(){            
		refresh_control();
    },10000);
}

function init() {
	
	refresh_history();
	//refresh_output();
	
	$("#tabs ul").idTabs();
	$("#tabs2 ul").idTabs();
	
	$('#profile').change(function() { update() });
	$('#timing').change(function() { update() });
	$('#tcp').change(function() { update() });
	$('#nontcp').change(function() { update() });
	
	$(':checkbox').click(function() { update() });
	
	$('#target').keyup(function() { update() });
}

function refresh_control() {
	$('#control').load('nmap_data.php?control'); 
}

function refresh_output() {
	$.ajax({
		type: "GET",
		data: "lastscan",
		url: "nmap_data.php",
		success: function(msg){
			$("#output").val(msg);
		}
	});
}

function refresh_history() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "history",
		url: "nmap_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function scan_toggle(action) {
	if(action == 'scan') {
		scan();
		$("#scan").html('<font color="red"><strong>Cancel</strong></font>');
		$("#scan").attr("href", "javascript:scan_toggle('cancel');");
	}
	else {
		cancel();
		$("#scan").html('<font color="lime"><strong>Scan</strong></font>');
		$("#scan").attr("href", "javascript:scan_toggle('scan');");
	}
}

function update() {
	if(profile() != "")
		$('#command').val("nmap " + profile() + target());
	else
		$('#command').val("nmap " + timing() + tcp() + nontcp() +options() + target());
}

function cancel() {
	$.ajax({
		type: "GET",
		data: "cancel",
		url: "nmap_actions.php",
		success: function(msg){
			refresh_history();
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			clearInterval(auto_refresh);
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
		url: "nmap_actions.php",
		success: function(msg){
			$("#output").val(msg);
			showTab();		
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function delete_file(what) {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "delete&file=" + what,
		url: "nmap_actions.php",
		success: function(msg){
			$("#content").html(msg);
			refresh_history();
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function scan() {
	showOutput();

	$.ajax({
		type: "GET",
		data: "scan&cmd="+$('#command').val(),
		url: "nmap_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Scan is running...');
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function usb_toggle(action) {
	$('#output').load('nmap_actions.php?usb&action='+action);
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

function options(which) {
	var return_value = "";

    $('input:checked').each(function() {
      return_value += $(this).val() + " ";
    });
	
	return return_value;
}

function target() {
	var return_value = "";
		
	if($("#target").val() != "--")
		return_value = $("#target").val() + " ";
	
	return return_value;
}

function profile() {
    var return_value = "";
	
	if($("#profile").val() != "--")
		return_value = $("#profile").val() + " ";
	
	return return_value;
}

function timing() {
    var return_value = "";
	
	if($("#timing").val() != "--")
		return_value = $("#timing").val() + " ";
	
	return return_value;
}

function tcp() {
    var return_value = "";
	
	if($("#tcp").val() != "--")
		return_value = $("#tcp").val() + " ";
	
	return return_value;
}

function nontcp() {
    var return_value = "";
	
	if($("#nontcp").val() != "--")
		return_value = $("#nontcp").val() + " ";
	
	return return_value;
}

function install(where) {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		url: "nmap_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			location.reload(true);
		}
	});
}
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
	$('#verbose').change(function() { update() });
	$('#timestamp').change(function() { update() });
	$('#resolve').change(function() { update() });
	
	$(':checkbox').click(function() { update() });
	
	$('#filter').keyup(function() { update() });
}

function refresh_history() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "history",
		url: "tcpdump_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function append_filter(what) {
	if($('#filter').val().substr($('#filter').val().length-1) != ' ' && $('#filter').val().length != 0)	
		$('#filter').val($('#filter').val() + ' ' + what);
	else
		$('#filter').val($('#filter').val() + what);
	
	update();
}

function dump_toggle(action) {
	if(action == 'capture') {
		dump();
		$("#scan").html('<font color="red"><strong>Stop</strong></font>');
		$("#scan").attr("href", "javascript:dump_toggle('stop');");
	}
	else {
		cancel();
		$("#scan").html('<font color="lime"><strong>Capture</strong></font>');
		$("#scan").attr("href", "javascript:dump_toggle('capture');");
	}
}

function update() {
	if(filter() != '')
		$('#command').val("tcpdump " + interface() + verbose() + resolve() + timestamp() + options() + '\'' + filter() + '\'');
	else
		$('#command').val("tcpdump " + interface() + verbose() + resolve() + timestamp() + options());
}

function cancel() {
	$.ajax({
		type: "GET",
		data: "cancel",
		url: "tcpdump_actions.php",
		success: function(msg){
			refresh_history();
			$("#output").val(msg);
			$('#output').val('Capture has been stopped...');
			load_file('capture.log');
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
		url: "tcpdump_actions.php",
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
		url: "tcpdump_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function dump() {

	$.ajax({
		type: "GET",
		data: "scan&cmd="+$('#command').val(),
		url: "tcpdump_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$('#output').val('Capture is running...');
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function usb_toggle(action) {
	$('#output').load('tcpdump_actions.php?usb&action='+action);
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

function verbose() {
	var return_value = "";
		
	if($("#verbose").val() != "--")
		return_value = $("#verbose").val() + " ";
	
	return return_value;
}

function filter() {
	var return_value = "";
		
	if($("#filter").val() != " ")
		return_value = $("#filter").val();
	
	return return_value;
}

function interface() {
    var return_value = "";
	
	if($("#interface").val() != "--")
		return_value = $("#interface").val() + " ";
	
	return return_value;
}

function timestamp() {
    var return_value = "";
	
	if($("#timestamp").val() != "--")
		return_value = $("#timestamp").val() + " ";
	
	return return_value;
}

function resolve() {
    var return_value = "";
	
	if($("#resolve").val() != "--")
		return_value = $("#resolve").val() + " ";
	
	return return_value;
}

function install(where) {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "GET",
		data: "install&where=" + where,
		url: "tcpdump_actions.php",
		success: function(msg){
			$("#output").val(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
			location.reload(true);
		}
	});
}
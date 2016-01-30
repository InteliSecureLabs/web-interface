var auto_refresh = null;
var auto_refresh2 = null;
var auto_refresh_t1 = null;
var auto_refresh_t2 = null;
var auto_refresh_t3 = null;
var auto_refresh_t4 = null;
var last_acction="";
var last_info="";
var terminal_window = null;

function open_terminal(){
	if (terminal_window == null || terminal_window.closed ) { 
		window.clearInterval(auto_refresh_t1); auto_refresh_t1 = null;
		window.clearInterval(auto_refresh_t2); auto_refresh_t2 = null;
		window.clearInterval(auto_refresh_t3); auto_refresh_t3 = null;
		window.clearInterval(auto_refresh_t4); auto_refresh_t4 = null;
		terminal_window=open('terminal.html','terminal');
	}
}

function acction(acction) {
	var run=true;
	hide_actions();
	hide_info();
	if (acction.search('##OPTION1##')>0){
		var option1 = prompt("VAR: ##OPTION1## \n"+acction);
		if (option1==null || option1=="") run=false;
		acction = acction.replace('##OPTION1##', option1);
	}
	if (acction.search('##OPTION2##')>0){
		var option2 = prompt("VAR: ##OPTION2## \n"+acction);
		if (option2==null || option2=="") run=false;
		acction = acction.replace('##OPTION2##', option2);
	}
	if (run){
		$.ajax({
			type: "GET",
			data: acction,
			url: "php/uwui_actions_exec.php",
			success: function(msg){
				if (msg.length < 200){
					alert(msg);
				} else {
					var windows_msg=open('','Windows Message','width=600,height=600,scrollbars=yes,location=no');
					windows_msg.document.write('<html><head><title>UWUI</title><link rel="stylesheet" type="text/css" href="css/uwui.css" /><link rel="icon" href="icons/uwui.jpg" type="image/x-icon"><link rel="shortcut icon" href="icons/uwui.jpg" type="image/x-icon"></head><body style="text-align:left;"><pre>'+msg+'<pre></body></html>');
				}
			}
		});
	}
}

function start(){
	$('#core').load('php/uwui_core.php');
	auto_refresh = setInterval(function (){$('#core').load('php/uwui_core.php');}, 2000);
}

function stop(){
	window.clearInterval(auto_refresh);
	auto_refresh = null;
}

function hide_actions(){
	var div = document.getElementById("acctions_window");
	if (div != null) {
		document.body.removeChild(div);
	}
	last_acction="";	
}

function actions(top,left,tipo,value,value2,value3){
	var div = document.getElementById("acctions_window");
	if (div != null) {
		document.body.removeChild(div);
	}
	if (last_acction==tipo+value) {
		last_acction="";
	} else {
		var div = document.getElementById("info_window");
		if (div != null) {
			document.body.removeChild(div);
			window.clearInterval(auto_refresh2);
			auto_refresh2 = null;
		}
		var div = document.createElement("div");
		div.id="acctions_window";
		div.className="window";
		div.style.top=top;
		div.style.left=left;
		document.body.appendChild(div);
		$('#acctions_window').load('php/uwui_actions.php?tipo='+tipo+"&valor="+value+"&valor2="+value2+"&valor3="+value3);
		last_acction=tipo+value;
	}
}

function show_in_terminal(url_info){
	if (terminal_window != null && ! terminal_window.closed ) { 
		     if ($('#terminal1',terminal_window.document).html() == "" ) auto_refresh_t1 = setInterval(function (){$('#terminal1',terminal_window.document).load(url_info);}, 1000);
		else if ($('#terminal2',terminal_window.document).html() == "" ) auto_refresh_t2 = setInterval(function (){$('#terminal2',terminal_window.document).load(url_info);}, 1000);
		else if ($('#terminal3',terminal_window.document).html() == "" ) auto_refresh_t3 = setInterval(function (){$('#terminal3',terminal_window.document).load(url_info);}, 1000);
		else if ($('#terminal4',terminal_window.document).html() == "" ) auto_refresh_t4 = setInterval(function (){$('#terminal4',terminal_window.document).load(url_info);}, 1000);
	}
	var div = document.getElementById("info_window");
	if (div != null) {
		document.body.removeChild(div);
		window.clearInterval(auto_refresh2);
		auto_refresh2 = null;
	}
}

function hide_info(){
	var div = document.getElementById("info_window");
	if (div != null) {
		document.body.removeChild(div);
		window.clearInterval(auto_refresh2);
		auto_refresh2 = null;
	}
	last_info="";
}

function info(top,left,tipo,value,value2,value3){
	var div = document.getElementById("info_window");
	if (div != null) {
		document.body.removeChild(div);
		window.clearInterval(auto_refresh2);
		auto_refresh2 = null;
	}
	if (last_info==tipo+value) {
		last_info="";
	} else {
		var div = document.getElementById("acctions_window");
		if (div != null) {
			document.body.removeChild(div);
		}
		var div = document.createElement("div");
		div.id="info_window";
		div.className="window";
		div.style.top=top;
		div.style.left=left;
		document.body.appendChild(div);
		if (terminal_window != null && ! terminal_window.closed ) { 
			url_info='php/uwui_info.php?tipo='+tipo+"&valor="+value+"&valor2="+value2+"&valor3="+value3;
			$('#info_window').html("<input type=button onclick=\"javascript:show_in_terminal('"+url_info+"');\" value='Open in Terminal'><br>");
		}
		var div2 = document.createElement("div");
		div2.id="content";
		document.getElementById('info_window').appendChild(div2);
		$('#content').load('php/uwui_info.php?tipo='+tipo+"&valor="+value+"&valor2="+value2+"&valor3="+value3);
		auto_refresh2 = setInterval(function (){$('#content').load('php/uwui_info.php?tipo='+tipo+"&valor="+value+"&valor2="+value2+"&valor3="+value3);}, 1000);
		last_info=tipo+value;
	}
}

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
}

function refresh() {
	$("#refresh_text").html('<em>Loading<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		url: "status_data.php",
		success: function(msg){
			$("#content").html(msg);
			$("#refresh_text").html(''); clearInterval(showDots);
		}
	});
}

function getOUIFromMAC(mac) {

	var top = 30;
	var left = Math.floor(screen.availWidth * .66) - 10;
	var width = 700
	var height = 400
	var tab = new Array();

	tab = mac.split(mac.substr(2,1));

	var win = window.open("http://standards.ieee.org/cgi-bin/ouisearch?" + tab[0] + '-' + tab[1] + '-' + tab[2], 'OUI From MAC', 'top=' + top + ',left=' + left + ',width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}

function graph(what) {

	var top = 30;
	var left = Math.floor(screen.availWidth * .66) - 10;
	var width = 700
	var height = 400

	var win = window.open("graph.php?"+what, 'Graph', 'top=' + top + ',left=' + left + ',width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}

function execute(what) {

	var top = 30;
	var left = Math.floor(screen.availWidth * .66) - 10;
	var width = 700
	var height = 400

	var win = window.open("execute.php?cmd="+what, 'Execute', 'top=' + top + ',left=' + left + ',width=' + width + ',height=' + height + ",resizable=yes,scrollbars=yes,statusbar=no");
	win.focus();
}
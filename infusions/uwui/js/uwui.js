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
}

function install(what, where) {
	$("#refresh_text").html('<em>Installing<span id="loadingDots"></span></em>'); 
	showLoadingDots();
	
	$.ajax({
		type: "POST",
		data: "install=1&where=" + where + "&what=" + what,
		url: "uwui_actions.php",
		success: function(msg){
			$("#refresh_text").html(''); clearInterval(showDots);
			location.reload(true);
		}
	});
}

function mytoggle(id,id_link,id_section) {
	id.toggle();
	
	if(id_link.text() == "[+]") 
	{
		id_link.text('[_]');
		id_section.css('border-bottom-left-radius','');
		id_section.css('border-bottom-right-radius','');
	}
	else 
	{ 
		id_link.text('[+]');
		id_section.css('border-bottom-left-radius','10px 10px');
		id_section.css('border-bottom-right-radius','10px 10px');
	}
}
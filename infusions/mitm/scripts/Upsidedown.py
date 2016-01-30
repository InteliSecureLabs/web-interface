import re
import subprocess
from BeautifulSoup import BeautifulSoup
import upsidedown

def response(ctx, flow):
 try:
  if re.match('image/',flow.response.headers["content-type"][0]):
   proc = subprocess.Popen('/usb/usr/bin/convert -flip - -', shell=True, stdin=subprocess.PIPE, stdout=subprocess.PIPE)
   flow.response.content=proc.communicate(flow.response.content)[0]
   proc.stdin.close()
  
  if re.match('text/html',flow.response.headers["content-type"][0]):
   flow.response.decode()
   soup = BeautifulSoup(flow.response.content)
   for text in soup.findAll(text=True):
    text.replaceWith(upsidedown.transform(text))
   
   flow.response.content=str(soup)
 
 except IndexError:
  ctx.log("no content-type[0]")
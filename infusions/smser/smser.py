#!/usb/usr/bin/python

import os
import sys
import random
import imaplib
import smtplib
import datetime
from email.parser import HeaderParser

greetings = ["Hey man ", "Yo. ", "Hello ", "Hola, ", "Sup dawg! "]
prePhrases = ["Fine!", "Ok, ", "Okay, ", "Alright, ", "Sure, "]
phrases = ["the front door is ", "the garage door is ", "the oven is ", "its in the oven now", "fine I'll take care of the "]
states = ["open ", "closed ", "preheating ", "off "]
objects = ["dog", "hamster", "car"]
postPhrases = [". Anything else?", ". Want me to do anything else for you?", ". Now stop bothering me!", ". What else can I do?"]
thanks = ["and no problem.", "any time", "dont't worry about it!", "you're welcome."]


def main(args):

	global email
	global password
	global number
	global inbox
	global verbose
	global sendReply
	global logs
	global time
	global cmd1
	global cmd2
	global cmd3

	inbox = "INBOX"
	verbose = False
	sendReply = True
	sendMsg = False
	loops = 1
	helpMode = False
	time = str(datetime.datetime.now())
	logs = True

	for loops in range(0, len(args), 1):	
		if str(args[loops]) == "--help" or str(args[loops]) == "-h":
			print "Use as: readSMS.py [OPTIONS]\n\tNOTE: YOU MUST SPECIFY AND EMAIL ADDRESS, EMAIL PASSWORD, AND PHONE_NUMBER@SMS_GATEWAY!\n"
			print "\tExample: smser.py -e myaddress@gmail.com -p lamePassword -n 1234567890@text.verison.com\n\n"
			print "[OPTIONS]\n"
			print "\t--verbose : Run program verbosely"
			print "\t--noreply : Generate reply but do NOT send it"
			print "\t--nologs : Do not keep logs"
			print "\t--cmd1 \"COMMAND\" : Custom command to run on keyphrase"
			print "\t--cmd2 \"COMMAND\" : Custom command to run on keyphrase"
			print "\t--cmd3 \"COMMAND\" : Custom command to run on keyphrase"
			print "\t-e EMAIL_ADDRESS : Specify email address"
			print "\t-p EMAIL_PASSWORD : Specify email password"
			print "\t-n NUMBER@GATEWAY : Specify number at gateway"
			print "\t-m \"message\" : Send a specific message"
			print "\t-i INBOX : Specify an inbox to check, defaut is \"INBOX\""
			print "\n[NOTES]\n"
			print "\t IF YOUR STRING HAS SPACES IN IT YOU MUST WRAP IT IN \"\" or \'\'!"
			print "\t[EXAMPLE]: smser.py -e myaddress@gmail.com -p \"This is my password\" -n 123456789@text.verison.com -m \"Hello World\""
			print "\t IF THE -m OPTION IS USED THE SCRIPT WILL NOT READ MESSAGES!"
			helpMode = True

		elif str(args[loops]) == "--verbose":
			verbose = True
			print "RUNNING IN VERBOSE MODE"

		elif str(args[loops]) == "--noreply":
			sendReply = False
			print "NO REPLY WILL BE SENT"

		elif str(args[loops]) == "--nologs":
			logs = False
			print "NO LOGS WILL BE KEPT"

		elif str(args[loops]) == "--cmd1":
			cmd1 = str(args[loops + 1])
			loops = loops + 1

		elif str(args[loops]) == "--cmd2":
			cmd2 = str(args[loops + 1])
			loops = loops + 1

		elif str(args[loops]) == "--cmd3":
			cmd3 = str(args[loops + 1])
			loops = loops + 1

		elif str(args[loops]) == "-e":
			email = str(args[loops + 1])
			loops = loops + 1

		elif str(args[loops]) == "-p":
			password = str(args[loops + 1])
			loops = loops + 1

		elif str(args[loops]) == "-n":
			number = str(args[loops + 1])
			loops = loops + 1
		
		elif str(args[loops]) == "-m":
			msg = str(args[loops + 1])
			sendMsg = True
			loops = loops + 1

		elif str(args[loops]) == "-i":
			inbox = str(args[loops + 1])
			loops = loops + 1

	if helpMode == False:
		readSMS()

	if sendMsg == True:
		sendMessage(msg)

def readSMS():
	print email
	print password
	print number
	server = imaplib.IMAP4_SSL("imap.gmail.com")
	server.login(email, password)

	status, messages = server.select(inbox)

	if status != "OK":
		print "YOUR SELECTED MAIL BOX DOES NOT EXIST!"
		exit()

	os.system("echo [" + (time) + "]: Checking for messages. >> /pineapple/logs/SMSer.log")

	if int(messages[0]) > 0:
		for message_number in range(1, int(messages[0]) + 1):
			data = server.fetch(message_number, "(BODY[HEADER])")
			parser = HeaderParser()
			msg = parser.parsestr(data[1][0][1])
			if verbose == True: 
				print "Sender: " + str(msg["from"])
		
			if number in msg["from"]:
				if verbose == True:
					print "You got mail"
				
				_, payload = server.fetch(message_number, "(UID BODY[TEXT])")
                                payload = payload[0][1]
				payload = str(payload).lower();
				
				if verbose == True:
					print "Message: " + payload 

				payload = payload.split(" ")
				randGreet = random.randint(0, len(greetings) - 1)
				randThank = random.randint(0, len(thanks) - 1)
				randPre = random.randint(0, len(prePhrases) - 1)
				randPost = random.randint(0, len(postPhrases) - 1)
				buildReply = []
				reply = ""

				if "hey" in payload or "hello" in payload or "hola" in payload or "yo" in payload:
					buildReply = reply, greetings[randGreet]
					reply = ''.join(buildReply)

				if "open" in payload and "front" in payload and "door" in payload:
					buildReply = reply, prePhrases[randPre], phrases[0], states[0]
					reply = ''.join(buildReply)
					os.system("hostapd_cli -p /var/run/hostapd-phy0 karma_enable")

				if "close" in payload and "front" in payload and "door" in payload:
					buildReply = reply, prePhrases[randPre], phrases[0], states[1]
					reply = ''.join(buildReply)	
					os.system("hostapd_cli -p /var/run/hostapd-phy0 karma_disable")

				if "preheat" in payload and "oven" in payload or "put" in payload and "oven" in payload:
					buildReply = reply, prePhrases[randPre], phrases[2], states[2]
					reply = ''.join(buildReply)
					os.system("echo /pineapple/dnsspoof/dnsspoof.sh | at now")

				if "off" in payload and "oven" in payload or "stop" in payload and "oven" in payload:
					buildReply = reply, prePhrases[randPre], phrases[2], states[3]
					reply = ''.join(buildReply)
					os.system("killall dnsspoof")

				if "dog" in payload or "cat" in payload:
					buildReply = reply, prePhrases[randPre], phrases[4], objects[0]
					reply = ''.join(buildReply)					
					os.system("echo " + cmd1 + " | at now")

				if "car" in payload or "van" in payload or "truck" in payload:
					buildReply = reply, prePhrases[randPre], phrases[4], objects[2]
					reply = ''.join(buildReply)
					os.system("echo " + cmd2 + " | at now")

				if "hamster" in payload or "mouse" in payload or "rat" in payload:
					buildReply = reply, prePhrases[randPre], phrases[4], objects[1]
					reply = ''.join(buildReply)
					os.system("echo " + cmd3 + " | at now")

				if "thank" in payload or "thanks" in payload or "gracias" in payload or "appreciate" in payload:
					buildReply = reply, thanks[randThank]
					reply = ''.join(buildReply)

				if random.randint(0, 5) == 3:
					buildReply = reply, postPhrases[randPost]
					reply = ''.join(buildReply)

				if logs == True:
					os.system("echo [" + (time) + "]: Message recieved. >> /pineapple/logs/SMSer.log") 

				if verbose == True:
					print "Message Reply: " + reply

				if sendReply == True:
					sendMessage(reply)
					if logs == True:
						os.system("echo [" + (time) + "]: Message sent. >> /pineapple/logs/SMSer.log")
				
				server.store(message_number, "+FLAGS", "\\Deleted")
	

	server.expunge()
	server.logout()

#def parseMsg(msg):
#
#	if verbose == True:
#		print "Raw Message: " + msg
#
#	message = msg.split("', ")
#
#	if verbose == True:
#		print "Partial Parsed: " + message[2]
#
#	message = message[2].split("\\")
#
#	if verbose == True:
#		print "Almost Parsed: " + message[0]
#
#        if message[0].startswith("'"):
#                message = message[0].split("'")
#        elif message[0].startswith('"'):
#                message = message[0].split('"')
#
#        gatheredMessage = []
#        for i in range(1, len(message), 1):
#                gatheredMessage.append(message[i])
#                                                  
#	return ' '.join(gatheredMessage)

def sendMessage(message):
	server = smtplib.SMTP("smtp.gmail.com", 587)
	server.ehlo()
	server.starttls()
	server.login(email, password)
	headers = "\r\n".join(["from: " + email,
                       "subject: " + message,
                       "to: " + number,
                       "mime-version: 1.0",
                       "content-type: text/html"])
        content = headers + "\r\n\r\n" + message
        server.sendmail(email, number, content)
	server.quit()

	if verbose == True:
		print "Sent message: " + message

main(sys.argv)

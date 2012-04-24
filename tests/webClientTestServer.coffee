#!/usr/bin/env coffee

http = require 'http'
util = require 'util'

doneStep1 = false

util.log 'Starting server...'
server = http.createServer (req, res) ->
	util.log "URL requested: #{req.url}"
	switch req.url
		when '/simpleget'
			res.end 'simpleget says hi!'
		when '/simplepost'
			body = ''
			req.on 'data', (data) ->
				body += data
			req.on 'end', ->
				foundImLooking = (body.indexOf('imLooking') != -1)
				foundForTheRightInfo = (body.indexOf('forTheRightInfo') != -1)
				if foundImLooking and foundForTheRightInfo
					res.end 'Yes'
				else
					res.end 'No'
		when '/step1'
			doneStep1 = true
			res.end 'done step1'
		when '/step2'
			if doneStep1
				res.end 'step1 => step2'
			else
				res.end 'you need to do step1 first'
		when '/reset'
			doneStep1 = false
			res.end 'reset'
		else
			res.end 'catchall'

server.listen 9000
util.log 'Listening...'
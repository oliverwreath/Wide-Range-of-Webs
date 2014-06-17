var express = require('express');
var app = express();

var http = require('http');

app.set('port', 3000);

app.get('/', function(req, res){
	res.send('Hi');
})

app.get('/home', function(req, res){
	res.send('Home');
})

app.get('/about', function(req, res){
	res.send('About');

})

app.get('/tutorial', function(req, res){
	res.send('Welcome to Tutorial!');
})

app.listen(app.get('port'));


var express = require('express');
var app = express();
app.use(express.static("public"));
var db = require('./db');

var UserController = require('./user/UserController');
app.use('/api', UserController);

module.exports = app;
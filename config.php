<?php
/*
 * Variabili del Token [Bot]Ti
 * Define this variable immediatly. Insert the token of bot. Readme: https://core.telegram.org/bots#botfather
 */ 
define('BOT_TOKEN', 'TOKEN TELEGRAM');    //Insert number/letter of id token   149655063:AAE4QGfuhxEXk2JNacYkkaqGQmQZYYQ9270
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

/*
 * Variabili del database Mysql
 * Define this variable immediatly. This is variable of Mysql Server
 */ 
$GLOBALS['mysql_host']='localhost'; //Ip server Mysql
$GLOBALS['mysql_port']='3306';      //Port server Mysql
$GLOBALS['mysql_user']='admin';     //User database
$GLOBALS['mysql_pass']='PASSWORD';//Password database
$GLOBALS['mysql_db']='telegram';    //Don't change

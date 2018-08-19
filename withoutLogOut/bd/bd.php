<?php 
require "libs/rb-mysql.php";
R::setup('mysql:host=localhost; dbname=try_bdd','root','');
session_start();
if(!R::testConnection())
	{
	exit('Нет подключения к бд');
}
?>
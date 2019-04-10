<?
$config['db_host']="localhost";
$config['db_name']="bookborrow_db";
$config['db_user']="bookborrow_user";
$config['db_pswd']="bookborrow_pass";

mysql_connect($config['db_host'],$config['db_user'],$config['db_pswd']);
mysql_select_db($config['db_name']);

mysql_query("SET CHARACTER SET utf8");
?>
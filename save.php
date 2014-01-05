<?
include "anti-injection.php";
include "config.php";

var $id = $_POST['id'];
var $user_id = $_POST['user_id'];
var $friend_id = $_POST['friend_id'];
var $friend_name = $_POST['friend_name'];
var $book_id = $_POST['book_id'];
var $book_name = $_POST['book_name'];
var $from = $_POST['from'];
var $to = $_POST['to'];

if($id > 0){
  //`user_id` = '$user_id',//makes no sense to update owner for existing borrow
  mysql_query("UPDATE `borrows`
SET

`friend_id` = '$friend_id',
`friend_name` = '$friend_name',
`book_id` = '$book_id',
`book_name` = '$book_name',
`from` = '$from',
`to` = '$to'
WHERE `id` = '$id';
");
} elseif($id == 0){
  mysql_query("INSERT INTO `borrows`
(`user_id`,`friend_id`,`friend_name`,`book_id`,`book_name`,`from`,`to`)
VALUES
('$user_id','$friend_id','$friend_name','$book_id','$book_name','$from','$to');");
}
?>
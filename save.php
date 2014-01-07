<?
include "anti-injection.php";
include "config.php";

$id = $_POST['id'];
$user_id = $_POST['user_id'];
$friend_id = $_POST['friend_id'];
$friend_name = $_POST['friend_name'];
$book_id = $_POST['book_id'];
$book_name = $_POST['book_name'];
$from = $_POST['from'];
$to = $_POST['to'];
if(is_numeric($from)){ $from = $from / 1000; }
if(is_numeric($to)){ $to = $to / 1000; }

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
echo json_encode(array( code => 0, message => "დამახსოვრებულია" ));
?>
<?php
session_start();
include_once "lib.php";
include_once "fb_config.php";

include_once("facebook-php-sdk/src/facebook.php");
$facebook = new Facebook(array(
		'appId'  => $config['app_id'],
		'secret' => $config['app_secret'],
		'cookie' => true,
));

$redir_location = "Location: https://www.facebook.com/pages/@/".$config['page_id']."?id=".$config['page_id']."&sk=app_156691764502878";

if(isset($_GET["code"]) && isset($_GET["state"])){
	$code = str_replace("#_=_", "", $_GET["code"]);
	$state = $_GET["state"];
	//
	try {
		//make curl request
		$url_for_token = "https://graph.facebook.com/oauth/access_token?"
	    ."client_id=".$config['app_id'].""
	    ."&redirect_uri=".$config['app_dir_https']."/auth.php"
	    ."&client_secret=".$config['app_secret'].""
	    ."&code=".$code;
		$token_container = get_data_curl($url_for_token);
		$token_parts = explode("&", $token_container);
		$token = str_replace("access_token=", "", $token_parts[0]);
		//echo $token;
		//////$me = get_data_curl("https://graph.facebook.com/me?access_token=$token");
		//////$my_info = json_decode($me);
		$facebook->setAccessToken($token);
		$me = $facebook->api('/me');
		$my_info = (object)$me;
		//$birth_date = date_parse($my_info->birthday);
		//note that "email" and "user_location" should be entered in User & Friend Permissions: field of application permissions management page
		//also they're mentioned in index.php OAuth redirect's URL as scope argument
		//here is the list of additional fields which you can request: https://developers.facebook.com/docs/reference/login/extended-profile-properties/
		$fb_id = $my_info->id;
		$full_name = $my_info->name;
		$first_name = $my_info->first_name;
		$last_name = $my_info->last_name;
		$profile_url = $my_info->link;
		$username = $my_info->username;
		//$birthday = $my_info->birthday;
		///////$since_born = date_diff(new DateTime(NULL), new DateTime($my_info->birthday), true);
		$age = 0;//$since_born->y;
		$gender = $my_info->gender;
		$email = $my_info->email;
		$location = (object)$my_info->location;
		if($location){ $hometown = $location->name;
		} else { $hometown = ""; }
		//get profile picture url
		/*$prof_pic_headers = get_headers("https://graph.facebook.com/$fb_id/picture",1);
		// just a precaution, check whether the header isset...
		if(isset($prof_pic_headers['Location'])) {
			$prof_pic_url = $prof_pic_headers['Location']; // string
		} else {
			$prof_pic_url = false; // nothing there? .. weird, but okay!
		}*/
		$prof_pic_url = "https://graph.facebook.com/$fb_id/picture";
		//end getting profile picture url
		header($redir_location);
	} catch(Exception $e){
		//die("Facebook has a problem. Please try again later.");
		//TODO: somehow curl doesnt work properly, use another method to authenticate
		header($redir_location);
	}
}
else if(isset($_GET["error"])){
	/*
	 YOUR_REDIRECT_URI?
    error_reason=user_denied
   &error=access_denied
   &error_description=The+user+denied+your+request.
   &state=YOUR_STATE_VALUE
   #_=_
	 */
	echo $_GET["error"];
} else {
	//non-fb initialized request
}
?>
<?
   include "fb_config.php";
   
   include_once("facebook-php-sdk/src/facebook.php");
   $facebook = new Facebook(array(
		'appId'  => $config['app_id'],
		'secret' => $config['app_secret'],
		'cookie' => true,
   ));

   $localhost = "localhost" == $_SERVER["HTTP_HOST"];
?>
<!Doctype html>
<html>
    <head>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="jquery.facebook.multifriend.select.js"></script>
        <link rel="stylesheet" href="jquery.facebook.multifriend.select-list.css" />
        <style>
	  /*html { overflow:hidden; }
	  body { overflow:hidden; }
	  */
          body {
                background: #fff;
                color: #333;
                font: 11px verdana, arial, helvetica, sans-serif;
            }
            a:link, a:visited, a:hover {
                color: #666;
                font-weight: bold;
                text-decoration: none;
            }
	    .only-authed { display:none; }
        </style>
    </head>
    <body>

        <div id="pageBody">
            <div id="fb-root"></div>
            <script src="https://connect.facebook.net/en_US/all.js"></script>
            <script>
                FB.init({appId: '<?=$config['app_id']?>',
		         status: true,
		         cookie: true});

		// Additional initialization code here 
		FB.Canvas.setAutoGrow();

                FB.getLoginStatus(function(response) {console.log(response);
                    if (response.status === 'connected') {
          // the user is logged in and has authenticated your 
          // app, and response.authResponse supplies 
          // the user's ID, a valid access token, a signed 
          // request, and the time the access token 
          // and signed request each expire 
		        var uid = response.authResponse.userID;
		        var accessToken = response.authResponse.accessToken;
                        init();
		} else if (response.status === 'not_authorized') {
          // the user is logged in to Facebook, 
          // but has not authenticated your app 
          top.window.location = "https://www.facebook.com/dialog/oauth/?client_id=<?=$config['app_id']?>"
			+ "&redirect_uri=<?=$config['app_dir_https']?>/auth.php"
			+ "&state=<?=uniqid()?>&scope=<?=$config['permissions']?>";
                    } else {
          // the user isn't logged in to Facebook.
		        
                    }
                });


                function login() {
                    FB.login(function(response) {
                        if (response.status === 'connected') {
                            init();
		        } else if (response.status === 'not_authorized') {
                        } else {
                            alert('Login Failed!');
                        }
                    });
                }

                function init() {
                  FB.api('/me', function(response) {
                      $("#username").html("<img src='https://graph.facebook.com/" + response.id + "/picture'/><div>" + response.name + "</div>");
                      $("#jfmfs-container").jfmfs({ max_selected: 1, max_selected_message: "{0} of {1} selected"});
                      $("#logged-out-status").hide();
                      $("#show-friends,.only-authed").show();
  
                  });
                }              


                $("#show-friends").live("click", function() {
                    var friendSelector = $("#jfmfs-container").data('jfmfs');             
                    $("#selected-friends").html(friendSelector.getSelectedIds().join(', ')); alert(4);console.log("dafuq");
                });
alert($("#btn-load-books").length);console.log("dafuq");
		$("#btn-load-books").live("click", function(){alert(5);
		    //you need usr_actions.books permission
		    FB.api('/me/book.reads', function(response){
		        alert(response.toString());
		    });
		});

              </script>
              
              <div id="logged-out-status" style="">
                  <a href="javascript:login()">Login</a>
              </div>

              <div>
                  <div id="username"></div>
                  <a href="#" id="show-friends" style="display:none;">Show Selected Friends</a>
                  <div id="selected-friends" style="height:30px"></div>
                  <div id="jfmfs-container"></div>
              </div>
	      <div id="books-div" class="only-authed">
		<button id="btn-load-books">Load books</button>
	      </div>
        </div>
    </body>
</html>


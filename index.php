<?
   include "lib.php";
   include "config.php";
   include "fb_config.php";
   
   include_once("facebook-php-sdk/src/facebook.php");
   $facebook = new Facebook(array(
		'appId'  => $config['app_id'],
		'secret' => $config['app_secret'],
		'cookie' => true,
   ));

   $localhost = "localhost" == $_SERVER["HTTP_HOST"];

   $me_res = $facebook->api('/me');
   $me = (object)$me_res;

?>
<!Doctype html>
<html>
    <head>
        <script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.22.custom.min.js"></script>
	<link rel="stylesheet" href="css/south-street/jquery-ui-1.8.22.custom.css" />
	
        <script type="text/javascript" src="jquery.facebook.multifriend.select.js"></script>
        <link rel="stylesheet" href="jquery.facebook.multifriend.select-list.css" />
        <style type="text/css">
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
            thead th { text-align:left; }
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
                    $("#selected-friends").html(friendSelector.getSelectedIds().join(', '));
                });

		/*$("#btn-load-books").live("click", function(){
		    //you need usr_actions.books permission
		    FB.api('/me/book.reads', function(response){
		        alert(response.toString());
		    });
		});*/

              </script>
	      
	      <div id="app-description" style="margin-bottom:20px;">
		  წიგნის გასათხოვებლად წიგნი ან ფეისბუქის პროფილში უნდა გქონდეთ Read-ში მონიშნული, ან ხელით შეიყვანოთ წიგნის სახელი.
		  <br>
		  აირჩიეთ წიგნი, აირჩიეთ მეგობარი, რომელსაც ათხოვეთ წიგნი, და დააჭირეთ დამახსოვრების ღილაკს.
		  <br>
	      </div>
              
              <div id="logged-out-status" style="">
                  <a href="javascript:login()">Login</a>
              </div>

	      <div id="books-div" class="only-authed" style="width:250px; float:left; margin-right:16px;">
		<!--<button id="btn-load-books">Load books</button>-->
                  <select id='book' name='book' size="22">
		  <!--style='height:400px;'-->
		  <option value='manual'>წიგნის სახელის ხელით აკრეფა</option>
		  <?
		  $my_books_res = $facebook->api('/me/book.reads');
                  $my_books = (object)$my_books_res;
                  foreach($my_books->data as $b){
		    $book = get_book($b['data']['book']['id']);
		    //$book['description'], ['name'], ['link'], ['likes']
		    echo "<option value='".$book['id']."'>".$book['name']."</option>";
		  }
		  ?>
		  </select>
		  <br>
		  <input type='text' id='book-manual' name='book-manual' style='display:none;' />
	      </div>
              <div id="friends-div" style="width:250px; float:left; padding-bottom:100px;">
                  <div id="username"></div>
                  <a href="#" id="show-friends" style="display:none;">Show Selected Friends</a>
                  <div id="selected-friends" style="height:30px"></div>
                  <div id="jfmfs-container"></div>
              </div>
	      <div id='toolbox-div'>
		    <label for="from">თხოვების თარიღი</label>
		    <br>
		    <input type="text" id="from" name="from" />
		    <br>
		    <label for="to">გამორთმევის თარიღი</label>
		    <br>
		    <input type="text" id="to" name="to" />
		    <br>
		    <button type='button' id='btn-save'>დამახსოვრება</button>
	      </div>
	      
	      <div class="text" id="bottom-description" style="clear:both; margin:20px 0px;">
		  ქვემოთ მოცემულია თქვენს მიერ უკვე გათხოვებული წიგნების სია.
		  <br>
		  <br>
		  <table border='0' width='100%'>
		    <thead>
		      <tr>
		      <th>მეგობარი</th>
		      <th>წიგნი</th>
		      <th>თხოვების თარიღი</th>
		      <th>გამორთმევის თარიღი</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?
		          $q_borrows = mysql_query("select * from borrows where user_id = '".$me->id."';");
                          while($r_borrow = mysql_fetch_assoc($q_borrows)){
			    $rfriend_id = $r_borrow["friend_id"];
			    $rfriend_name = $r_borrow["friend_name"];
			    if($rfriend_name == ""){
			      $api_fr = $facebook->api("/".$rfriend_id);
			      $rfriend_name = $api_fr["name"];
			    }
			    $rbook_name = $r_borrow["book_name"];
			    $rfrom = $r_borrow["from"];
			    $rto = $r_borrow["to"];
			    ?>
			    <tr>
			       <td><?=$rfriend_name?></td>
			       <td><?=$rbook_name?></td>
			       <? //the time is given in UTC so let javascript convert it based on browser timezone ?>
			       <td><span class='convert-time'><?=$rfrom*1000?></span><?//=date("j/m/Y", $rfrom)?></td>
			       <td><span class='convert-time'><?=$rto*1000?></span><?//=date("j/m/Y", $rto)?></td>
			    </tr>
			    <?
                          }
		      ?>
		    </tbody>
		  </table>
	      </div>
		    
	      <script type='text/javascript'>
		    $("#from,#to").datepicker({ dateFormat : "@" });
		    $('#book').change(function(){
			$(this).val() == "manual" ? $("#book-manual").show() : $("#book-manual").hide();
		    }).change();

                    function save_book(args){
		      var ret = { code : 501, message : "nothing happened" };
		      var id = args.id;
		      var friend_id = args.friend_id;
		      var book_id = args.book_id;
		      var book_name = args.book_name;
		      var from = args.from || 0;//Date.now();
		      var to = args.to || 0;//Date.now();
		      if(id != undefined && friend_id && book_id && book_name){
			  $.ajax({
			    url: "save.php",
			    type: "POST",
			    cache: false,
			    data: { id : id, user_id : '<?=$me->id?>', friend_id : friend_id,
				  book_id : book_id, book_name : book_name, from : from, to : to },
			    dataType: "json",
			    success: function(data){
				ret = { code : 0, message : data.message };
			    },
			    error: function(data){
				ret = { code : 2, message : data.statusText };
			      }
			    });
		      } else {
			ret = { code : 1, message : "invalid parameters" };
		      }
		      return ret;
                    }
                    
		    $("#btn-save").click(function(){
			var friendSelector = $("#jfmfs-container").data('jfmfs');
			var friend_ids = friendSelector.getSelectedIds();
			var book_id = $("#book").val();
			var book_manual = $("#book-manual").val();
			var book_name = "";
			var from = $("#from").val();
			var to = $("#to").val();
			if(/^\d+$/gi.test(book_id)){
			  book_name = $("#book option[value='"+book_id+"']").text();
			}
			else if(book_id == 'manual' && !/^\s*$/gi.test(book_manual)){
			  book_name = book_manual;
			}
			if(friend_ids.length > 0 && !/^\s*$/gi.test(book_name)){
			  var friend_id = friend_ids[0];
			  var sav = save_book({ id : 0, friend_id : friend_id, book_id : book_id,
				book_name : book_name, from : from, to : to });
			  alert(sav.message);
			  
			} else {
			  alert("წიგნიც, მეგობარიც და თარიღიც აუცილებლად უნდა შეავსოთ :)");
			}
                    });

                    //the time is given in UTC so let javascript convert it based on browser timezone
                    $(".convert-time").each(function(i, el){
			var d = new Date(parseInt($(this).text()));
			$(this).html(d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear());
                    });
	      </script>
        </div>
    </body>
</html>


<?php

// session_start creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
session_start();

// isset determine if a variable is declared and is different than null.
if(!isset($_SESSION['user_data']))
{
	header('location:index.php');
}

require('database/ChatUser.php'); // Accessing the database with table ChatUser.

require('database/ChatRooms.php'); // Accessing the database with table ChatRooms.

$chat_object = new ChatRooms; // Object for ChatRooms.

// Accessing variables from ChatRooms.
$chat_data = $chat_object->get_all_chat_data();


$user_object = new ChatUser; // Object for ChatUser.

// Accessing variables from ChatUser.
$user_data = $user_object->get_user_all_data();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat Application | Chatroom</title>
	<!-- Bootstrap core CSS -->
    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>


		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css'><link rel="stylesheet" href="./chatroomstyle.css">
		<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor-front/jquery/jquery.min.js"></script>
    <script src="vendor-front/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor-front/jquery-easing/jquery.easing.min.js"></script>

    <script type="text/javascript" src="vendor-front/parsley/dist/parsley.min.js"></script>
	<style type="text/css">
		html,
		body {
		  height: 100%;
		  width: 100%;
		  margin: 0;
		}
		#wrapper
		{
			display: flex;
		  	flex-flow: column;
		  	height: 100%;
		}
		#remaining
		{
			flex-grow : 1;
		}
		#messages {
			background-color: #446985;
			width:400px;
			height: 150px;
			font-color:white;
			overflow: auto;
		}
		#chat-room-frm {
			margin-top: 10px;
		}
		#user_list
		{
			background-color: #446985;
			font-color:white;
			height:450px;
			overflow-y: auto;
		}

		#messages_area
		{
			height: 650px;
			overflow-y: auto;
			background-color:#FDFBDB;
		}

	</style>
</head>
<body>
	<div class="navbar">
	  <table style="width:100%">
	  <tr>
	    <td>
	      <h6 class="navtitle">Web Chat Application | General Room</h6>
	    </td>
			<td>
				<a href="profile.php" class="editbtn" type="button" >Edit</a>
				<input type="button" class="logoutbtn" name="logout" id="logout" value="Logout" />
			</td>
	  </tr>
	</table>
	</div>

	<?php

	$login_user_id = '';

	foreach($_SESSION['user_data'] as $key => $value)
	{
		$login_user_id = $value['id'];
	?>
	<input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $login_user_id; ?>" />
				<div class="generalroom">
					<table class="chatarea">
				    <tr class="chatroom">
				      <td>
				        <h6 class="chattitle">Chat Room</h6>
				      </td>
				      <td colspan="2" align="right">
				        <a href="privatechat.php" class="supprivbtn">Private Chat</a>
				        <a href="https://file.projecmpe492.click/" class="supprivbtn" target="_blank" >Upload File</a>
				      </td>
				    </tr>
						<tr>
				      <td class="mcha" colspan="3">

				  <div class="mainchatarea card-body" id="messages_area">
					<?php
					foreach($chat_data as $chat)
					{
						if(isset($_SESSION['user_data'][$chat['userid']]))
						{
							$from = 'Me';
							$row_class = 'row justify-content-end';
							$background_class = 'alert-success';
						}
						else
						{
							$from = $chat['user_name'];
							$row_class = 'row justify-content-start';
							$background_class = 'text-dark alert-light';
						}

						echo '
						<div class="'.$row_class.'">
							<div class="col-sm-10">
								<div class="shadow-sm alert '.$background_class.'">
									<b>'.$from.' - </b>'.$chat["msg"].'
									<br />
									<div class="text-right">
										<small><i>'.$chat["created_on"].'</i></small>
									</div>
								</div>
							</div>
						</div>
						';
					}
					?>
					</div>
				</td>
	    </tr>
	    <tr>
	      <form method="post" id="chat_form" data-parsley-errors-container="#validation_error">
	      <td colspan="2" class="textarea">
	        <textarea class="form-control" id="chat_message" name="chat_message" placeholder="Don't Be Afraid To Talk!" data-parsley-maxlength="1000"  required></textarea>
	      </td>

	      <td class="messagebtnarea">
	        <button type="submit" name="send" id="send" class="sendbtn">
	          <i class="material-icons">send</i>
	        </button>
	      </td>
	      <div id="validation_error"></div>
	    </form>
	    </tr>
	  </table>

				<?php
				}
				?>

				<table class="userlist">
			    <tr class="ULtitle">
			      <td>User List</td>
			    </tr>
			    <tr>
			      <td>
					<div class="card-body" id="user_list">
						<div class="list-group list-group-flush userlistarea">
						<?php
						if(count($user_data) > 0)
						{
							foreach($user_data as $key => $user)
							{
								$icon = '<i class="fa fa-circle text-danger"></i>';

								if($user['user_login_status'] == 'Login')
								{
									$icon = '<i class="fa fa-circle text-success"></i>';
								}

								if($user['user_id'] != $login_user_id)
								{
									echo '
									<a class="list-group-item list-group-item-action">
										<img src="'.$user["user_profile"].'" class="img-fluid rounded-circle img-thumbnail" width="50" />
										<span class="ml-1"><strong>'.$user["user_name"].'</strong></span>
										<span class="mt-2 float-right">'.$icon.'</span>
									</a>
									';
								}

							}
						}
						?>
						</div>
					</div>
				</td>
			</tr>
		</table>
				</div>

		</div>
	</div>
</body>
<script type="text/javascript">

	$(document).ready(function(){

		var conn = new WebSocket('wss://projecmpe492.click/wss2/');
		conn.onopen = function(e) {
		    console.log("Connection established!");
		};

		conn.onmessage = function(e) {
		    console.log(e.data);

		    var data = JSON.parse(e.data);

		    var row_class = '';

		    var background_class = '';

		    if(data.from == 'Me')
		    {
		    	row_class = 'row justify-content-end';
		    	background_class = 'alert-success';
		    }
		    else
		    {
		    	row_class = 'row justify-content-start';
		    	background_class = 'text-dark alert-light';
		    }

		    var html_data = "<div class='"+row_class+"'><div class='col-sm-10'><div class='shadow-sm alert "+background_class+"'><b>"+data.from+" - </b>"+data.msg+"<br /><div class='text-right'><small><i>"+data.dt+"</i></small></div></div></div></div>";

		    $('#messages_area').append(html_data);

		    $("#chat_message").val("");
		};

		$('#chat_form').parsley();

		$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);

		$('#chat_form').on('submit', function(event){

			event.preventDefault();

			if($('#chat_form').parsley().isValid())
			{

				var user_id = $('#login_user_id').val();

				var message = $('#chat_message').val();

				var data = {
					userId : user_id,
					msg : message
				};

				conn.send(JSON.stringify(data));

				$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);

			}

		});

		$('#logout').click(function(){

			user_id = $('#login_user_id').val();

			$.ajax({
				url:"action.php",
				method:"POST",
				data:{user_id:user_id, action:'leave'},
				success:function(data)
				{
					var response = JSON.parse(data);

					if(response.status == 1)
					{
						conn.close();
						location = 'index.php';
					}
				}
			})

		});

	});

</script>
</html>

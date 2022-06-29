<?php

// session_start creates a session or resumes the current one based on a session identifier passed via a GET or POST request, or passed via a cookie.
session_start();

// isset determine if a variable is declared and is different than null.
if(!isset($_SESSION['user_data']))
{
	header('location:index.php'); // Accessing the index.php
}

require('database/ChatUser.php'); // Accessing the database with table ChatUser.

require('database/ChatRooms.php'); // Accessing the database with table ChatRooms.

?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat Application | Chatroom </title>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="style.css"/>
    <link href="vendor-front/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="vendor-front/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="vendor-front/parsley/parsley.css"/>

		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.1/iconfont/material-icons.min.css'><link rel="stylesheet" href="./privatechatstyle.css">
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
			height: 150px;
			background-color: #446985;
			overflow: auto;
		}
		#chat-room-frm {
			margin-top: 10px;
		}
		#user_list
		{
			background-color: #446985;
			height:450px;
			overflow-y: auto;
		}

		#messages_area
		{
			height: 485px;
			overflow-y: auto;
			background-color:#FDFBDB
			/*background-color:#e6e6e6;*/
			/*background-color: #EDE6DE;*/
		}

	</style>
</head>
<body>
	<div class="navbar">
	  <table style="width:100%">
	  <tr>
	    <td>
	      <h6 class="navtitle">Web Chat Application | Private Chat</h6>
	    </td>
			<td>
    			<a href="profile.php" class="editbtn" type="button" >Edit</a>
			<input type="button" class="logoutbtn" name="logout" id="logout" value="Logout"/>
    </td>
	  </tr>
	</table>
	</div>
	<div class="privchatarea">
	  <table class="userlist">
			<tr>
	      <td>

	        <div class="privuserlist">
                               <?php

				$login_user_id = '';

				$token = '';

				foreach($_SESSION['user_data'] as $key => $value)
				{
					$login_user_id = $value['id'];

					$token = $value['token'];

				?>
				<input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $login_user_id; ?>" />

				<input type="hidden" name="is_active_chat" id="is_active_chat" value="No" />

			</div>
		</td>
	</tr>
			<tr>
       <td>

        <h6 class="privtitle">User List</h6>

       </td>
     </tr>

	<tr>
		<td>

				<?php
				}

				$user_object = new ChatUser;

				$user_object->setUserId($login_user_id);

				$user_data = $user_object->get_user_all_data_with_status_count();

				?>
				<div class="listofusers" overflow-y:scroll;>
					<?php

					foreach($user_data as $key => $user)
					{
						$icon = '<i class="fa fa-circle text-danger"></i>';

						if($user['user_login_status'] == 'Login')
						{
							$icon = '<i class="fa fa-circle text-success"></i>';
						}

						if($user['user_id'] != $login_user_id)
						{
							if($user['count_status'] > 0)
							{
								$total_unread_message = '<span class="badge badge-danger badge-pill">' . $user['count_status'] . '</span>';
							}
							else
							{
								$total_unread_message = '';
							}

							echo "
							<a class='list-group-item list-group-item-action select_user' style='cursor:pointer' data-userid = '".$user['user_id']."'>
								<img src='".$user["user_profile"]."' class='img-fluid rounded-circle img-thumbnail' width='50' />
								<span class='ml-1'>
									<strong>
										<span id='list_user_name_".$user["user_id"]."'>".$user['user_name']."</span>
										<span id='userid_".$user['user_id']."'>".$total_unread_message."</span>
									</strong>
								</span>
								<span class='mt-2 float-right' id='userstatus_".$user['user_id']."'>".$icon."</span>
							</a>
							";
						}
					}


					?>
				</div>
      </td>
    </tr>

  </table>
	<table class="mainprivchat">
		<tr>
			<td>
				<h6 class="privtitle">Chat with your friends</h6>
			</td>
			<td align="right">

        <a href="chatroom.php" class="gnrlroom">General Room</a>
      </td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="chat_area" class="chatarea">

				</div>
			</td>
		</tr>
	</table>
</div>
</body>

<script type="text/javascript">
	$(document).ready(function(){

		var receiver_userid = '';

		var conn = new WebSocket('wss://projecmpe492.click/wss2/'); #Change socket to your socket

		conn.onopen = function(event)
		{
			console.log('Connection Established');
		};

		conn.onmessage = function(event)
		{
			var data = JSON.parse(event.data);

			if(data.status_type == 'Online')
			{
				$('#userstatus_'+data.user_id_status).html('<i class="fa fa-circle text-success"></i>');
			}
			else if(data.status_type == 'Offline')
			{
				$('#userstatus_'+data.user_id_status).html('<i class="fa fa-circle text-danger"></i>');
			}
			else
			{

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
					background_class = 'alert-primary';
				}

				if(receiver_userid == data.userId || data.from == 'Me')
				{
					if($('#is_active_chat').val() == 'Yes')
					{
						var html_data = `
						<div class="`+row_class+`">
							<div class="col-sm-10">
								<div class="shadow-sm alert `+background_class+`">
									<b>`+data.from+` - </b>`+data.msg+`<br />
									<div class="text-right">
										<small><i>`+data.datetime+`</i></small>
									</div>
								</div>
							</div>
						</div>
						`;

						$('#messages_area').append(html_data);

						$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);

						$('#chat_message').val("");
					}
				}
				else
				{
					var count_chat = $('#userid'+data.userId).text();

					if(count_chat == '')
					{
						count_chat = 0;
					}

					count_chat++;

					$('#userid_'+data.userId).html('<span class="badge badge-danger badge-pill">'+count_chat+'</span>');
				}
			}
		};

		conn.onclose = function(event)
		{
			console.log('connection close');
		};

		function make_chat_area(user_name)
		{
			var html = `
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col col-sm-6">
							<b>Chat with <span class="text-danger" id="chat_user_name">`+user_name+`</span></b>
						</div>
						<div class="col col-sm-6 text-right">
							<a href="https://file.projecmpe492.click/" class="btn btn-success btn-sm"  target= _blank  >Upload File</a>&nbsp;&nbsp;&nbsp;
							<button type="button" class="close" id="close_chat_area" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
				<div class="card-body" id="messages_area">

				</div>
			</div>

			<form id="chat_form" method="POST" data-parsley-errors-container="#validation_error">
				<div class="input-group mb-3" style="height:7vh">
					<textarea class="form-control" id="chat_message" name="chat_message" placeholder="Type Message Here" data-parsley-maxlength="1000" required></textarea>
					<div class="input-group-append">
						<button type="submit" name="send" id="send" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
					</div>
				</div>
				<div id="validation_error"></div>
				<br />
			</form>
			`;

			$('#chat_area').html(html);

			$('#chat_form').parsley();
		}

		$(document).on('click', '.select_user', function(){

			receiver_userid = $(this).data('userid');

			var from_user_id = $('#login_user_id').val();

			var receiver_user_name = $('#list_user_name_'+receiver_userid).text();

			$('.select_user.active').removeClass('active');

			$(this).addClass('active');

			make_chat_area(receiver_user_name);

			$('#is_active_chat').val('Yes');

			$.ajax({
				url:"action.php",
				method:"POST",
				data:{action:'fetch_chat', to_user_id:receiver_userid, from_user_id:from_user_id},
				dataType:"JSON",
				success:function(data)
				{
					if(data.length > 0)
					{
						var html_data = '';

						for(var count = 0; count < data.length; count++)
						{
							var row_class= '';
							var background_class = '';
							var user_name = '';

							if(data[count].from_user_id == from_user_id)
							{
								row_class = 'row justify-content-end';

								background_class = 'alert-success';

								user_name = 'Me';
							}
							else
							{
								row_class = 'row justify-content-start';

								background_class = 'alert-primary';

								user_name = data[count].from_user_name;
							}

							html_data += `
							<div class="`+row_class+`">
								<div class="col-sm-10">
									<div class="shadow alert `+background_class+`">
										<b>`+user_name+` - </b>
										`+data[count].chat_message+`<br />
										<div class="text-right">
											<small><i>`+data[count].timestamp+`</i></small>
										</div>
									</div>
								</div>
							</div>
							`;
						}

						$('#userid_'+receiver_userid).html('');

						$('#messages_area').html(html_data);

						$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);
					}
				}
			})

		});

		$(document).on('click', '#close_chat_area', function(){

			$('#chat_area').html('');

			$('.select_user.active').removeClass('active');

			$('#is_active_chat').val('No');

			receiver_userid = '';

		});

		$(document).on('submit', '#chat_form', function(event){

			event.preventDefault();

			if($('#chat_form').parsley().isValid())
			{
				var user_id = parseInt($('#login_user_id').val());

				var message = $('#chat_message').val();

				var data = {
					userId: user_id,
					msg: message,
					receiver_userid:receiver_userid,
					command:'private'
				};

				conn.send(JSON.stringify(data));
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

	})
</script>
</html>

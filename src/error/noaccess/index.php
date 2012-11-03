<?php
    /*
	  Copyright 2012 KRISHNA MURTI

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
   */   
	$con=mysql_connect("127.0.0.1", "<db-username>", "<db-password>");
 if(!$con)
  {
    header("location:http://kf.ahens.com/error/?conerror=1&db=cu");
	exit;
  }	
 
 
   mysql_select_db("<db-name>", $con);
   session_start();	
   $indiantime = time()+45000;
   $dat =date("F d, Y | h:i a",$indiantime);
	
	if(($_SESSION['user']=='')&&($_COOKIE['user']==''))	
		{  $_SESSION['url_temp']=$_SERVER['REQUEST_URI'];
		header("location:http://kf.ahens.com/login/?loginrequired=1"); exit;} 
	$_SESSION['url_temp']='';	 
	
   if(isset($_POST['apptease_out']))
	{
			mysql_query("UPDATE corn_users SET lastIP = '$_SERVER[REMOTE_ADDR]', lastUserInfo = '$_SERVER[HTTP_USER_AGENT]' WHERE Email ='$_SESSION[user]'",$con);
			$current = time();
			$spent = $current - $_SESSION['time'];
			mysql_query("UPDATE corn_users SET timeSpent = timeSpent + '$spent'  WHERE Email ='$_SESSION[user]'",$con);
			mysql_query("UPDATE corn_users SET live = 0 WHERE Email = '$_SESSION[user]'", $con);
			 setcookie("user","",time()-3600,'/');
			session_destroy();
			header("location:http://kf.ahens.com/?logout=success");
			exit;
	}
	
	
  $getUserDetail = mysql_fetch_array(mysql_query("SELECT * FROM corn_users WHERE userID='$_SESSION[uidx]'",$con));
  $uid=$getUserDetail['userID'];
  mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);
?>

<!doctype HTML> 
<html class="access_denied">
	<head>
		<title>Kornflex | Access Denied</title>
		<meta charset="utf-8" />
		<meta type="description" content="Kornflex is a service by AHENS, which helps you connect with your campus very e
					asily and efficiently" />
		<link type="shortcut icon" href="favicon.ico" />
		<link type="text/css" rel="stylesheet" href="http://kf.ahens.com/kornflex_design_all/kf_home_1366.css" />
		<link type="text/css" rel="stylesheet" href="http://kf.ahens.com/home/apps/apptease/ver10/apptease_design_1366.css" />
	    <script type="text/javascript" src="http://kf.ahens.com/korn_script.js"></script>
     	<script type="text/javascript" src="http://kf.ahens.com/script/kf_home.js"></script>
	 	<script type="text/javascript" src="http://kf.ahens.com/home/apps/apptease/ver10/apptease_script.js"></script>	
	</head>
	<body class="apptease" onload="loginstatus(), startNotification(), updatenotif_new(), message()">
		<body class="apptease" onload="loginstatus(), startNotification(), updatenotif_new(), message()">
	
	   	<div class="canvas" id="apptease_canvas">
	   		
	   	<?php if($_GET['usr_unavail']>53)
	  	{ ?>
	  
	   		
	   		<div class="error_message" id="no_subscription">
	   			<div class="smiley" id="depression_symbol">:(</div>
	   			<br /> 
	   			<div class="error_message_apptease" id="message1_error">
	   				Poor you!
	   			</div>
	   			<br /> <br /> 
	   			<div class="error_description" id="description1">
	   				There is no way you can see this page.
	   				<br />
	   				<span style='font-size:14px;'>
	   				Its seems user is on a moon mission or of some similar kind! <br />Hold on till he/she returns.
	   			    </span>
	   			</div>
	   		</div>
	   <?php } 
			else if($_GET['access']=="denied")
				{
					?>
					
					<div class="error_message" id="no_subscription">
						<div class="smiley" id="depression_symbol">:(</div>
	   					<br /> 
	   					<div class="error_message_apptease" id="message1_error">
	   						Stay Back!
	   					</div>
	   					<br /> <br /> 
	   					<div class="error_description" id="description1">
	   						You are not allowed to enter this area.
	   					<br />
	   					<span style='font-size:14px;'>
	   						Please go <a href="http://kf.ahens.com/home/home.php">back to your home</a>. Your mommy must be worrying.
						</span>
						</div>
					</div>	
			<?php }
				else
					{ ?>
						<div class="error_message" id="no_subscription">
						<div class="smiley" id="depression_symbol">:(</div>
	   					<br /> 
	   					<div class="error_message_apptease" id="message1_error">
	   						Stop playing with URL.
	   					</div>
	   					<br /> <br /> 
	   					<div class="error_description" id="description1">
	   						It will do you no good.
	   					<br />
	   					<span style='font-size:14px;'>
	   						Please go <a href="http://kf.ahens.com/home/home.php">back to your home</a>, if you think you are here by mistake.
						</span>
						</div>
					</div>	
					<?php	
						
					}
					?>
					
					
						<div class="remedy_error" style="position:absolute;font-size:12px;color:#222;top:620px;left:30px;">
	   		If you think that this is an error, rest assured. <br />We have been already notified by our
	   		automated reporting system.
	   		
	   					</div>
	   	</div>
	   
	  
	   
	   	<div class="bg" id="glassy_bg">
	   		
	   		
	   	</div>
   	   	<div class="bg" id="lower_bar">
   	   		<div class="ahens_foot"><br /> &nbsp;&nbsp;&nbsp;AHENS &copy; 2012</div>
   	   		
   	   	</div>
       	<div class="search_bar" id="finder">
            <input type="text" class="home_search" id="search_bar" value="Not for searching cattles and pets!" onfocus="removeVal()" onblur="bringVal()"/>
   	   	</div>
       	<div class="search_button" id="find_him" onmouseover="buttonBEffect()" onmouseout="buttonDEffect()" onclick="perform_search()"> find him/her
   	   	</div>
   		<div class="newmessage" id="newmessage"></div>
		<div class="newmessage" id="newchat"></div>
   
   		<div class="panel" id="upper_action_panel">
   			<a href="http://kf.ahens.com/home/mymirror.php"><img id="mail_icon" src="http://kf.ahens.com/kf_images_all/mail.png" border=0 /></a>  
      		<img id="chat_icon" src="http://kf.ahens.com/kf_images_all/kopetestatusmessage.png" border=0 />
    		<a href="http://kf.ahens.com/home/ahens/settings.php">  
      			<img id="settings_icon" src="http://kf.ahens.com/kf_images_all/application_x_desktop.png" border=0 title="Change your settings" />
    		</a>
   	  	</div>
  		<div class="username" title="Have a tour of your upcoming Profile!">
  	   			<a id="userLink" href="http://kf.ahens.com/home/newkf/index.php"><?php echo $getUserDetail['userName']; ?></a>
  		</div>
   		<div class="hidden_box" id="search_box">
       			<div class="close" id="exitt" onclick="hideRes()"> X </div>
	   			<div id="search_result"></div>
   		</div>
   
      	 <a href="http://kf.ahens.com/home/home.php">
			<div title="Go to your home" class="back_button" id="escape" onmouseover="this.childNodes[1].style.borderRight='solid 15px #80BABE'"; 
						onmouseout ="this.childNodes[1].style.borderRight='solid 15px #18BABE';">
						esc
			<div id="esc_arrow"></div>
		 </div>
		</a>
		<form id="logout" action="" method="post">
	  		<button  name="apptease_out" type="submit" class="app_logout" id="apptease_logout"
	   			onmouseover="logout_app_effect()"
	   			onmouseout="logout_app_effect_out()"> 
	   			:) 
			<div class="logout_arrow"></div>
	  		</button>
	   </form>
	
          	<!-- code #4 notifications live -->
   		<div id="notiFrame2"> </div>
	 	<div id="notif"> </div>
	 	<div id="notiFrame"> <div id="content"></div> <div id="more" style="text-align:center;"></div></div>
   			<!-- code #4 notifications end -->
	</body>
</html>

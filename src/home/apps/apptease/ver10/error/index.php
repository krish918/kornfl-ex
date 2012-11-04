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
 $conApp=mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>"); if(!$con)
  {
    header("location:http://kf.ahens.com/error/?conerror=1&db=cu");
	exit;
  }	
   if(!$conApp)
  {
    header("location:http://kf.ahens.com/error/?conerror=1&db=at");
	exit;
  }
   mysql_select_db("apptease", $conApp);
   mysql_select_db("<db-name>", $con);
   session_start();	
   $indiantime = time()+45000;
   $dat =date("F d, Y | h:i a",$indiantime);
	
	
		
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
	
	if(($_SESSION['user']=='')&&($_COOKIE['user']==''))	
		{  header("location:http://kf.ahens.com/login/?loginrequired=1"); exit;} 
	
  $getUserDetail = mysql_fetch_array(mysql_query("SELECT * FROM corn_users WHERE userID='$_SESSION[uidx]'",$con));
  $uid = $getUserDetail['userID'];
  if($uid!=56)	
  mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);
?>	

<!doctype HTML>
<html class="kornflex_apps">
	<head>
   		<title> Apptease Error </title>
   		<meta type="description" content="Apptease is an app on Kornflex , which lets you prepare for standard tests like SAT, GRE, GMAT, GATE" />
   		<meta charset="utf-8" />
   		<link type="shortcut/icon" href="favicon.ico" />
   		<link type="text/css" rel="stylesheet" href="http://kf.ahens.com/home/apps/apptease/ver10/apptease_design_1366.css" />
   		<link type="text/css" rel="stylesheet" href="http://kf.ahens.com/kornflex_design_all/kf_home_1366.css" />
     	
     	<script type="text/javascript" src="http://kf.ahens.com/korn_script.js"></script>
     	<script type="text/javascript" src="http://kf.ahens.com/script/kf_home.js"></script>
	 	<script type="text/javascript" src="http://kf.ahens.com/home/apps/apptease/ver10/apptease_script.js"></script>
	 
	</head>
	<body class="apptease" onload="message(),loginstatus(),updatenotif_new(),startNotification()">
	
	   	<div class="canvas" id="apptease_canvas">
	   		
	   	<?php if($_GET['nosubscription']==1)
	  	{ ?>
	  
	   		
	   		<div class="error_message" id="no_subscription">
	   			<div class="smiley" id="depression_symbol">:(</div>
	   			<br /> 
	   			<div class="error_message_apptease" id="message1_error">
	   				You seem in hurry!
	   			</div>
	   			<br /> <br /> 
	   			<div class="error_description" id="description1">
	   				Sorry! You are not subscribed for this app.
	   				<br />
	   				<span style='font-size:14px;'>
	   				Please goto <a href="http://kf.ahens.com/home/home.php">Appcetra</a>, and subscribe for this app by clicking on its icon.
	   			    </span>
	   			</div>
	   		</div>
	   <?php } 
			else
				{
					?>
					
					<div class="error_message" id="no_subscription">
						<div class="smiley" id="depression_symbol">:(</div>
	   					<br /> 
	   					<div class="error_message_apptease" id="message1_error">
	   						You seem lost!
	   					</div>
	   					<br /> <br /> 
	   					<div class="error_description" id="description1">
	   						People come here, when they do domething wrong.
	   					<br />
	   					<span style='font-size:14px;'>
	   						Please go <a href="http://kf.ahens.com/home/home.php">back to your home</a>. Your mommy must be worrying.
						</span>
						</div>
					</div>	
			<?php }
					?>
	   	</div>
	  
	   
	   	<div class="bg" id="glassy_bg"></div>
   	   	<div class="bg" id="lower_bar"></div>
       	<div class="textContainer" id="header_container"> A P P T E A S E</div>
       	<div id="kornflex_canvas_text"> Kornflex Canvas Ver 1.0 <br /> AHENS &copy; 2012 </div>
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
    		<a href="http://kf.ahens.com/home/ahens/settings.php"><img id="settings_icon" src="http://kf.ahens.com/kf_images_all/application_x_desktop.png" border=0 title="Change your settings" /></a>
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
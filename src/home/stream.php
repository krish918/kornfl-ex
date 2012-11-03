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
    header("location:http://kf.ahens.com/error/?conerror=1");
	exit;
  }	
	mysql_select_db("<db-name>", $con); 
	session_start();
	$indiantime = time()+45000;
	$dat =date("F:d, Y | h:i a",$indiantime);
	if($_COOKIE['user']!='' && $_SESSION['flag']!=1) 
	  {
	  	$_SESSION['flag']=1;
		$_SESSION['time']=time();
	  }	
	
	
    if(isset($_POST['logout']))
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
		{  $_SESSION['url_temp']=$_SERVER['REQUEST_URI'];
		header("location:http://kf.ahens.com/login/?loginrequired=1"); exit;} 
	$_SESSION['url_temp']='';	 

	
	$sql_query1 = "SELECT * FROM corn_users WHERE Email = '$_SESSION[user]' OR Email='$_COOKIE[user]'";
	$getUserDetail = mysql_fetch_array(mysql_query($sql_query1,$con));
      $userName = $getUserDetail['userName'];   
	  $campus = $getUserDetail['campusJoined'];
	  $uid = $getUserDetail['userID'];
	$_SESSION['uidx'] =$uid;
	$_SESSION['campus'] = $campus;
	if($uid!=56)	
	mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);
	
?>
<!doctype HTML>
<html class="kornflexStream">
<head>
  <title> Stream : <?php echo $userName; ?> </title>
  <meta type="description" content="" />
  <meta charset="utf-8" />
  <link type="shortcut/icon" href="favicon.ico" />
 
  <link type="text/css" rel="stylesheet" href="http://kf.ahens.com/kornflex_design_all/kf_home_1366.css" />
  <script type="text/javascript" src="http://kf.ahens.com/korn_script.js"></script>
  <script type="text/javascript" src="http://kf.ahens.com/script/kf_home.js"> </script>
  
</head>
<body class="kornflex_stream" id="home" onload="bringfeed(0,60),loginstatus(), startNotification(), updatenotif(),message()">
   <div class="back_design2" id="color_blue"></div>
   <div class="back_design2" id="color_green"></div>
   <div class="back_design2" id="color_yellow"></div>
   <div class="back_design2" id="color_red"></div>
   
   <div class="search_bar" id="finder">
     <input type="text" class="home_search" id="search_bar" value="Not for searching cattles and pets!" onfocus="removeVal()" onblur="bringVal()"/>
   </div>
   <div class="search_button" id="find_him" onmouseover="buttonBEffect()" onmouseout="buttonDEffect()" onclick="perform_search()"> find people
   </div>
       <div class="newmessage" id="newmessage"></div>
		<div class="newmessage" id="newchat"></div>
   <div class="panel" id="upper_action_panel">
     <a href="http://kf.ahens.com/home/mymirror.php"> <img id="mail_icon" src="http://kf.ahens.com/kf_images_all/mail.png" border=0 /></a>
      <img id="chat_icon" src="http://kf.ahens.com/kf_images_all/kopetestatusmessage.png" border=0 />
      <a href="http://kf.ahens.com/home/ahens/settings.php"><img id="settings_icon" src="http://kf.ahens.com/kf_images_all/application_x_desktop.png" border=0 title="Change your settings" /></a>
   </div>
   <div class="username" title="Have a tour of your upcoming Profile!"> <a id="userLink" href="http://kf.ahens.com/home/newkf/index.php"><?php echo $userName; ?></a></div>
     
	 <a href="http://kf.ahens.com/home/home.php" style="color:black;"> <div class="kf_inactive_tab" id="appcetra2" onmouseover="this.style.backgroundColor='#ededed';" onmouseout="this.style.backgroundColor='#ffffff';"> Appcetra </div></a>
   <div class="home_title_body_stream" id="middle">
   
      <div class="kf_active_tab" id="orangestream2"> OrangeStream </div>
	  <form action="" method="post">
      <button type="submit" name="logout" class="logout" id="right_circle"> Log Out </button>
	  </form>
	  <div id="title_line2"> Watch the trends of<br /> your campus </div>
	  <div id="subtitle_line2"> Its not exactly orange, but its exactly an stream. Enjoy your campus feed.
							   
	</div>	

    <div class="feedContainer" id="feedBox">
	   
	</div>
	<div id="containerDummy"></div>
    <div class="button_list" id="showmore" onmouseover="showmoreOver()" onmouseout="showmoreOut()">
	  Show More
	  <div class="arrow" id="smalldown"></div>
	 </div> 



	
	   <div class="image_container" id="logo">
  
    <a href="http://kf.ahens.com"><img src="http://kf.ahens.com/kf_images_all/kornflex_new_smal.gif" title="Reload this page" border=0 /></a>
  
  </div> 
  <div id="footer"> All rights reserved. <br /><span style="font-size:12px;">AHENS &copy; 2012</span>
   </div> 
   <div id="footerlink">
     <a class="stdlinkHome" href="http://blog.ahens.com"> Blog &raquo; </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <a class="stdlinkHome" href="http://www.ahens.com"> AHENS &raquo; </a> 
	 
   </div>  
  </div>
     <div class="hidden_box" id="search_box">
       <div class="close" id="exitt" onclick="hideRes()"> X </div>
	   <div id="search_result"></div>
	    
   
   </div>
   
   
     <div id="notiFrame2"> </div>
	 <div id="notif"> </div>
	 <div id="notiFrame"> <div id="content"></div> <div id="more" style="text-align:center;"></div></div>
      
   
</body>
</html>


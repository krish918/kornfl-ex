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
$conStone = mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>");
 if(!$con)
  {
    header("location:http://kf.ahens.com/error/?conerror=1");
	exit;
  }	
   if(!$conStone)
  {
    header("location:http://kf.ahens.com/error/?conerror=1");
	exit;
  }	
	mysql_select_db("<db-name>", $con); 
	mysql_select_db("<db-name>", $conStone); 
	session_start();
	$indiantime = time()+45000;
	$dat =date("F d, Y | h:i a",$indiantime);
	
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
		{  header("location:http://kf.ahens.com/login/?loginrequired=1"); exit;}
    
	$sql_query1 = "SELECT * FROM corn_users WHERE Email = '$_SESSION[user]' OR Email = '$_COOKIE[user]'";
	$getUserDetail = mysql_fetch_array(mysql_query($sql_query1,$con));
      $userName = $getUserDetail['userName'];   
	  $campus = $getUserDetail['campusJoined'];
	  $uid = $getUserDetail['userID'];
    $_SESSION['uidx'] =$uid;
	$_SESSION['campus'] = $campus;
	if($uid!=56)	
	mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);
	$currentTimeStamp = time();
	$getActStat = mysql_fetch_row(mysql_query("SELECT activation FROM corn_users WHERE userID ='$_SESSION[uidx]'",$con));
	if($getActStat[0]>0)
	{
		if(($currentTimeStamp-$getActStat[0])>259200){
			   mysql_query("UPDATE corn_users SET activation=0 WHERE userID='$_SESSION[uidx]'",$con);
			}
	}
	
//setting parameters for live notification
	$not=mysql_query("SELECT flakesID FROM flakes ORDER BY flakesID DESC", $con);
	$notify = mysql_fetch_array($not);
	$_SESSION['lastFlakes'] = $notify[0];	
	$not2=mysql_query("SELECT userID FROM corn_users WHERE campusJoined = '$campus' ORDER BY userID DESC", $con);
	$notify2 = mysql_fetch_array($not2);
	$_SESSION['lastUser'] = $notify2[0];
	$_SESSION['camp'] = $campus;
	$not3=mysql_query("SELECT rateID FROM ratinglist WHERE target = '$uid' ORDER BY rateID DESC", $con);
	$notify3 = mysql_fetch_array($not3);
	$_SESSION['newRater'] = $notify3[0];
    $_SESSION['id'] = $uid;	
	$stone = mysql_fetch_row(mysql_query("SELECT stoneID FROM stone ORDER BY stoneID DESC", $conStone));
	$_SESSION['stoneNotif'] = $stone[0];
	$read = mysql_fetch_row(mysql_query("SELECT dataID FROM readerdata ORDER BY dataID DESC", $conStone));
	$_SESSION['readNotif'] = $read[0];
	$comment = mysql_fetch_row(mysql_query("SELECT commentID FROM comments ORDER BY commentID DESC", $conStone));
	$_SESSION['comment'] = $comment[0];
	
	//app subscription
	
	/*$sql2 = mysql_query("SELECT * FROM user_sessions WHERE session = 0",$conApp);
	$subscriber_num = mysql_num_rows($sql2);*/
	if(isset($_POST['appid']))
	 {
	 if($_POST['appid']!="")
	 {
	  if(!mysql_query("INSERT INTO appsubscription (userID,appID,timestamp) VALUES('$uid','$_POST[appid]','$dat')",$con))
	      { header("location:/kornflex/error/?error=100&uid=$uid&appid=$_POST[appid]"); exit; }
		if(!mysql_query("UPDATE appcetra SET totalSubscriptions = totalSubscriptions+1 WHERE appID='$_POST[appid]'",$con))
				{ header("location:/kornflex/error/?error=200&uid=$uid&appid=$_POST[appid]"); exit; }	
		$link = mysql_fetch_row(mysql_query("SELECT appLink FROM appcetra WHERE appID = '$_POST[appid]'",$con));
        header("location:$link[0]");		
	  }
	}			
			   
		$getSubsQuery = mysql_query("SELECT * FROM appsubscription, appcetra WHERE appsubscription.userID = '$_SESSION[uidx]' AND appcetra.appID = appsubscription.appID",$con);
				$num_apps =mysql_num_rows($getSubsQuery);  
	 if($_SESSION['url_temp']!='')
		  {  header("location:http://kf.ahens.com$_SESSION[url_temp]");}

?>
<!doctype HTML>
<html class="kornflexHome">
<head>
  <title> Home : <?php echo $userName; ?> </title>
  <meta type="description" content="Presenting Appcetra�, an all new kornflex home app. Kornflex is now simpler & smarter than ever.
With Appcetra, you can choose/order the apps of your needs and allow them 
to appear in your streams in real time." />
  <meta charset="utf-8" />
  <link type="shortcut/icon" href="favicon.ico" />
  <link type="text/css" rel="stylesheet" href="http://kf.ahens.com/kornflex_design_all/kf_home_1366.css" />
 <script type="text/javascript" src="http://kf.ahens.com/korn_script.js"></script>
  <script type="text/javascript" src="http://kf.ahens.com/script/kf_home.js"></script>  
  

  
</head>
<body class="kornflex_hm" id="home" onload="loginstatus(), startNotification(), updatenotif_new(), message()">
   <div class="back_design" id="color_blue"></div>
   <div class="back_design" id="color_green"></div>
   <div class="back_design" id="color_yellow"></div>
   <div class="back_design" id="color_red"></div>
   
   <div class="search_bar" id="finder">
     <input type="text" class="home_search" id="search_bar" value="Not for searching cattles and pets!" onfocus="removeVal()" onblur="bringVal()"/>
   </div>
   <div class="search_button" id="find_him" onmouseover="buttonBEffect()" onmouseout="buttonDEffect()" onclick="perform_search()"> find people
   </div>
		<div class="newmessage" id="newmessage"></div>
		<div class="newmessage" id="newchat"></div>
   <div class="panel" id="upper_action_panel">
    
      <a href="http://kf.ahens.com/home/mymirror.php"><img id="mail_icon" src="http://kf.ahens.com/kf_images_all/mail.png" border=0 /></a>
      <img id="chat_icon" src="http://kf.ahens.com/kf_images_all/kopetestatusmessage.png" border=0 />
      <a href="http://kf.ahens.com/home/ahens/settings.php"><img id="settings_icon" src="http://kf.ahens.com/kf_images_all/application_x_desktop.png" border=0 title="Change your settings" /></a>
   
   </div>
   <div class="username" title="Have a tour of your upcoming Profile!"> <a id="userLink" href="http://kf.ahens.com/home/newkf/index.php"><?php echo $userName; ?></a></div>
     <a href="http://kf.ahens.com/home/stream.php" style="color:black;"><div class="kf_inactive_tab" id="orangestream" onmouseover="this.style.backgroundColor='#ededed';" onmouseout="this.style.backgroundColor='#ffffff';"> OrangeStream </div></a>
   <div class="home_title_body" id="middle">
   
      <div class="kf_active_tab" id="appcetra"> Appcetra </div>
       <form action="" method="post">
      <button type="submit" name="logout" class="logout" id="right_circle"> Log Out </button>
	  </form>
	  <div id="title_line"> Welcome to your new Home! </div>
	  <div id="subtitle_line"> Presenting Appcetra&trade;, an all new kornflex home app. Kornflex is now simpler & smarter than ever.<br />With Appcetra, you can choose/order the apps of your needs
	                           and allow them <br />to appear in your streams in real time. 
							   
	</div>						   
	  <div id="sec_2_line"> Now that you are here, what are you planning to do? <br />Confused! Start with our simplest apps &raquo;&raquo;&raquo;</div>
    <div class="innerbox_core" id="core">
	   <div class="appbox" id="search" onmouseover="showdetail('search','Search all the networks at KF for people you may be interested in.')" onmouseout="hidedetail('search', '<br />Network Search')" onclick="location.href='http://kf.ahens.com/home/search/';"> <br />Network Search </div>
	   <div class="appbox" id="mirror" onmouseover="showdetail('mirror','<br />Exchange messages with people in network.')" onmouseout="hidedetail('mirror', '<br />Mirror')" onclick="location.href='http://kf.ahens.com/home/mymirror.php';"> <br />Mirror </div>
	   <div class="appbox" id="profile" onmouseover="showdetail('profile','<br />Reflects your academic and professional skills.')" onmouseout="hidedetail('profile', '<br />Profile')" onclick="location.href='http://kf.ahens.com/home/profile.php';"> <br />Profile </div>
	   <div class="appbox" id="flakes" onmouseover="showdetail('flakes','<br />This app lets you share your feelings.')" onmouseout="hidedetail('flakes', '<br />Flex')" onclick="location.href='http://kf.ahens.com/home/flakes.php';"> <br />Flex </div>
	   <div class="appbox" id="stone" onmouseover="showdetail('stone','<br />Lets you store your knowledge and learnings.')" onmouseout="hidedetail('stone', '<br />Stone')" onclick="location.href='http://kf.ahens.com/home/stone/';"> <br />Stone </div>
	   <div class="appbox" id="commonroom" onmouseover="showdetail('commonroom','This app displays all flex running through the network.')" onmouseout="hidedetail('commonroom', '<br />Common Room')" onclick="location.href='http://kf.ahens.com/home/users/commonroom.php';"> <br />Common Room </div>
	
	</div>  
	<div class="innerbox_ondemand" id="ondemand">
	       <div class="appbox" id="appdem" onmouseover="showdetail('appdem','Sharpen your aptitude for various standard tests across world like SAT, GRE, GMAT,GATE etc.')" onmouseout="hidedetail('appdem', '<br />Apptease')" onclick="subscribeApp(0,0),callAppSubsContent(1)"> <br /> Apptease </div>
	</div>
	<div class="innerbox_upcoming" id="upcoming">
	       <div class="appbox" id="microstone" onmouseover="showdetail('microstone','<br />Mini, live and a fast version of stone.')" onmouseout="hidedetail('microstone', '<br />Micro Stone')"> <br /> Micro Stone </div>
	       <div class="appbox" id="addix" onmouseover="showdetail('addix','<br />So, you think you can do the sums. Try this!')" onmouseout="hidedetail('addix', '<br />Addix')"> <br /> Addix </div>
	</div>
    <div id="innerTitle"> Core Apps (8) </div>
    <div id="innerTitle2"> On Demand Apps (1) </div>
    <div id="innerTitle3"> Up coming Apps (2) </div>
	<div id="divider"></div>
	
	 <div class="image_container" id="logo">
  
    <a href=""><img src="http://kf.ahens.com/kf_images_all/kornflex_new_smal.gif" title="Reload this page" border=0 /></>
  
  </div> 
  <div id="footer"> All rights reserved. <br /><span style="font-size:12px;">AHENS &copy; 2012</span>
   </div> 
   <div id="footerlink">
     <a class="stdlinkHome" href="http://blog.ahens.com"> Blog &raquo; </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <a class="stdlinkHome" href="http://www.ahens.com"> AHENS &raquo; </a> 
	 
   </div>  
	<?php if($num_apps!=0)
	{ 
	    ?>
	  <div class="innerbox_subscribed" id="subscribed">
<div id="drop_down">
				
				</div>	      
		  <div id="menu_head_container" onclick="load_subs_apps()">
	            <div id="menu_head_myapp">My subscribed apps <span style="font-weight:normal;margin-left:14px;"><?php echo "&laquo;".$num_apps."&raquo;"; ?></span></div>
				<div id="arrow_head_myapp"></div>
			</div>	
				
		</div>
		<?php } ?>
	
  </div>
  <div id="newstream"> </div>
   <div class="hidden_box" id="search_box">
       <div class="close" id="exitt" onclick="hideRes()"> X </div>
	   <div id="search_result"></div>
	    
   
   </div>
 
       <div id="notiFrame2"> </div>
	 <div id="notif"> </div>
	 <div id="notiFrame"> <div id="content"></div> <div id="more" style="text-align:center;"></div></div>
	 
	 <?php
	 if($_SESSION['checkpoint']==1)
	 {
	 	$_SESSION['checkpoint']=0;
	 	?><div class="activation_notification"> Wohooo! You have successfully activated your account. Now, dive in without any worries.</div>
	 	<?php
	 }
	 
     if($getActStat[0]!=-1)
     { ?>
   		<div class="activation_notification">
   			This account has not been activated yet. Activate it by following the mail, we have sent you
   			on your registered Email address. Your account will be unaccessable after 72 hours<br /> from the time of registeration,
   			if not activated.
   			
   		</div><?php } ?>	  
   <div class="cover" id="cvr"></div>   
   <div class="dialogBox" id="subscribeApp">
    
      <div class="exclam"> :) </div><br />
	  <div id="welcome"> </div>
      <br /><hr /><br /><div id="oldSubs">
	   
         </div>
	 <div id="close_box" onclick="hideDialogBox(0.5, 1)"> X </div><br /><br />
	  <form action="" method="post" name="subcribe" id="subscribeForm">
		  <input type="hidden" name="appid" id="appid" /> 
	  </form>		  
	  <div id="button_subs">
	  <div class="subscribe_Button" id="lnk1">
	    
		  
	   <div class="blogBlock" id="tumblr" onmouseover="hover('lnk1','tumblr','tumblrurl')">Click here to subscribe & get going!</div>
			<div class="blogBlock" id="tumblrurl" onmouseout="out('lnk1','tumblr','tumblrurl')" onclick="document.getElementById('subscribeForm').submit();"> People will be informed about this & feeds appear in your stream! </div> 
		
		
      </div>
	  </div>
  </div>
     <div class="cover" id="cvr2"></div>   
  <div class="dialogBox" id="subscriberlist">
    <div id="heading_subslist"> Subscribers &raquo;
	<div id="close_box_small" onclick="hideSubsList()">X</div>
	</div>
	<div id="subslist">
	 
	</div>
	<br />
			 <hr style="width:70%;" /> 
	
  </div>
   
  </body>
</html>


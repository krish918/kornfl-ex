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
		header("location:../disconnected.html");
		exit;
	  }
	mysql_select_db("<db-name>", $con);
	session_start();
	$username='';
	$indiantime = time()+45000;
    $time =date("F j, Y | g:i a", $indiantime);	
	$loggedin =1;
	if(($_SESSION['user']=='')&&($_COOKIE['user']==""))	
		$loggedin =0;	
	if(isset($_GET['signout']))
		{
		    mysql_query("UPDATE corn_users SET lastIP = '$_SERVER[REMOTE_ADDR]', lastUserInfo = '$_SERVER[HTTP_USER_AGENT]' WHERE Email ='$_SESSION[user]'",$con);
			$current = time();
			$spent = $current - $_SESSION['time'];
			mysql_query("UPDATE corn_users SET timeSpent = timeSpent + '$spent'  WHERE Email ='$_SESSION[user]'",$con);
			
			mysql_query("UPDATE corn_users SET live = 0 WHERE Email = '$_SESSION[user]'", $con);
	    
			setcookie("user","",time()-3600);
			$getfeed = mysql_fetch_row(mysql_query("SELECT feedback FROM corn_users WHERE Email ='$_SESSION[user]'",$con));
			if($getfeed[0]==0)
			{
			mysql_query("UPDATE corn_users SET feedback=1 WHERE Email = '$_SESSION[user]'",$con);
			session_destroy();
			header("Location:../../index.php?logout");
			}
			else
			{
			session_destroy();
			header("Location:../../index.php");
			}
		}
	$proq=mysql_query("SELECT * FROM corn_users",$con);	
	while($bring = mysql_fetch_array($proq))
	{
	  if(($bring['Email'] == $_SESSION['user'])||($bring['Email']==$_COOKIE['user']))
		{
			$username = $bring['userName'];
			$proSavStat = $bring['profileSaved'];
			$id = $bring['userID'];
			$campCode = $bring['campusJoined'];
			$projStat = $bring['profileProjected'];
			
		}
	}
	if($id!=56)		
	mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$id','$_SERVER[REQUEST_URI]','$time')",$con);
	$sent=2;
?>
<!DOCTYPE html PUBLIC  "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesProfile" id="theahensbuilder" lang="en">
<HEAD>
      <TITLE>ahens Privacy Policy</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
	  <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/newdesign.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
</HEAD>
<BODY class="ahens" onload="startNotification(), updatenotif()">
<?php if($loggedin==1)
		  {	?>
		  <script> window.onload = loginstatus(), startNotification(), updatenotif(); </script> 
		  <?php }
		  ?> 
		<div class="topbar"> 
    <div style="position:absolute; left:80px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com';">
     ahens &copy; 2012
	 </div>
     <div style="position:absolute; left:700px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/about.php';"> About </div>
	 <div style="position:absolute; left:800px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/help.php';"> Help </div>
	 <div style="position:absolute; left:900px; top:10px;color:white;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/privacy.php';"> Privacy </div>
  </div>
  
  <div class="midcircle"></div>
  <div class="ahenslogo"> <a href="javascript: todefault();"><img src="http://kf.ahens.com/images/ahensfinal.png" border=0/> </a></div>
  <div class="tagline"> a.gentle.way.to.discover.more<br />about.people.in.your.campus</div>
    <div class="backcontent" style="left:549px;width:600px;height:400px;text-align:center;font-family:verdana;opacity:0.97;">
	<div id="helparrow" style="right: 80px;"></div>  
	     <h1 style="font-family:tahoma;">Invasion on your personal life?</h1><br /><br />
		<span style="font-size:18px;"> You are here probably because, either you are smart or you have nothing else to do!</span> <br /><br />
		Allow us to disappoint you a little more. We are changing our privacy policy. Why? Beacuse we lost some sweet users due to our last extremely 
		honest privacy statements? No, not at all.
		<br /><br />Whatever, that counts for another dodge of patience from you all dear users. The new policy will appear soon.<br /><br />
		<a id="prof2" href="http://kf.ahens.com">Leave this place </a>
	</div>
	
	<?php if($loggedin==1)
		{ ?>
	 <DIV id="notif"></DIV>	
	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
         <?php } ?>
</BODY>
</HTML>
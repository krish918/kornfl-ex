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
		
	if($_POST['q']!='')
		{
		$query = validate($_POST['q']);
		if(mail("krish@ahens.com", $username." needs help","from : ".$_SESSION['user']." |  query : ".$query))
			$sent =1;
		else $sent =0;
		}
	function validate($in)
		{
		  $in =trim($in);
		  $in = htmlspecialchars($in);
		  return $in;
		 } 
?>
<!DOCTYPE html PUBLIC  "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesProfile" id="theahensbuilder" lang="en">
<HEAD>
      <TITLE>Quick Guide</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
	  <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/newdesign.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
</HEAD>
<BODY class="ahens">
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
	 <div style="position:absolute; left:800px; top:10px; color:white;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/help.php';"> Help </div>
	 <div style="position:absolute; left:900px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/privacy.php';"> Privacy </div>
  </div>
  <div class="midcircle"></div>
  <div class="ahenslogo"> <a href="javascript: todefault();"><img src="http://kf.ahens.com/images/ahensfinal.png" border=0/> </a></div>
  <div class="tagline"> a.gentle.way.to.discover.more<br />about.people.in.your.campus</div>
    <div class="backcontent">
	<div id="helparrow"></div>
	<?php if($loggedin==0)
	 { ?>
	<h2> It seems you don't want to login, but still we will help you!</h2>
	  <?php }
	   else
	    {?>
		<h2> Hello <?php echo $username; ?>! How can we help you? </h2>
		<?php } ?>
	  <div style="font-size:17px;"> Click on relevant topics </div>
            <br /><br />
		<div id="list">	
        <ul type="square">
		  <li> <a id="prof2" href="javascript: showhelp(1);">What bird is ahens?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">What can I do at ahens.com?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">How to start at ahens.com?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">How can I choose my campus and what if my campus is not present at ahens?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">How to show my tweets at ahens homepage? </a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">What is whistle or how can my name and quotes be displayed at ahens homepage?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">What are kTeasers?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">How to search people in my campus and rate them?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">How to send messages to people?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">What is Flakes?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">Why is a stone kept at ahens?</a> </li>
		  <li> <a id="prof2" href="javascript: showhelp(1);">How to change my account settings?</a> </li>
		  Now, you yourself explore the rest!
        </ul>		
	    </div>
		<div id="helpanswers">
		<?php if($loggedin==1)
			{ ?>
		<FORM method="post" action="">
		 <INPUT type="text" name="q" style="width:500px; height:30px;font-family:verdana;font-size:14px;color:#909090;" value="Enter your queries, suggestions or complains and press Enter" onfocus="this.value='';this.style.color='black';"/>
		</FORM> <br />
		<?php } ?>
		
		         <?php
		 if($sent==1)
			{
			   echo "<DIV style='background-color:#ffcccc;border:1px solid #ff9090; padding:6px;font-family:tahoma;font-size:15px;'> Your query has been sent to ahens. Thanks for communicating!<br /> An email will be sent to you in reply within 24 working hours. </DIV>";
			}
		else if($sent==0)
			{
				echo "<DIV style='background-color:#ffcccc;border:1px solid #ff9090; padding:6px;font-family:tahoma;font-size:15px;'> Some error ocurred at ahens server. Please try a moment later. </DIV>";
			}
        ?>		
			We have not finished our homework yet. Still writing the help documentation!<br /><br />
			This is not at all an unforgiveable mistake. Please spare us!
		</div>
		 
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
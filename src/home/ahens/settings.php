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
 $conStone=mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>");
	if(!$con)
	  {
	header("location:http://kf.ahens.com/error/?conerror=1");
		exit;
	  }
	  if(!$conStone)
	  {
	header("location:http://kf.ahens.com/error.html");
		exit;
	  }
	mysql_select_db("<db-name>", $con);
	mysql_select_db("<db-name>", $conStone);
	session_start();
	$username='';
	   $indiantime = time()+45000;
   $dat =date("F d, Y | h:i a",$indiantime);
	if(isset($_GET['signout']))
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
	$getid=mysql_fetch_row(mysql_query("SELECT userID,campusJoined FROM corn_users WHERE Email = '$_SESSION[user]'",$con));
	$id =$getid[0];
	$campCode=$getid[1];
	$invalid=-1;
	$invalid2=-1;
	$shown =-1;
	$hidden =-1;
	$project =-1;
	$unproject =-1;
	if($id!=56)	
	mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$id','$_SERVER[REQUEST_URI]','$dat')",$con);
	if(isset($_POST['cp']))
	{
	 if(($_POST['oldpass']!='') && ($_POST['newpass']!='') && strlen($_POST['newpass'])>5)
	 {
	  $pass = mysql_fetch_row(mysql_query("SELECT password FROM corn_users WHERE password = '$_POST[oldpass]' and userID ='$id'",$con));
	  if($pass == null)
	      $invalid =1;		
	   else if(mysql_query("UPDATE corn_users SET password = '$_POST[newpass]' WHERE userID = '$id'",$con)) 
                 $invalid =0;
            else $invalid =2;
	   }
	  else $notentered =1; 
     }
    if(isset($_POST['cn']))
     {
      if(($_POST['oldpass2']!='')&&($_POST['newname']!=''))
       {
	     $pass2 = mysql_fetch_row(mysql_query("SELECT password FROM corn_users WHERE password = '$_POST[oldpass2]' and userID ='$id'",$con));
	     if($pass2 == null)
	     $invalid2 =1;
		  else if((mysql_query("UPDATE corn_users SET userName = '$_POST[newname]' WHERE userID = '$id'",$con))&&(mysql_query("UPDATE profiles SET fullName = '$_POST[newname]' WHERE userID = '$id'",$con))&&(mysql_query("UPDATE stone SET name = '$_POST[newname]' WHERE userID = '$id'",$conStone)))
		         $invalid2 =0;
		    else
                 $invalid2=2;	
				 
        }
		else $notentered =2;
      }
    if($_GET['email']!='')
	{
      if($_GET['email']==1)
	  {
        if(mysql_query("UPDATE corn_users SET emailHidden = 0  WHERE userID ='$id'",$con))
		  $shown =1;
         else $hidden =0;
       }
     if($_GET['email']==0)
       {
         if(mysql_query("UPDATE corn_users SET emailHidden =1  WHERE userID ='$id'",$con))	
             $hidden =1;
          else
              $shown =0;	
       }
	}   
	if($_GET['project']!='')
	  {
	   if($_GET['project']==0)
	   {
    	mysql_query("UPDATE campusdata SET projectionCount = projectionCount-1 WHERE campusCode = '$campCode'",$con);
			mysql_query("UPDATE corn_users SET profileProjected = 0 WHERE Email = '$_SESSION[user]'",$con);
			mysql_query("UPDATE profiles SET projected = 0 WHERE email = '$_SESSION[user]'",$con);
		   $unproject =1;	
		}
		if($_GET['project']==1)
		{
       mysql_query("UPDATE campusdata SET projectionCount = projectionCount+1 WHERE campusCode = '$campCode'",$con);
			mysql_query("UPDATE corn_users SET profileProjected = 1 WHERE Email = '$_SESSION[user]'",$con);
			mysql_query("UPDATE profiles SET projected = 1 WHERE email = '$_SESSION[user]'",$con);
		 	$project =1;
          }
       }		  
        		
		$proq=mysql_query("SELECT * FROM corn_users",$con);	
	while($bring = mysql_fetch_array($proq))
	{
	  if(($bring['Email'] == $_SESSION['user'])||($bring['Email']==$_COOKIE['user']))
		{
			$username = $bring['userName'];
			$proSavStat = $bring['profileSaved'];
		
		
			$projStat = $bring['profileProjected'];
			$acc = $bring['accountCreated'];
			 $logCount=$bring['LoginCount'];
			 $lastLogin = $bring['LastLogin'];
			 $ipo = $bring['lastIP'];
			 $ua = $bring['lastUserInfo'];
			 $emailhid = $bring['emailHidden'];
			 $spentTime =$bring['timeSpent'];
		}
	}
   $days =0; $hours=0; $min =0; $sec=0;	
   $days = (int)($spentTime/86400);
   $rem1 = $spentTime%86400;
   $hours = (int)($rem1/3600);
   $rem2 = $rem1%3600;
   $min = (int)($rem2/60);
   $sec = $rem2%60;   

?>
<!DOCTYPE html PUBLIC  "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesProfile" id="theahensbuilder" lang="en">
<HEAD>
      <TITLE> General Settings</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
	  
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
</HEAD>
<BODY class="kornContent" onload="startNotification(), updatenotif(), loginstatus()">
   
		 
		   
    <DIV class="mainContainer" style="font-family:tahoma;font-size:13px;text-align:center;width:500px;padding-left:194px;padding-right:196px;">		
	<br />
	<?php
	   if($invalid==0)
	     echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Password Updated succesfully</DIV>";
		else if($invalid==1)
             echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Incorrect Old Password</DIV>";
	      else if($invalid==2)
               echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Some error ocurred at ahens server. Please try later!</DIV>";  
        if($invalid2==0)
               echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Name Updated succesfully</DIV>"; 	
			else if($invalid2==1)
                  echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Incorrect Password</DIV>";
     			else if($invalid==2)
                     echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Some error ocurred at ahens server. Please try later!</DIV>";
	    if(($hidden==0)||($shown==0))
                echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Some error ocurred at ahens server. Please try later!</DIV>";	
           else if($hidden==1)
                  echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Your Email address will not be displayed to others now!</DIV>";
              else if($shown==1)
                    echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Your Email address is now visible on the network.</DIV>";
          if($project==1)
                  echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Your Profile has been projected.</DIV>";
          if($unproject==1)
                    echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Your profile has been unprojected.</DIV>";	
          if($notentered==1)
                  echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Short or empty Password. Enter at least 6 characters.</DIV>";
	      if($notentered==2)
                  echo "<DIV style='background-color:#ffcccc;border:solid 1px #ff9090;width:500px;padding:5px;'>Short password or empty name. Enter at least 6 characters.</DIV>";
		
   			 ?>
	<br /><img src="../../images/settings.jpg" alt="settings" />
	  <br />
	  <DIV style="font-family:'lucida sans unicode',tahoma;font-size:28px;color:#bbbbee;">
	    GENERAL SETTINGS
      </DIV><br /><br />
	  <DIV style="font-size:14px;background-color:#eeeeff;border:groove 2px #aaaaff;">
	   <br /> Your account was created on : <?php echo $acc; ?> IST<br  /><br />
	  </DIV><br />
	  <?php if($id == 54) echo "<span style='color:#ff9292;'>This is the first account on ahens!</span>"; 
	        else if($id == 191) echo "<span style='color:#ff9292;'>This is the first female account in BIT Patna!</span>";
            ?> <br />
	  <br />
      <DIV class="setting">You logged into your account : <span style="font-weight:bold;"><?php echo $logCount; ?> times</span> <br /><br /></DIV>
	  <DIV class="setting"><br />You approximately spent :<span style="font-weight:bold;"> <?php if($days!=0) echo $days." Days "; 
       if($hours!=0) echo $hours. " Hours ";  if($min!=0) echo $min." Minutes "; ?> on Kornflakes </span><br /><br /></DIV><br /><br />
	  <DIV style="font-weight:bold;background-color:#eeffee;width:500px;border:solid 1px #98ff98;padding:7px;"> Last Login information</DIV><br />
	  <DIV class="setting">Time of Login : <?php echo $lastLogin; ?><br /><br /></DIV><br />
      <DIV class="setting">IP Address : <?php echo $ipo; ?><br /><br /></DIV><br />
	  <DIV class="setting">Browser Used : 
	   
	 <?php 
	
	 if (!stripos($ua ,"firefox")===false)
	               echo "Mozilla Firefox";
	    else if(!stripos($ua ,"presto")===false)	echo "Opera";
             else if(!stripos($ua ,"msie")===false)
                                           echo "Microsoft Internet Explorer";
                 else if(!stripos($ua ,"Chrome")===false)
                                   echo "Google Chrome";	
                      else if(!stripos($ua, "safari")===false)
									echo "Safari";
							else echo "Unknown Browser";
	 ?>
	<br /><br /></DIV><br />
    <DIV class="setting">Operating System : <?php
			if(!stripos($ua ,"Windows nt 6.1")===false) echo "Windows 7";	
			else if(!stripos($ua ,"mac")===false) echo "Macintosh";
			      else if(!stripos($ua ,"windows nt 6.0")===false) echo "Windows Vista";
				       else if(!stripos($ua ,"windows nt 5.1")===false) echo "Windows XP";
					      else if(!stripos($ua ,"wnidows nt 5.0")===false) echo "Windows Server 2000";
						     else if(!stripos($ua ,"windows")===false) echo "Deprecated Windows Operating System. Please upgrade your OS!";
							    else if(!stripos($ua ,"mac")===false) echo "Mac OS X";
								  
								     else if((!stripos($ua ,"android")===false) && (!stripos($ua ,"mobile")===false)) echo "Android Mobile Phone";
									     else if((!stripos($ua ,"android")===false) && (!stripos($ua ,"tablet")===false)) echo "Android Tablet PC";
										 else if(!stripos($ua ,"linux")===false) echo "Linux Desktop";
			  else echo "Unidentified OS";
			  ?>
    <br /><br /></DIV><br />
	<DIV class="setting">Architecture : <?php
	       if(!stripos($ua ,"x86")===false) echo "32-Bit Processor";	
			
			      else if((!stripos($ua ,"x64")===false) || (!stripos($ua ,"wow64")===false)) echo "64-Bit Processor";
				      else echo "No details sent about architecture.";
	?><BR /><BR /></DIV><br /><br />
     <hr /><br /><br />	
	 <?php if($emailhid==0)
	  { ?>
        <a href="http://kf.ahens.com/home/ahens/settings.php?email=0" class="settinglink" style="text-decoration:none;"> Hide my Email address </a>	
	  <?php }  
	  else { ?>
        <a href="http://kf.ahens.com/home/ahens/settings.php?email=1" class="settinglink" style="text-decoration:none;"> Show my Email address </a>
       <?php } ?>
      <br /><br />
        <span id="changep" onclick="changepass()" style="cursor:pointer;" class="settinglink"> Change your Password </span><br /><SPAN id="changec" class="settinglink"></SPAN>
		<SCRIPT type="text/javascript">
		   function changepass()
		     {
			document.getElementById("changec").innerHTML = "<br /><FORM action='' method='post'>Enter your old password : <span id='other'><INPUT type='password' name='oldpass' /><br /><br />Enter your new password: <INPUT type='password' name='newpass' /><br /><br /><button id='button' type='submit' name='cp' />Change</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='submit' id='button' name='n'>Close</button></span></FORM>";
   			 }
	    </SCRIPT>
		<br />
	<?php if($projStat==0) { ?>
	      <a href="http://kf.ahens.com/home/ahens/settings.php?project=1" class="settinglink" style="text-decoration:none;"> Project Your Profile </a>
		  <?php }
		  else 
		     { ?>
		  <a href="http://kf.ahens.com/home/ahens/settings.php?project=0" class="settinglink" style="text-decoration:none;">Unproject Your Profile</a>
           <?php  } ?>
         <br /><br />
           <span id="changen" onclick="showname()" class="settinglink" style="cursor:pointer;">Change Your Name</span><br /><SPAN id="changed" class="settinglink"></SPAN>
         <SCRIPT type="text/javascript">
            function showname()
                 {
			    document.getElementById("changed").innerHTML ="<br /><FORM action='' method='post'>Enter your Password: <INPUT type='password' name='oldpass2' /><br /><br />Enter your new name : <INPUT type='text' name='newname' /><br /><br /><button id='button' type='submit' name='cn'>Change</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='submit' id='button'>Close</button></FORM>";	 
	              }
		  
         </SCRIPT>
	    <br /><br /><span style="font-size:12px;color:#aaaaaa;"> Kornflakes&trade; right now is in beta version. Release candidate is expected soon.</span>
		 <br /><br />
        
        
         		 
		<!--<DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV> -->
        <DIV id="end" style="position:absolute;bottom:-90px;left:250px;height:40px;">
	     <br />AHENS &copy; 2012
	  </DIV>
	  
	</DIV>
         <div class="bottombar" style="left:0px;width:1347px;"> 
     <div style="position:absolute; left: 30px;top: 2px;">
           <img src="http://kf.ahens.com/images/ahensfinalsmall.png" />
     </div>		   
    <div style="position:absolute; left:600px; top:10px;color:#656565;">
     ahens &copy; 2012
	 </div>
     <div style="position:absolute; left:1060px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/about.php';"> About </div>
	 <div style="position:absolute; left:1160px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/help.php';"> Help </div>
	 <div style="position:absolute; left:1260px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/privacy.php';"> Privacy </div>
  </div>
       <!--  <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='../search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='../profile.php';">My Profile</span>
				<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')" /></span>			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
	          </DIV>
        </DIV> -->
        <DIV id="searchbox" style="text-align:center;"></DIV>
	<!-- <DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	<FORM action="" method="GET">
	   <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='settings.php';">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='theahensbuilders.php';">The Ahens Builders</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='help.php';">Help</DIV>
	   <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out</button>
	   </FORM>
	 </DIV>   -->
	   <div class="deck">
		<div class="decklink" onclick="location.href='http://kf.ahens.com/home/search';">Search</div>
		<div class="decklink" style="left:270px;" onclick="location.href='http://kf.ahens.com/home/profile.php';">Profile</div>
		<div style="position:absolute;top:5px;left:490px;">
		  <input type="text" value="Enter a name to search" id="qs" class="qsearchtextbox" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')"/>
		 </div> 
		 <div id="backProEff" onclick="document.getElementById('menunew').style.visibility='hidden';this.style.visibility='hidden';"> </div>
		 <div id="callmenu" style="position:absolute;top:1px;left:950px;cursor:pointer;"><img src="http://kf.ahens.com/images/profile.png" border=0 /></div> 
				   
				   <script>
					  var cal = document.getElementById("callmenu");
					  var shown=0;
					  cal.onclick = function()
								{
								wtimer(); 
								if(shown==0)
								 {
									document.getElementById('menunew').style.visibility='visible';
									document.getElementById('backProEff').style.visibility='visible';
									shown =1;
								  }
								 else
									{
									  document.getElementById('menunew').style.visibility='hidden';
									  document.getElementById('backProEff').style.visibility='hidden';
									  shown=0;
									}  
								};
								
					</script>			
		    <div class="menunew" id="menunew">
			  
				   <div style="position:absolute; top:-21px; right:40px;width:0;height:0; border-right:20px solid transparent;border-left:20px solid transparent;border-bottom:20px solid #aaaaaa;">
			       </div>	
				   <div style="position:absolute; top:-20px; right:40px;width:0;height:0; border-right:20px solid transparent;border-left:20px solid transparent;border-bottom:20px solid rgb(247,247,247);">
			       </div>
                   <div style="padding:8px;border-bottom:1px solid #aaaaaa;font-weight:bold;font-size:15px;"><?php echo $username; ?> </div>
					<div style="padding:8px;" id="menulnk" onclick="enlarge('timer',11,0)"> Whistle <div style="position:absolute;right:5px;font-style:italic;font-family:arial;font-size:11px;display:inline-block;;color:#777777;" id="timer"></div></div> 
					<div style="padding:8px;" id="menulnk" onclick="location.href='http://kf.ahens.com/home/ahens/theahensbuilders.php';">Credits</div>
					<div style="border-bottom:1px solid #cccccc;"></div> 
					<div style="padding:8px;" id="menulnk" onclick="location.href='http://kf.ahens.com/home/ahens/help.php';">Help</div>
					<div style="padding:8px;" id="menulnk" onclick="location.href='http://kf.ahens.com/home/ahens/settings.php';">Settings</div>
					<div style="border-bottom:1px solid #cccccc;"></div> <form action="" method="get">
                     <button style="padding:8px;border-style:none;background-color:transparent;width:190px;text-align:left;font-family:arial;" id="menulnk" type="submit" name="signout" >Log out</button></form>
					<div style="padding:0px 5px 5px 5px;font-size:9px;color:#606060;font-family:tahoma;">AHENS TRIVIA : The name 'ahens' was generated accidently, through a very simple program written in C !
						</div>
			</div> 
  </div>
 <DIV class="glassSignup2">
	<DIV class="sideLink2" id="sideLnk1" onmouseover="sidelinkover('sideLnk1')" onmouseout="sidelinkout('sideLnk1','Common Room')" onclick="location.href='http://kf.ahens.com/home/users/commonroom.php';">
	Common Room
	</DIV>
    <DIV class="sideLink2" id="sideLnk2">
	<Form action="../index.php" method ="get"><button id="ed" type="submit" name="editPro" style="font-size:15px;font-family:'tahoma';cursor:pointer;border-style:none;background-color:white;color:rgb(120,120,120);" onmouseover="sidelinkover('ed')" onmouseout="sidelinkout('ed', 'Edit Profile')">Edit Profile</button></form>
	</DIV>
    <DIV class="sideLink2" id="sideLnk3" onmouseover="sidelinkover('sideLnk3')" onmouseout="sidelinkout('sideLnk3','Mirror')" onclick="location.href='http://kf.ahens.com/home/mymirror.php';">
	Mirror
	</DIV>
	<DIV class="sideLink2" id="sideLnk4"  onmouseover="sidelinkover('sideLnk4')" onmouseout="sidelinkout('sideLnk4','Flakes&trade;')" onclick="location.href='http://kf.ahens.com/home/flakes.php';">
	Flakes&trade;
	</DIV> 
	<DIV class="sideLink2" id="sideLnk5"  onmouseover="sidelinkover('sideLnk5')" onmouseout="sidelinkout('sideLnk5','Stone&trade;')" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade;
	</DIV>
    <DIV style="font-weight:bold;" class="sideLink2" id="sideLnk6"  onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 <span style="font-size:15px;"> Simple small apps that are tangent to your lives. Soon by AHENS.</span>

  
 
    </DIV> 
    
	<DIV class="cover" id="cvr"></DIV>
	 <DIV id="notif"></DIV>	
	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
 
</BODY>
</HTML>

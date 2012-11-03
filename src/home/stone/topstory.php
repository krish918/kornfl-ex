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
 $conStone=mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>");if(!$con)
	     {
		header("location:http://kf.ahens.com/error/?conerror=1");
		exit;
	     }
      mysql_select_db("<db-name>",$con);
      session_start();
      $username = '';
	  $indtime = time() + 45000;
	  $time = date("F j, Y | g:i a",$indtime);
      $query = mysql_query("SELECT * FROM corn_users", $con);
      while($row = mysql_fetch_array($query))
       {
          if(($row['Email']==$_SESSION['user'])||($row['Email']==$_COOKIE['user']))
             {
				$uid = $row['userID'];
               $username = $row['userName'];
			   if($row['campusJoined'] == '')
                      {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}	
               $campCode = $row['campusJoined'];				  
				   
             }
       }
      if($uid!=56)	
	  mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$time')",$con); 
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
	  mysql_select_db("<db-name>",$conStone);		
      	  
	  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesSearch" id="profileIndex" lang="en">
  <HEAD>
      <TITLE>  Stone : Top Stories</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
  </HEAD>
  <BODY class="kornContent" onload="startNotification(), updatenotif(), bringstone(0,15), loginstatus()">
  
    <DIV class="mainContainer">
	    
	    <DIV class="stonetitle">STONE<span style="font-weight:normal;">&trade;</span> </DIV>
	    <DIV class="stonecontent">
		<DIV class="readhead"><br />
		 <span style="font-size:16px;font-family:verdana;font-style:italic;">Showing most influencial stories of the week</span>
		 
		</DIV>
		  <br />
		  <br /><br /><br />
		  <DIV id="getstone">	
		 <?php
		 $sql =mysql_query("SELECT * FROM stone WHERE benefitCount<>0 ORDER BY benefitCount DESC LIMIT 0,10",$conStone); 
		  while($st = mysql_fetch_array($sql))
			{
			 $getc = mysql_fetch_row(mysql_query("SELECT campusJoined FROM corn_users WHERE userID = '$st[userID]'",$con));
			 $campName = mysql_fetch_row(mysql_query("SELECT campusName FROM campusdata WHERE campusCode = '$getc[0]'",$con));
			 if($st['place']!='' && $st['sources']!='')
			 {
			   ?>
			     <DIV style="border-bottom:1px double #aaaaaa;padding:10px;" onmouseover="this.style.backgroundColor='#efefff';showbrief(<?php echo $st['stoneID'];?>,event);" onmouseout="this.style.backgroundColor='transparent';hidebrief();" onclick="location.href='readone.php?showstory=<?php echo $st['stoneID'];?>';">
				 <DIV style='padding:7px; cursor:pointer;font-size:14px;'>
				   <span style='font-weight:bold;'> <?php echo $st['name']; ?> </span> learns <span style='font-weight:bold;'> <?php echo $st['title']; ?> </span>
				   at <?php echo $st['place']; ?> 
				   <br />
				     <span style="font-size:13px; color:#707070;"> Source(s) : <?php echo $st['sources']; ?></span> <br /><br />
			     <DIV style="font-family:verdana; font-size: 12px; font-style:italic;color:#909090;text-align:right;">a profile from <?php echo $campName[0]; ?></DIV>
			     <DIV style="font-family:tahoma;font-size:10px;color:#bbbbbb;text-align:right;"> <?php echo $st['time']; ?> </DIV>
				 <div style="color:#1C85D4;"><span style="font-size: 20px;"><?php echo $st['benefitCount']; ?></span> people</DIV>
			     </DIV>
				 </DIV>
				 <?php
				  $count++;
				 }
				else
					{
					?>
					<DIV style="border-bottom:1px double #aaaaaa;padding:10px;" onmouseover="this.style.backgroundColor='#efefff';showbrief(<?php echo $st['stoneID'];?>,event);" onmouseout="this.style.backgroundColor='transparent';hidebrief();" onclick="location.href='readone.php?showstory=<?php echo $st['stoneID'];?>';">
					<DIV style="padding:7px;cursor:pointer;">
					 <DIV style="font-weight:bold; font-size: 14px;"> <?php echo $st['title']; ?></DIV>
					  <DIV style="font-size:14px;color:#707070;"> - authored by <?php echo $st['name']; ?> </DIV><br />
					 <DIV style="font-family:verdana; font-size: 12px; font-style:italic;color:#909090;text-align:right;">a profile from <?php echo $campName[0]; ?></DIV> 
					 <DIV style='font-family:tahoma;font-size:10px;color:#bbbbbb;text-align:right;'> <?php echo $st['time']; ?> </DIV>
					 <div style="color:#1C85D4;"><span style="font-size: 20px;"><?php echo $st['benefitCount']; ?></span> people</DIV>
					</DIV>
					</DIV>
					<?php
					  $count++;
				     }
			      
			 } 
			if($count==0)
			   echo "<DIV style='background-color:#ffcccc;border:1px solid #ff9090;padding:7px;font-size:14px;'> There were no eligibile stories at stone.</DIV><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
			 
		   else if($count<3)
						echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";	
		    else echo "<br /><br />";				
			 ?>
			 
		       
			   </DIV>
		   </DIV>	 
		<div class="stonelinks">
		<a id="stlink" href="index.php">Introduction</a> 
		<br /><br /><br />
		<a id="stlink" href="share.php">Share</a>
		<br /><br /><br />
		<a id="stlink" href="read.php">Read</a>
		<br /><br />
		<div class="active">Top Stories<div id="arrow"></div><div id="arrowin"></div></div>
		<br /><br /><br />

		<a id="stlink" href="mostread.php">Most Read</a><br /><br /><br />
		
		</div>
		
	   <!-- <DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV>  -->
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
   
      <!--    <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='../search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='../profile.php';">My Profile</span>
				<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')" /></span>			
			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  </DIV>
            </DIV>  -->
			<DIV id="searchbox" style="text-align:center;"></DIV>
<!--	<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	 <FORM action="index.php" method="GET">
	    <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/settings.php';">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/theahensbuilders.php';">The Ahens Builders</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/help.php';">Help</DIV>
	  <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out
	    </button>
		</FORM>
	 </DIV>  -->
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
	<DIV class="sideLink2" id="sideLnk1" onmouseover="sidelinkover('sideLnk1')" onmouseout="sidelinkout('sideLnk1','Common Room')" onclick="location.href='../users/commonroom.php';">
	Common Room
	</DIV>
    <DIV class="sideLink2" id="sideLnk2">
	<Form action="../index.php" method ="get"><button id="ed" type="submit" name="editPro" style="font-size:15px;font-family:'tahoma';cursor:pointer;border-style:none;background-color:white;color:rgb(120,120,120);" onmouseover="sidelinkover('ed')" onmouseout="sidelinkout('ed', 'Edit Profile')">Edit Profile</button></form>
	</DIV>
    <DIV class="sideLink2" id="sideLnk4" onmouseover="sidelinkover('sideLnk4')" onmouseout="sidelinkout('sideLnk4', 'Mirror')"  onclick="location.href='../mymirror.php';">
	Mirror
	</DIV>
	<DIV class="sideLink2" id="sideLnk3" onmouseover="sidelinkover('sideLnk3')" onmouseout="sidelinkout('sideLnk3', 'Flakes&trade;')" onclick="location.href='../flakes.php';">
	Flakes&trade;
	</DIV>
    <DIV class="sideLink2" id="sideLnk5" style="color:rgb(20,20,20);" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade; &gt;
	</DIV>
         <DIV style="font-weight:bold;" class="sideLink2" id="sideLnk6"  onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 <span style="font-size:15px;"> Running towards the top, is the one last mirage left in this world.</span>

  
 
    </DIV>
    
	<DIV class="cover" id="cvr"></DIV>
	 <DIV id="notif"></DIV>
	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
 <DIV id="getbrief">
				  <DIV id="cont"></DIV>
					<DIV style="position:absolute; top:50px;left:-20px;width:0;height:0;border-top:20px solid transparent;border-bottom:20px solid transparent; border-right:20px solid black;"></DIV>
					<DIV style="position:absolute; top:50px;left:-19px;width:0;height:0;border-top:20px solid transparent;border-bottom:20px solid transparent; border-right:20px solid white;"></DIV>
		</DIV>
  </BODY>	
</HTML>
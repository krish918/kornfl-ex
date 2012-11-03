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
		header("location:http://kf.ahens.com/error.html");
		exit;
	     }
      mysql_select_db("<db-name>",$con);
      session_start();
      $username = '';
      $query = mysql_query("SELECT * FROM corn_users");
	   $indiantime = time()+45000;
	  $dat =date("F:d, Y | h:i a",$indiantime);
	  
      while($row = mysql_fetch_array($query))
       {
          if(($row['Email']==$_SESSION['user'])||($row['Email']==$_COOKIE['user']))
             {
			   $uid = $row['userID'];
               $username = $row['userName'];
			   if($row['campusJoined'] == '')
                      {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}		
				else $campCode =$row['campusJoined'];
             }
       }
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
          if(!isset($_GET['userid']))
             
            {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}	
             
         $useridT = $_GET['userid'];
         $target = mysql_query("SELECT userName, campusJoined FROM corn_users WHERE userID='$useridT'");
         $name = mysql_fetch_row($target);
         $usernameT = explode(" ",$name[0]); 
		 $campCodeT = $name[1];
	if($uid!=56)		 
	 mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);	 
?>         

<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesPeerFlakes" id="peerFlakesShowCase" lang="en">
  <HEAD>
      <TITLE> <?php echo $usernameT[0];?>'s Flakes</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK rel="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
  </HEAD>
  <BODY class="kornContent" onload="startNotification(), updatenotif(), loginstatus()">
  
    <DIV class="mainContainer" style="font-family:tahoma;font-size:13px;text-align:left;width:500px;padding-left:194px;padding-right:196px;">
   <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
   <DIV style="font-weight:bold;font-family:verdana;font-size:20px;">Flakes By <?php echo $name[0]; ?></DIV>
   <br /><br /><DIV style="font-family:'lucida sans unicode';text-align:center;">SEARCH FLAKES ABOUT<br />
   <INPUT type="text" size="50" id="searchAbout" style="text-align:center;" onkeyup="searchpeerflakes(this.value,'<?php echo $useridT; ?>')" /></DIV>
   <SCRIPT type="text/javascript">
         function searchpeerflakes(query,id)
          {
           document.getElementById("loader").innerHTML="<img src='http://kf.ahens.com/images/ajax-loader.gif' alt='loading' border=0 />";
           var http;
           if(window.XMLHttpRequest)
             http = new XMLHttpRequest();
           else
             http = new ActiveXObject("Msxml2.XMLHTTP");
           http.onreadystatechange=function()
             {
             if(http.readyState==4 && http.status==200)
                {
                   document.getElementById("loader").innerHTML="";
                   document.getElementById("msg").innerHTML="Showing Search Results";
                   document.getElementById("showall").innerHTML="<a style='text-decoration:none;' href='peerflakes.php?userid="+id+"'>Show All</a>";
                   document.getElementById("flakes").innerHTML=http.responseText;
                 }
             }
           http.open("get","searchflakes.php?query1="+query+"&id="+id,true);
           http.send();  
           }                                    
   </SCRIPT>
   <DIV id="showall" style="position:absolute;top:184px;left:600px;"></DIV>
   <DIV id="loader" style="text-align:center;"> </DIV><br /><hr /><br />
   <span id="msg" style="font-family:verdana;font-style:italic;">Showing All Flakes</span>
   <br /><br /><DIV id="flakes">
   <?php 
         $count = 0;
       $release = mysql_query("SELECT * FROM flakes WHERE userID='$useridT' AND hidden=0 ORDER BY flakesID DESC",$con);
          while($getFlakes = mysql_fetch_array($release))
           {
              $isql =mysql_query("SELECT profileProjected FROM corn_users WHERE userID ='$getFlakes[userID]'",$con);
              $checkProj = mysql_fetch_row($isql);
                echo "<DIV style='border-style:solid; border-width: 0px 0px 1px 0px;border-color:#90dd50;padding:4px;'><div style='font-weight:bold;font-family:verdana;background-color:#eeeeff;'>";
                if($checkProj[0]==1) echo "<a style='text-decoration:none;color:#8967ff;' href='showProfile.php?userID=".$getFlakes['userID']."'>".$getFlakes['flaker']."</a>"; 
                   else echo $getFlakes['flaker'];
                    echo "<span style='font-weight:normal;'> flaked about</span> ";
                      if(($getFlakes['about']=='myself')||($getFlakes['about']=='self'))
                          echo "himself"; 
                      else
                          echo $getFlakes['about'];
                       echo "</div><br /><span style='font-size:14px;'>".$getFlakes['flakes']."</span><br /><br />";
                   $agSql = mysql_query("SELECT flakesID, agreeID,disagreeID FROM agreelist WHERE (flakesID='$getFlakes[flakesID]') AND (agreeID = '$uid' OR disagreeID ='$uid')",$con); 
                  $checkAgree = mysql_fetch_row($agSql);
                   if($checkAgree==NULL)
                       {?><span style="color:#cc8080;font-size:12px;">
                       <?php echo $getFlakes['agree']; ?>  Agree</span> | <span style="color:#cc8080;font-size:12px;"><?php echo $getFlakes['disagree']; ?> Disagree</span><br /> 
                       <?php } 
                     else if($checkAgree[0]!=0 && $checkAgree[2]!=0)
                        { ?> <span style="color:#cc8080;font-size:12px;">
                       <?php echo $getFlakes['agree']; ?>  Agree</span> | <span style="color:#cc8080;font-size:12px;"><?php echo $getFlakes['disagree']; ?> Disagree</span><br /> 
                       <?php }  
                        else if($checkAgree[0]!=0 && $checkAgree[1]!=0)
                           { ?> <span style="color:#cc8080;font-size:12px;">
                       <?php echo $getFlakes['agree']; ?>  Agree</span> | <span style="color:#cc8080;font-size:12px;"><?php echo $getFlakes['disagree']; ?> Disagree</span><br /> 
                       <?php } 
                         echo "<span style='font-size:11px;color:#909090'>".$getFlakes['time']."</span></DIV><br />";
                        $count++;
                    }
               if($count==0) echo "<br /><br /><br /><DIV style='background-color:#ffcccc;border:solid 1px #ff9090;padding:4px;font-size: 14px;'> <br />No Flakes to Show!<br /><br /></DIV>";
               ?></div>
         <br /><hr /><br />
		<!-- <span style="font-size:12px;color:#aaaaaa;"> ahens, right now, is in beta version. Release candidate is expected soon.</span><br /><br />
<DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV> -->
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
   <!-- <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='../search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='../';"> My Profile </span>
<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')"/></span>				
			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  </DIV>
            </DIV> -->
              
     <DIV class="midPlate" style="top:145px;">
	  <span style="position:absolute;left:430px;font-family:tahoma;font-size:26px;font-style:bold;">
	  <?php 
	        $brow = mysql_query("SELECT * FROM campusdata");
			while($r = mysql_fetch_array($brow))
			  {
				if($r['campusCode']==$campCodeT)
				{
				  
			      echo $r['campusName'];
			       ?> </span><br /><span style="position:absolute;left:430px;top: 40px;color:#cccccc;">
				   <?php
			        if($r['Address1']!='')
			           echo $r['Address1'].","."<br />";
			       if($r['Address2']!='')
			             echo $r['Address2'].","."<br />";
			         if($r['City']!='')
			             echo $r['City'].","."<br />";
			           if($r['State']!='')
			                echo $r['State'].","."<br />";
			         
			           if($r['Country']!='')
			             echo $r['Country']; 
				   ?></span><span style="position:absolute;left:915px;font-size:14px;font-family:verdana;top:34px;border-style:solid;border-width:0px 0px 0px 1px;padding-left:6px; color:#bbbbbb;"><?php
				   echo $r['Established']." Established<br />";
				   ?><span style="font-size:26px;">
				   <?php
				   echo $r['networkCount']. "</span> People in network<br />";
				   ?><span style="font-size:26px;"><?php
				   echo $r['projectionCount']."</span> Projected profiles";
				   ?></span><?php
				}
			  }
	    ?>
	
    </DIV>
	<DIV id="searchbox" style="text-align:center;"></DIV>
  <!--  <DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	 <FORM action="" method="GET">
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
	<DIV class="sideLink2" id="sideLnk1" onmouseover="sidelinkover('sideLnk1')" onmouseout="sidelinkout('sideLnk1','Common Room')" onclick="location.href='commonroom.php';">
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
    <DIV class="sideLink2" id="sideLnk5"  onmouseover="sidelinkover('sideLnk5')" onmouseout="sidelinkout('sideLnk5','Stone&trade;')" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade;
	</DIV>
	<?php if($uid!=$useridT)
	{
	?>
	<DIV class="sideLink2" id="sideLnk6" onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','<?php echo $usernameT[0]; ?>\'s Mirror')" onclick="location.href='peerMirror.php?userid=<?php echo $useridT; ?>';">
	<?php echo $usernameT[0]; ?>'s Mirror
	</DIV>
	<DIV class="sideLink2" id="sideLnk7" onmouseover="sidelinkover('sideLnk7')" onmouseout="sidelinkout('sideLnk7','<?php echo $usernameT[0]; ?>\'s Profile')" onclick="location.href='showProfile.php?userID=<?php echo $useridT; ?>';">
	<?php echo $usernameT[0]; ?>'s Profile
	</DIV>
	<?php } ?>
	<DIV style="font-weight:bold;" class="sideLink2" id="sideLnk8"  onmouseover="sidelinkover('sideLnk8')" onmouseout="sidelinkout('sideLnk8','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 

  
 
    </DIV>
      <DIV id="notif"></DIV>	
	  <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
 
 </BODY>	
</HTML>	    
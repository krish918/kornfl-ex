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
      mysql_select_db("<db-name>",$con);
      session_start();
      $username = '';
	  $indiantime = time()+45000;
	  $dat =date("F:d, Y | h:i a",$indiantime);
	  
      $query = mysql_query("SELECT * FROM corn_users");
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
	  if($uid!=56)	 
	  mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con); 
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
	 $delete=2;    
	 if($_GET['deleteThread']!='')
           {
             if(mysql_query("DELETE FROM mirror WHERE threadID = '$_GET[deleteThread]'",$con))
                $delete=1;
              else $delete=0;  
             
           }   
     mysql_query("UPDATE mirror SET unreadFlag=0 WHERE target ='$uid'",$con);	
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesMirror" id="mirrorShowKornflakes" lang="en">

  <HEAD>
      <TITLE> <?php echo $username;?>'s mirror</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="http://kf.ahens.com/images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
      <STYLE type="text/css">
          #proGoLink
              {
              font-size:13px;
              font-weight:bold;
              color: #9090ff;
              text-decoration:none;
              }
              #proGoLink.hover
              {
              color: #5050ff;
              }
              </STYLE> 
			  
      <SCRIPT type="text/javascript" src="../korn_script.js"></SCRIPT>
       <SCRIPT type="text/javascript">
         /*   function submitThread()
            {
            
             var source= document.getElementById("threader").value;
             var target = document.getElementById("target").value;
             var thread = document.getElementById("thread").value;
             if(thread=='' || thread=="Maximum 320 Characters")
               {
                document.getElementById("msg").innerHTML="Please write at least a word on mirror";  
               }
             else
             {  
             var http;
             if(window.XMLHttpRequest)
               {
               http = new XMLHttpRequest();
               }
              else
                http = new ActiveXObject("Msxml2.XMLHTTP");
            
               document.getElementById("loader").innerHTML="<img src='../images/ajax-loader.gif' alt='loading' />";
   
              http.onreadystatechange =function()
                 {
           
                    
                    if(http.readyState==4 && http.status ==200)
                      {
                       document.getElementById("msg").innerHTML="Your thread was succesfully posted!";
                       document.getElementById("loader").innerHTML="";
                       document.getElementById("thread").value="";
                       document.getElementById("mirrorData").innerHTML = http.responseText;
                      }
                  }    
                http.open("get","http://kf.ahens.com/home/users/mirrorFetchUpdate.php?thread="+thread+"&to="+target+"&from="+source,true);   
                http.send();
              }      
             }  */
           /* function updateMirror(from,to)
              {
               
               var httpx;
               if(window.XMLHttpRequest)
                 httpx = new XMLHttpRequest();
               else
                 httpx = new ActiveXObject();
               httpx.onreadystatechange = function()
                   {
                   
                     if(httpx.readyState==4 && httpx.status ==200)
                        {
                         document.getElementById("mirrorData").innerHTML += httpx.responseText;
                        }
                   }
                httpx.open("get","http://kf.ahens.com/home/users/mirrorFetchUpdate.php?s="+from+"&ts="+to, true);
                httpx.send();
              }    
			  */
       </SCRIPT> 
  </HEAD>

  <BODY class="kornContent" onload="startNotification(), updatenotif(), loginstatus()">
    <input type="hidden" id="threader" value="<?php echo $uid; ?>" />
     <input type="hidden" id="target" value="<?php echo $useridT; ?>" />       
    <DIV class="mainContainer" style="font-family:tahoma;font-size:13px;text-align:left;width:500px;padding-left:194px;padding-right:196px;">   
    <DIV style="text-align:center;font-size:34px;border-style:solid;border-width:0px 0px 1px 0px;border-color:#aaaaaa;height:50px;"><?php echo $username; ?></DIV>
    <br />
    <DIV style="background-color:#ddeeff;font-family:verdana;font-size:15px;width:500px;height:25px;text-align:center;">&nbsp;Your mirror reflects here</DIV> 
    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
   <DIV style="font-family:verdana;font-size:15px;font-style:italic;color:#ff6050;">Most Recent Post </DIV><br />
    <DIV id="mirrorData">
    <?php
        $countPost=0;
        $getPost = mysql_query("SELECT * FROM mirror WHERE target = '$uid' ORDER BY threadID DESC",$con);
        while($showPost = mysql_fetch_array($getPost))
            {
             $threadID =$showPost['threadID'];
             
             $getSourceName = mysql_query("SELECT userName,userID FROM corn_users WHERE userID = '$showPost[threadSource]'",$con);
             $nameS = mysql_fetch_row($getSourceName);
			 $link_pattern = '/\b(http:\/\/)*(https:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.*[^\s][^\s]+/';
              preg_match($link_pattern, $showPost['thread'], $matches);
		       $replace="<a href='$matches[0]'>$matches[0]</a>";
              $mod_threads=str_ireplace($matches[0], $replace, $showPost['thread']); 
			  ?><div style="border:solid 1px #ddeeff;padding:4px;"> <?php
             echo "<a id='proGoLink' href='users/showProfile.php?userID=".$nameS[1]."'>".$nameS[0]. " </a>says <br /><br /><span style='font-size:14px;font-weight:normal;'>".nl2br($mod_threads)."</span><br /><br /><span style='font-size:11px;font-weight:normal;'>".$showPost['time']."</span>";
              ?><br /><span style="cursor:pointer;color:#998070;font-size:11px;" onclick="location.href='mymirror.php?deleteThread=<?php echo $threadID ;?>';">Delete</span>
               | <span style="cursor:pointer;color:#998070;font-size:11px;" onclick="location.href='users/peerMirror.php?userid=<?php echo $nameS[1];?>';">Write on <?php echo $nameS[0];?>'s mirror</span></DIV>
              <?php
             $count++;
             break;
            }
            if($count==0) echo "<DIV style='background-color:#ffcccc;border:solid 1px #FF9090;'>No Posts to show!</div>"; 
         if($delete==1)
            echo "<br /><DIV style='background-color:#ffbbbb; border:solid 1px #ff8080;'>Thread Deleted Succesfully</DIV>";
           else if($delete==0)
             echo "<DIV style='background-color:#ffbbbb; border:solid 1px #ff8080;'>Some error ocurred at ahens server. Please try later!</DIV>";  
         ?><br /><br />
         <DIV style="font-family:verdana;font-size:15px;font-style:italic;color:#669866;">Older Posts</DIV><br />
         <?php
         $count1=0;
         while($showPost =mysql_fetch_array($getPost))
            {
               $getSourceName = mysql_query("SELECT userName,userID FROM corn_users WHERE userID = '$showPost[threadSource]'",$con);
             $nameS = mysql_fetch_row($getSourceName);
             $threadID = $showPost['threadID'];
			  $link_pattern = '/\b(http:\/\/)*(https:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.*[^\s][^\s]+/';
              preg_match($link_pattern, $showPost['thread'], $matches);
		       $replace="<a href='$matches[0]'>$matches[0]</a>";
              $mod_threads=str_ireplace($matches[0], $replace, $showPost['thread']); 
			  ?><div style="border:solid 1px #ddeeff;padding:4px;"><?php
             echo "<a id='proGoLink' href='users/showProfile.php?userID=".$nameS[1]."'>".$nameS[0]. " </a> says <br /><br /><span style='font-size:14px;font-weight:normal;'>".nl2br($mod_threads)."</span><br /><br /><span style='font-size:11px;font-weight:normal;'>".$showPost['time']."</span>";
            ?> <br /><span style="cursor:pointer;color:#998070;font-size:11px;" onclick="location.href='mymirror.php?deleteThread=<?php echo $threadID ;?>';">Delete</span>
              | <span style="cursor:pointer;color:#998070;font-size:11px;" onclick="location.href='users/peerMirror.php?userid=<?php echo $nameS[1];?>';">Write on <?php echo $nameS[0];?>'s mirror</span></DIV>
              <?php
             $count1++;
            }
            $threadNo=$count+$count1;
         if($count1==0) echo "<DIV style='background-color:#ffcccc;border:solid 1px #FF9090;'>No old posts on your mirror!</DIV>";      
         else 
           echo "<DIV style='color:green;font-weight:bold;'>Total ".$threadNo." threads returned.</DIV>";
                  
                
     ?>      
    </DIV><br /><br />
    <DIV style="background-color:#eeeeee;border-style:solid;border-width:7px 0px 3px 0px;border-color:#ccccff;font-family:verdana;">
     Mirror is place where people write about people. You can search any profile in your network and write on their mirror. At your own mirror, you will
     see all such posts written about you, by other people in your network. You can review a person on any basis. For example, his behaviour, his intelligence,
     his attitude, abilities, nature or you can discuss any issue which was difficult to do in face.</div>
     <br /><hr /><br />
		 
			
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
  
    <!--       <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='profile.php';"> My Profile </span>
		<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')"/></span>					
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  
                  </DIV>
            </DIV> -->
	    
	
	<DIV class="midPlate" style="top:235px;">
	  <span style="position:absolute;left:430px;font-family:tahoma;font-size: 26px;font-style:bold;">
	  <?php 
	        $brow = mysql_query("SELECT * FROM campusdata");
			while($r = mysql_fetch_array($brow))
			  {
				if($r['campusCode']==$campCode)
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
	<DIV id="searchbox" style="text-align:center;"></DIV>
	<!--<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	 <FORM action="" method="GET">
	  <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/settings.php';">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/theahensbuilders.php';">The Ahens Builders</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/help.php';">Help</DIV>
	      <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out
	    </button>
		</FORM>
 </DIV> -->
  <DIV class="glassSignup2">
	<DIV class="sideLink2" id="sideLnk1" onmouseover="sidelinkover('sideLnk1')" onmouseout="sidelinkout('sideLnk1','Common Room')" onclick="location.href='users/commonroom.php';">
	Common Room
	</DIV>
    <DIV class="sideLink2" id="sideLnk2">
	<Form action="index.php" method ="get"><button id="ed" type="submit" name="editPro" style="font-size:15px;font-family:'tahoma';cursor:pointer;border-style:none;background-color:white;color:rgb(120,120,120);" onmouseover="sidelinkover('ed')" onmouseout="sidelinkout('ed', 'Edit Profile')">Edit Profile</button></form>
	</DIV>
    <DIV class="sideLink2" id="sideLnk3" onclick="location.href='mymirror.php';">
	Mirror &gt;
	</DIV>
	<DIV class="sideLink2" id="sideLnk4"  onmouseover="sidelinkover('sideLnk4')" onmouseout="sidelinkout('sideLnk4','Flakes&trade;')" onclick="location.href='flakes.php';">
	Flakes&trade;
	</DIV> 
	<DIV class="sideLink2" id="sideLnk5"  onmouseover="sidelinkover('sideLnk5')" onmouseout="sidelinkout('sideLnk5','Stone&trade;')" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade;
	</DIV>
	<DIV style="font-weight:bold;" class="sideLink2" id="sideLnk6"  onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 <span style="font-size:15px;"> Apps that will spice up the way you connect with people and ideas. Soon at your service.</span>

  
 
	
	</DIV>
	<DIV class="cover" id="cvr"></DIV>  
	 <DIV id="notif"></DIV>
	 	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
   
    </BODY>
   </HTML>
	 
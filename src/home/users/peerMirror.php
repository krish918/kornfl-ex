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
            
       else
  		
      
       $get = mysql_query("SELECT * FROM profiles WHERE userID = '$_GET[userid]'",$con);
       $getS = mysql_fetch_array($get);
       if(!$getS)
             {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}	     
       $useridT = $getS['userID'];
       $usernameT = $getS['fullName']; 
       $firstname = explode(" ",$usernameT);
	   $campCodeT = $getS['campusCode'];
       if($uid ==$useridT)
           {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}	
        $delete=2; 
      
      	if($_GET['threadid']!='')
      	  {
      	         if(mysql_query("DELETE FROM mirror WHERE threadID = '$_GET[threadid]'",$con))
                $delete=1;
              else $delete=0;  
          }    
	   if($uid!=56)		  
	   mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);	  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesMirror" id="peerMirrorShowKornflakes" lang="en">
  <HEAD>
      <TITLE> <?php echo $usernameT;?>'s mirror</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
	  
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
       <SCRIPT type="text/javascript">
          function submitThread()
            {
            
             var source= document.getElementById("threader").value;
             var target = document.getElementById("target").value;
             var thread = document.getElementById("thread").value;
			 var t =document.getElementById("thread").innerHTML;
			 thread = escape(thread);
             if(thread=='' || t=="Maximum 320 Characters")
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
            
               document.getElementById("loader").innerHTML="<img src='http://kf.ahens.com/images/ajax-loader.gif' alt='loading' />";
   
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
                http.open("get","mirrorFetchUpdate.php?thread="+thread+"&to="+target+"&from="+source,true);   
                http.send();
              }      
             }
            function updateMirror(from,to)
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
                httpx.open("get","mirrorFetchUpdate.php?source="+from+"&target="+to, true);
                httpx.send();
              } 
        
       </SCRIPT>
  </HEAD>
  <BODY class="kornContent" onload="updateMirror('<?php echo $uid ?>','<?php echo $useridT; ?>'), startNotification(), updatenotif(), loginstatus()">
     <input type="hidden" id="threader" value="<?php echo $uid; ?>" />
     <input type="hidden" id="target" value="<?php echo $useridT; ?>" />       
    <DIV class="mainContainer" style="font-family:tahoma;font-size:13px;text-align:left;width:500px;padding-left:194px;padding-right:196px;">   
    <DIV style="text-align:center;font-size:34px;border-style:solid;border-width:0px 0px 2px 0px;border-color:#aaaaaa;height:50px;"><?php echo $firstname[0]; ?>'s Mirror</DIV>
    <br />
   
    <DIV style="font-family:tahoma; color:#775050; font-size:25px;">Mirror is just a reflection of your heart</DIV>
    <br />
    <DIV id="mirrorThread">
    <textarea id="thread" name="thread" class="threadBox" onfocus="textAreaEffect()" onkeydown="checklength()">Maximum 320 Characters</textarea>
    <SCRIPT type="text/javascript">
     function textAreaEffect()
      {
        var get = document.getElementById("thread");
        
        if(get.innerHTML=="Maximum 320 Characters")
        {
          get.style.height="120px";
        get.style.color="black";
           get.innerHTML="";
         }
  
      } 
     function checklength()
       {
       if(document.getElementById("thread").value.length>320)
          {
            document.getElementById("msg").innerHTML = "You can't enter more than 320 words. It will be trucated!";
           }
            if(document.getElementById("thread").value.length<320)
          {
            document.getElementById("msg").innerHTML = " ";
           }   
           }  
    </SCRIPT><br /><br />
    <DIV style="text-align:right;">
    <button type="button" name="submit" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onclick="submitThread()">POST</button>
    
    </DIV>
    </DIV>
    <br /><br />
    <DIV id="loader" style="position:absolute;top:266px;left:550px;"></DIV>
    <br /><br /><br /><br /><br /><br /><br /><br /><br />
    <DIV id="msg" style="position:absolute;top:270px;left:196px;background-color:#ffdddd;border: solid 1px #ff9090; font-size:13px;"></DIV><br />
    <span style="color:#bbbbbb;">Only Your Posts will be visible here,as this mirror is not public.</span><br /> 
    <?php
       if($delete==1)
            echo "<br /><DIV style='background-color:#ffbbbb; border:solid 1px #ff8080;'>Thread Deleted Succesfully</DIV>";
           else if($delete==0)
             echo "<DIV style='background-color:#ffbbbb; border:solid 1px #ff8080;'>Some error ocurred at ahens server. Please try later!</DIV>";  
       if($_GET['delete']==1)
                 echo "<br /><DIV style='background-color:#ffbbbb; border:solid 1px #ff8080;'>Thread Deleted Succesfully</DIV>";

       ?> 
      
    <DIV id="mirrorData"></DIV><br /><br /><hr /><br />
     <DIV class="mirrorHint" id="mirrorHint">
    Mirror is the personal space of any user on Kornflakes&trade; beta. Mirror is by default hidden from rest of the network. But, it can be reflected back to the network 
    by the user. <br /><br />
    At a particular Mirror, users from network post honest and just reviews about the owner of that Mirror. It could be personal, social, academic and emotional. 
    Mirror could be very sensitive place, most of the times. So, please keep your ethics and be decent.<br />
    <br /><span style="font-weight:bold;">Caution:</span> Use of abusive language, at mirror may lead to permanently deletion of Kornflakes&trade; Beta account from the campus.
    </DIV>
    <br /><hr /><br />
		<span style="font-size:12px;color:#aaaaaa;"> ahens, right now, is in beta version. Release candidate is expected soon.</span><br /><br />
		<!--	<DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV> -->
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
   
      <!--     <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='../search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='../';"> My Profile </span>
	<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')"/></span>						
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  
                  </DIV>
            </DIV> -->
	    <DIV id="searchbox" style="text-align:center;"></DIV>
	
	<DIV class="midPlate" style="top:430px;">
	  <span style="position:absolute;left:430px;font-family:tahoma;font-size: 26px;font-style:bold;">
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
<!--	<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	 <FORM action="" method="GET">
	   <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/settings.php';">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/theahensbuilders.php';">The Ahens Builders</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/help.php';">Help</DIV>
	   <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out
	    </button>
		</FORM>
	 </DIV> -->
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
	$firstname = explode(" ",$usernameT); 
	?>
	<DIV class="sideLink2" id="sideLnk6" onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','<?php echo $firstname[0]; ?>\'s Profile')" onclick="location.href='showProfile.php?userID=<?php echo $useridT; ?>';">
	<?php echo $firstname[0]; ?>'s Profile
	</DIV>
	<DIV class="sideLink2" id="sideLnk7" onmouseover="sidelinkover('sideLnk7')" onmouseout="sidelinkout('sideLnk7','<?php echo $firstname[0]; ?>\'s Flakes')" onclick="location.href='peerflakes.php?userid=<?php echo $useridT; ?>';">
	<?php echo $firstname[0]; ?>'s Flakes
	</DIV>
	<?php } ?>
	  <DIV style="font-weight:bold;" class="sideLink2" id="sideLnk8"  onmouseover="sidelinkover('sideLnk8')" onmouseout="sidelinkout('sideLnk8','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 

  
 
    </DIV>

	
    
    
	<DIV class="cover" id="cvr"></DIV>
    <DIV id="notif"></DIV>	
<DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
 	
   </BODY>
   </HTML>
	 
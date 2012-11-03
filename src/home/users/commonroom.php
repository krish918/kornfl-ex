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
	if($uid!=56)	
	 mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesRoom" id="commonShowKornflakes" lang="en">
  <HEAD>
      <TITLE>Common Room</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
	   <STYLE type ="text/css">
	      #loadm
		    {
			 text-align:center;
			 font-size:13px;
			 background-color:#cccddf;
			 border:solid 1px #9090ff;
			 cursor:pointer;
			 font-family:'lucida sans unicode',verdana;
			 padding:3px;
			 }
		#loadm:hover
             {
             background-color: #9090ff;
             }			 
	   </STYLE>
  </HEAD>
  <BODY class="kornContent" onload="getflakes(0,15), startNotification(), updatenotif(),loginstatus()"> 

    <DIV class="mainContainer" style="font-family:tahoma;font-size:13px;text-align:left;width:500px;padding-left:194px;padding-right:196px;">
    <br /> <DIV style="font-size:30px;">Common Room</DIV>
    <br /><br />
    <DIV style="font-size:12px;color:#808080;">FLAKES&trade; running through the network will appear here. <br />You can search Flakes by a particular user or Flakes on a particular topic right here.</DIV>
    <br /><br />
    <DIV style="text-align:center;font-family:'lucida sans unicode',tahoma;font-size:14px;">
      SEARCH FLAKES&trade; BY NAME/TOPICS<br /><br />
    <INPUT type="text" size="50" id="sFlakes" style="text-align:center;" onkeyup="searchflakes(this.value)"/>   </DIV>
    <br /><hr />
    <DIV id="msg" style="font-family:verdana;font-style:italic;">Showing all flakes by all users</DIV>
    <br />
    <SCRIPT type="text/javascript">
          function getflakes(a,b)
          {
            var http;
            if(window.XMLHttpRequest)
              http = new XMLHttpRequest();
            else
               http = new ActiveXObject("Msxml2.XMLHTTP");
             document.getElementById("loader").innerHTML = "<img src='http://kf.ahens.com/images/ajax-loader.gif' alt='loading' border=0 />";  
             http.onreadystatechange = function()
               {
                 if(http.readyState==4 && http.status==200)
                    {
					  document.getElementById("loadMore").innerHTML = "<img src='http://kf.ahens.com/images/ajax-loader.gif' alt='loading' border=0 />";  
                      document.getElementById("flakesB").innerHTML +=http.responseText;
                      document.getElementById("loader").innerHTML = "";
					  document.getElementById("loadMore").innerHTML = "<DIV id='loadm' onclick='getflakes("+(a+b)+",15)'> More Flakes </DIV>";
					  
                     }
                 }
              http.open("get","bringflakescommon.php?limit_a="+a+"&limit_b="+b, true);
              http.send(); 
                 
                          
          }
         function searchflakes(string)
            {
            if(string!='')
              {
			  
               var httpa;
            if(window.XMLHttpRequest)
              httpa = new XMLHttpRequest();
            else
               httpa = new ActiveXObject("Msxml2.XMLHTTP");
             document.getElementById("loader").innerHTML = "<img src='http://kf.ahens.com/images/ajax-loader.gif' alt='loading' border=0 />";  
             httpa.onreadystatechange = function()
               {
                 if(httpa.readyState==4 && httpa.status==200)
                    {
					  document.getElementById("loadMore").innerHTML = "";
                      document.getElementById("msg").innerHTML ="Showing search results";
                      document.getElementById("flakesB").innerHTML =httpa.responseText;
                      document.getElementById("loader").innerHTML = "";
                      document.getElementById("showall").innerHTML="<a style='text-decoration:none;' href='commonroom.php'>Show All</a>";
                     }
                 }	 
              httpa.open("get","searchflakes.php?query="+string, true);
              httpa.send(); 
             }
            }  
             

    </SCRIPT>      
     <Div style="position:absolute;left:600px;top:30px;" id="showall"></DIV>     
     <DIV id="flakesB"></DIV><DIV id="loadMore"> </DIV><DIV id="loader" style="text-align:center;"></DIV><br />	<hr /><br />
     <!--span style="font-size:12px;color:#aaaaaa;"> Kornflakes&trade; right now is in beta version. Release candidate is expected soon.</span>--><br /><br />	
			<!--<DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV>-->
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
   
   <!--        <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='../search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='../';"> My Profile </span>
<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')"/></span>				
			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  
                  </DIV>
            </DIV> -->
    <DIV id="searchbox" style="text-align:center;"></DIV>
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
	<DIV class="sideLink2" id="sideLnk1" onclick="location.href='commonroom.php';">
	Common Room &gt;
	</DIV>
    <DIV class="sideLink2" id="sideLnk2">
	<Form action="../index.php" method ="get"><button id="ed" type="submit" name="editPro" style="font-size:15px;font-family:'tahoma';cursor:pointer;border-style:none;background-color:white;color:rgb(120,120,120);" onmouseover="sidelinkover('ed')" onmouseout="sidelinkout('ed', 'Edit Profile')">Edit Profile</button></form>
	</DIV>
    <DIV class="sideLink2" id="sideLnk3" onmouseover="sidelinkover('sideLnk3')" onmouseout="sidelinkout('sideLnk3','Mirror')" onclick="location.href='../mymirror.php';">
	Mirror
	</DIV>
	<DIV class="sideLink2" id="sideLnk4"  onmouseover="sidelinkover('sideLnk4')" onmouseout="sidelinkout('sideLnk4','Flakes&trade;')" onclick="location.href='../flakes.php';">
	Flakes&trade;
	</DIV> 
	<DIV class="sideLink2" id="sideLnk5"  onmouseover="sidelinkover('sideLnk5')" onmouseout="sidelinkout('sideLnk5','Stone&trade;')" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade;
	</DIV>
        <DIV style="font-weight:bold;" class="sideLink2" id="sideLnk6"  onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 

  
 
    </DIV> 

	
    </DIV>
	<DIV class="cover" id="cvr"></DIV>
		 <DIV class="dialogueBox" id="dbox">
	 <DIV id="upBar">How to rate</DIV><br />
	 <br />
	 <button type= "button" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hide()">OK</button>
	 </DIV> 
    <DIV id="notif"></DIV>	
	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,20)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>	
   </BODY>
   </HTML>
	 
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
      mysql_select_db("<db-name>",$con);
      session_start();
      $username = '';
	  $indtime = time() + 45000;
	  $time = date("F j, Y | g:i a",$indtime);
      $query = mysql_query("SELECT * FROM corn_users");
      while($row = mysql_fetch_array($query))
       {
          if(($row['Email']==$_SESSION['user'])||($row['Email']==$_COOKIE['user']))
             {
             	$uid = $row['userID'];
               $username = $row['userName'];
			   if($row['campusJoined'] == '')
                       {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}	
				else if($_POST['camName']!='')
				   $campCode =$_POST['camName'];
				  else
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesSearch" id="profileIndex" lang="en">
  <HEAD>
      <TITLE> <?php echo $username;?>: User Profile Index</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
  </HEAD>
  <BODY class="kornContent" onload="startNotification(), updatenotif(),loginstatus()">
  
    <DIV class="mainContainer">
	    
		<span style="font-family:'lucida sans unicode';font-size:20px;color:purple;"><br />NETWORK PROFILE INDEX</span><br /><br />
		    <form>
			<input type ="text" size="70" class="simple" id="search" style="font-family:'lucida sans unicode';height:30px;text-align:center;color:#666666;" onkeyup="loadSearch('<?php echo "$campCode"; ?>')" />
	            </form>
	    
	         <span style="position:absolute;top:63px;left:670px;" id="load"></span>   	
		<span style="font-family:tahoma;font-size:14px;">Enter the name of user you want to search and wait.</span><br /><br />
		
		<span id="showall" onclick="loadAll('<?php echo $campCode; ?>')">Show All</span><br /><br />
		
				 <DIV class="camplist"> <SELECT id="camplist" onchange="showcampS()" style="height:35px;font-family:verdana;"><OPTION> Showing results from your campus: Visit other campus </OPTION>
		                <?php $sql = mysql_query("SELECT campusName,campusCode, networkCount From campusdata WHERE campusCode <> '$campCode'",$con);
						      while($rowC = mysql_fetch_array($sql))
							    {
								 if($rowC['networkCount']!=0)
								 {
								  ?> <OPTION value="<?php echo $rowC['campusCode']; ?>" > <?php echo $rowC['campusName']; ?> [<?php echo $rowC['networkCount'] ; ?>]</OPTION><?php
								  }
								} 
							  ?></SELECT>
				 <FORM method="post" action="index.php" id="campForm">
          				 <INPUT type="hidden" id="inptCamp" name="camName" />
                 </FORM>						 
				<SCRIPT type="text/javascript">
				 function showcampS()
				   {
				     
				     var index = document.getElementById("camplist").selectedIndex;
					 var opt = document.getElementById("camplist").options[index].value;
					 
					 document.getElementById("inptCamp").value = opt;
					 document.getElementById("campForm").submit();
				    }	 
               				
				</SCRIPT>			  
		</DIV><br />
		<hr /><br /><br />
		<br /><br /><br /><br /><br />
		<br />
		<DIV class="midContainer" id="searchResult">
		 <div style="font-family:verdana;font-style:italic;text-align:left;font-size:16px;color:#cc8080;">Most Rated Profiles</div> 
		 <DIV class="mostRated">
		  <?php
		     $flagR=0;$flagP=0;
		     $sql= mysql_query("SELECT raters FROM profiles WHERE (campusCode = '$campCode') AND (projected =1) AND (raters > 0) LIMIT 0,5",$con);
			  while($getR = mysql_fetch_array($sql))
			    {
			         $flagR++;
				 }	
              $sql= mysql_query("SELECT peopleInterested FROM profiles WHERE (campusCode = '$campCode') AND (projected =1) AND (peopleInterested > 0) LIMIT 0,5",$con);
			  while($getR = mysql_fetch_array($sql))
				  {
                     $flagP++;
				  }
				
		  ?><TABLE style="font-size:14px;text-align:left;"><?php
			  $countP=0;
			if($flagR>2)
            {	
             $sql2=mysql_query("SELECT * FROM profiles WHERE campusCode = '$campCode' AND projected = 1 ORDER BY raters DESC",$con);			
			    
				While($get2 = mysql_fetch_array($sql2))
				{  
				if($get2['raters']!=0)
				 {
			           ?><TR height="25"><TD width="250"><a id="smallprolink" href="../users/showProfile.php?userID=<?php echo $get2['userID']; ?>"><?php echo $get2['fullName']; ?></a></TD><TD width="200"> <span style="color:#cc90cc;"><?php echo $get2['course']." ".$get2['year']; ?></span></TD><TD width="50"><?php echo $get2['raters']; ?><span style='font-size:10px;'>people  </span></TD></TR>
			           <?php
					    $countP++;
			            if($countP==$flagR)
			            break;
				  }		
			     }
			  ?> </TABLE>  <?php	 
			}
			
			 else
			   { ?> </TABLE>  <?php
					 ?> <span style='font-family:tahoma;color:#aa8080;'><br />Not enough profiles yet </span><br /><br /> <?php }	
		  ?>
		    
		  </DIV>
         <br /><br />
		 <div style="font-family:verdana;font-style:italic;text-align:left;font-size:16px;color:#cc8080;">Top Profiles</div>
       	 <DIV class="mostRated">
			  <TABLE style="font-size:14px;text-align:left;"><?php
			  $resCount=0;  
			 for($m =1; $m<=5;$m++) 
			 {  
			  $sql2=mysql_query("SELECT * FROM profiles WHERE campusCode = '$campCode' AND projected =1 ",$con);
			  while($get2 = mysql_fetch_array($sql2))
			  {
				  if($get2['rank']==$m)
				  {
			           ?><tr height="25"><td width="250"><a id="smallprolink" href="../users/showProfile.php?userID=<?php echo $get2['userID']; ?>"><?php echo $get2['fullName']; ?></a></td><td width="200"> <span style="color:#bb90bb;"><?php echo $get2['course']." ".$get2['year']; ?></span>  </td><td width="50"><span style='font-size:10px;'>rank </span> <?php echo $get2['rank']; ?> </td></tr>
			           <?php $resCount++; 
			      }
				} 
			 
			 }	 ?> </TABLE> <?php
			if($resCount==0) { ?> <span style='font-family:tahoma;color:#aa8080;'><br />Not enough profiles yet </span><br /><br /> <?php }	
		  ?>
		 </DIV> <br /><br />
 <div style="font-family:verdana;font-style:italic;text-align:left;font-size:16px;color:#cc8080;">Most Interesting People</div>
 <DIV class="mostRated">
   <TABLE style="font-size:14px;text-align:left;">
			  
 <?php
 $countP=0;
 if($flagP>2)
            {	
             $sql2=mysql_query("SELECT * FROM profiles WHERE (campusCode = '$campCode') AND (projected = 1) AND (peopleInterested > 0)ORDER BY peopleInterested DESC, fullName",$con);			
			    
				While($get2 = mysql_fetch_array($sql2))
				{  
			
			           ?><TR height="25"><TD width="250"><a id="smallprolink" href="../users/showProfile.php?userID=<?php echo $get2['userID']; ?>"><?php echo $get2['fullName']; ?></a></TD><TD width="200"> <span style="color:#cc90cc;"><?php echo $get2['course']." ".$get2['year']; ?></span></TD><TD width="50"><?php echo $get2['peopleInterested']; ?><span style='font-size:10px;'> people</span></TD></TR>
			           <?php 
					    $countP++;
			            if($countP==$flagP)
			            break;		
			     }
			  ?> </TABLE>  <?php	 
			}
			
			 else
			   { ?> </TABLE>  <?php
					 ?> <span style='font-family:tahoma;color:#aa8080;'><br />Not enough profiles yet </span><br /><br /> <?php }	
		  ?>
 </DIV>
		 
		</DIV><br />

    
<br />
		 
					  
           <br /><hr /><br /><br />
           <DIV style="border: solid #ccccff;border-width:1px 0px 1px 0px; background-color:#eeeeff;font-family:tahoma;font-size:13px;"> Only those profiles will be enlisted
              here which have been projected by the user. <br />The profile listed here are only from your campus.</div><br /><br />
			  
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
				<span class="upperLink" style="left:80px;background-color:#dedeff;" onclick="location.href='';">Search Network</span>
				<span class="upperLink" style="left:235px;" onclick="location.href='../';">My Profile</span>
			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  </DIV>
            </DIV> -->
	
<!--	<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	 <FORM action="index.php" method="GET">
	    <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/settings.php';">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/theahensbuilders.php';">The Ahens Builders</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='../ahens/help.php';">Help</DIV>
	  <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out
	    </button>
		</FORM>
	 </DIV> -->
	<DIV class="midPlate" style="top:397px;">
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
		<div class="decklink" style="font-weight:bold;" onclick="location.href='http://kf.ahens.com/home/search';">Search</div>
		<div class="decklink" style="left:270px;" onclick="location.href='http://kf.ahens.com/home/profile.php';">Profile</div>
		<!--<div style="position:absolute;top:5px;left:490px;">
		  <input type="text" value="Enter a name to search" id="qs" class="qsearchtextbox" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')" />
		 </div> -->
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
    <DIV class="sideLink2" id="sideLnk5"  onmouseover="sidelinkover('sideLnk5')" onmouseout="sidelinkout('sideLnk5','Stone&trade;')" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade;
	</DIV>
<DIV style="font-weight:bold;" class="sideLink2" id="sideLnk6"  onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 <span style="font-size:15px;"> Its all raining ideas. At AHENS, we never ever satisfy by just one or the first. </span>

  
 
    </DIV>
    
	<DIV class="cover" id="cvr"></DIV>
	 <DIV id="notif"></DIV>
	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
 
  </BODY>	
</HTML>
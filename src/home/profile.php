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
	$conStone=mysql_connect("127.0.0.1", "<db-username>", "<db-password>");
	if(!$con)
	  {
	header("location:http://kf.ahens.com/error.html");
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
    $time =date("F j, Y | g:i a", $indiantime);

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
	$uid = mysql_fetch_row(mysql_query("SELECT userID from corn_users WHERE Email = '$_SESSION[user]' OR Email='$_COOKIE[user]' ",$con));	
    if(isset($_GET['change']))
		{
		if($_GET['changedname']!='')
		{
		mysql_query("UPDATE corn_users SET userName= '$_GET[changedname]' WHERE userID = '$uid[0]'",$con);
		mysql_query("UPDATE profiles SET fullName = '$_GET[changedname]' WHERE userID = '$uid[0]'",$con);
		mysql_query("UPDATE stone SET name = '$_GET[changedname]' WHERE userID = '$uid[0]'",$conStone);
		}
		else $empty=1;
		}	
	$proq=mysql_query("SELECT * FROM corn_users",$con);
	
	while($bring = mysql_fetch_array($proq))
	{
	  if(($bring['Email'] == $_SESSION['user'])||($bring['Email']==$_COOKIE['user']))
		{
			$username = $bring['userName'];
			$proSavStat = $bring['profileSaved'];
			$id = $bring['userID'];
			 if($bring['campusJoined']=='')
                       {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}	
				else $campCode = $bring['campusJoined'];
			$projStat = $bring['profileProjected'];
			
		}
	}
    if($id!=56)	
    mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid[0]','$_SERVER[REQUEST_URI]','$time')",$con);
	if((isset($_POST['save']))||(isset($_POST['project']))||(isset($_POST['update'])))
	{
	 $homeTownS = $_POST['town'];
	 $courseS = $_POST['course'];
	 $subjectS=$_POST['subject'];
	 $lastinstS = $_POST['last'];
	 $areaS = $_POST['intrst'];
	 $exprtS =$_POST['exprt'];
	 $favpS= $_POST['favPlace'];
	 $statS = $_POST['stat'];
	 $learnS = $_POST['learning'];
	 $readS =$_POST['reading'];
	 $qS = array($_POST['q1'],
					$_POST['q2'],
					$_POST['q3'],
					$_POST['q4'],
					$_POST['q5']);
	$t = array($_POST['t1'], $_POST['t2'], $_POST['t3'], $_POST['t4'], $_POST['t5']);				
	if($proSavStat==0)
	  {
		$insertPro="INSERT INTO profiles (userID,fullName,email,profileType,campusCode,homeTown,course,subject,year,dobDay,dobMonth,dobYear,lastInstitute,areaInterest,fieldExpertise,favoritePlace, profileStatement,currentLearning,currentReading,q1,q2,q3,q4,q5) VALUES
					('$id', '$username', '$_SESSION[user]', '$_POST[type]', '$campCode','".$homeTownS."', '$_POST[course]', '".$subjectS."', '$_POST[courseYear]', '$_POST[day]','$_POST[month]', '$_POST[year]', '".$lastinstS."','".$areaS."','".$exprtS."','".$favpS."','".$statS."','".$learnS."','".$readS."','".$qS[0]."','".$qS[1]."','".$qS[2]."','".$qS[3]."','".$qS[4]."')";
	    if(mysql_query($insertPro, $con))
		  {
			mysql_query("UPDATE corn_users SET profileSaved = 1 WHERE userID = '$id'",$con);
			$k=1;
			while($k<=5)
			{
			  if($qS[$k]!='')
			  mysql_query("UPDATE kteasers SET totalAttempt = totalAttempt+1 WHERE teaserID='$t[$k]'",$con);
			  $k++;
			} 
		  }
		else echo "Some error ocurred at ahens server! We will soon fix it out.";
	  }
	 else
		{
		  $updatePro="UPDATE profiles SET profileType = '$_POST[type]', homeTown ='".$homeTownS."',course = '$_POST[course]', subject = '".$subjectS."', year = '$_POST[courseYear]', dobDay ='$_POST[day]',dobMonth='$_POST[month]',dobYear='$_POST[year]', lastInstitute='".$lastinstS."',areaInterest='".$areaS."',fieldExpertise='".$exprtS."',favoritePlace='".$favpS."',profileStatement='".$statS."',currentLearning='".$learnS."', currentReading='".$readS."', q1='".$qS[0]."', q2='".$qS[1]."', q3='".$qS[2]."', q4='".$qS[3]."',q5='".$qS[4]."' WHERE  userID = '$id' ";
		  $getq=mysql_fetch_row(mysql_query("SELECT q1, q2, q3, q4, q5, profileID FROM profiles WHERE userID ='$id'",$con));
		  if(mysql_query($updatePro,$con)) 
		  {
     	  $l=0;
		  while($l<5)
		   {
		  if($getq[$l]=='')
			{
			if($qS[$l]!='')
			  mysql_query("UPDATE kteasers SET totalAttempt = totalAttempt+1 WHERE teaserID='$t[$l]'",$con);
			}
		  $l++;	
		   }
		  }
		  else echo "Some error ocurred at ahens server! We will soon fix it out.";
		 mysql_query("INSERT INTO notification (profileID, time) VALUES ('$getq[5]', '$time')",$con); //notification sidebar
			
			
		}
	}
	if(isset($_GET['unproj']))
	     {
		   if($projStat == 1)
		       mysql_query("UPDATE campusdata SET projectionCount = projectionCount-1 WHERE campusCode = '$campCode'",$con);
			mysql_query("UPDATE corn_users SET profileProjected = 0 WHERE Email = '$_SESSION[user]'",$con);
			mysql_query("UPDATE profiles SET projected = 0 WHERE email = '$_SESSION[user]'",$con);
		 }
	if((isset($_POST['project']))||(isset($_GET['proj'])))
		{
			if($projStat == 0)
			  mysql_query("UPDATE campusdata SET projectionCount = projectionCount+1 WHERE campusCode = '$campCode'",$con);
			mysql_query("UPDATE corn_users SET profileProjected = 1 WHERE Email = '$_SESSION[user]'",$con);
			mysql_query("UPDATE profiles SET projected = 1 WHERE email = '$_SESSION[user]'",$con);
		}	 
	$qry = mysql_query("SELECT * FROM corn_users",$con);
	while($bring2 = mysql_fetch_array($qry))
	{
	  if(($bring2['Email'] == $_SESSION['user'])||($bring2['Email']==$_COOKIE['user']))
	    {
		   if($bring2['profileSaved']==0)
		       header("Location:index.php");
		}
	}
	
	 // information collection for live notification	
	if($proSavStat==1)
  {	
	$not=mysql_query("SELECT flakesID FROM flakes ORDER BY flakesID DESC", $con);
	$notify = mysql_fetch_array($not);
	$_SESSION['lastFlakes'] = $notify[0];	
	$not2=mysql_query("SELECT userID FROM corn_users WHERE campusJoined = '$campCode' ORDER BY userID DESC", $con);
	$notify2 = mysql_fetch_array($not2);
	$_SESSION['lastUser'] = $notify2[0];
	$_SESSION['camp'] = $campCode;
	$not3=mysql_query("SELECT rateID FROM ratinglist WHERE target = '$id' ORDER BY rateID DESC", $con);
	$notify3 = mysql_fetch_array($not3);
	$_SESSION['newRater'] = $notify3[0];
    $_SESSION['id'] = $id;	
	$stone = mysql_fetch_row(mysql_query("SELECT stoneID FROM stone ORDER BY stoneID DESC", $conStone));
	$_SESSION['stoneNotif'] = $stone[0];
	$read = mysql_fetch_row(mysql_query("SELECT dataID FROM readerdata ORDER BY dataID DESC", $conStone));
	$_SESSION['readNotif'] = $read[0];
	$comment = mysql_fetch_row(mysql_query("SELECT commentID FROM comments ORDER BY commentID DESC", $conStone));
	$_SESSION['comment'] = $comment[0];
  }	
     //information collection for live notification ends
	 
	 //counting stories published And stories read
      $countPub = mysql_num_rows(mysql_query("SELECT * FROM stone WHERE userID = '$id'",$conStone));
	  $countRead =	mysql_num_rows(mysql_query("SELECT * FROM readerdata WHERE readerID = '$id' AND action='r'",$conStone));
	  $countVisit = mysql_num_rows(mysql_query("SELECT * FROM profile_visit WHERE target = '$id'",$con));
	 
    //name validation
	 $letter = str_split($username);
	  $surname = explode(" ",$username);
	  $namerror=0;$invchar=0;$firstletter=0;$capital=0;$sur=0;
	  $invcount = preg_match_all('/[^a-zA-Z\s]/', $username, $matches, PREG_SET_ORDER);
	if($invcount>0) {$namerror=1; $invchar=1;}
    if(preg_match('/[A-Z]/', $letter[0])==0) {$namerror =1; $firstletter =1; } 
	if(preg_match_all('/[A-Z]/', $username,$matches2)>3) {$namerror =1; $capital =1; }	
	if($surname[1]==null)  {$namerror =1; $sur =1;} 
	if(strlen($username)<4) { $namerror=1; $len= 1;}
?>

<!DOCTYPE html PUBLIC  "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesProfile" id="profile" lang="en">
<HEAD>
      <TITLE> <?php echo $username;?>'s Profile</TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
</HEAD>
<BODY class="kornContent" onload="startNotification(), updatenotif(), loginstatus()">
  

   
	
    <DIV class="mainContainer" style="font-family:tahoma;font-size:13px;text-align:left;width:500px;padding-left:194px;padding-right:196px;">
      
		<DIV class="sidebox"> <div style="font-size: 50px;font-family:verdana;"><?php echo $countVisit; ?></div> <DIV style="position:absolute; bottom:0px;left:0px;width:100px;background-color:#aaaaff;padding:4px;">people visited your profile</DIV></DIV>
		<DIV class="sidebox" style="top:500px;" > <div style="font-size:50px;font-family:verdana;"><?php echo $countPub; ?></div> <DIV style="position:absolute; bottom:0px;left:0px;width:100px;padding:4px;background-color:#aaaaff;">stories published</DIV></DIV>
		<DIV class="sidebox" style="top:630px;"> <div style="font-size:50px;font-family:verdana;"><?php echo $countRead; ?></div> <DIV style="position:absolute; bottom:0px;left:0px;width:100px;padding:4px;background-color:#aaaaff;">stories read</DIV></DIV>
		
		<?php $rankG = mysql_fetch_row(mysql_query("SELECT rank FROM profiles WHERE userID = '$id'",$con)); ?>
		<DIV style="position:absolute; top:0px;left:16px;width:110px;height:82px;overflow:hidden;"><DIV class="rankBox">rank
		<div style="font-size:48px; text-align:right;font-family:tahoma;"> <?php echo $rankG[0]; ?></DIV>
		</DIV></DIV>
           
		<DIV id="head" style="font-family:tahoma;font-size:42px;text-align:center;">
         <?php echo $username;?>
		 <span style="position:absolute;color:#aa7070;font-size:15px;top:60px;left:194px;height:30px;width:500px;border-style:solid;border-width:0px 0px 1px 0px;border-color:black;">
		 <?php echo $_SESSION['user'];?></span>
		 </DIV>
		  <DIV class="DPbox" id="dpb" style="color:purple;cursor:auto;font-size:13px;"> 
		 <?php
		   $pic =mysql_query("SELECT * FROM pictures WHERE userID = '$id'",$con);
		   $getpic = mysql_fetch_array($pic);
		   if($getpic==NULL)
		 { ?>
		   <IMG src="http://kf.ahens.com/images/default_dp.jpg" width="140" height="180" alt="No Picture" />
		 <?php  }
		   else
		    { ?> <IMG src="<?php echo $getpic['picPath'].$getpic['picName']; ?>" width="140" height="180" />
			<?php }
			?>
		    
		 </DIV>

		 <DIV class="proDisp">
		 <?php
		 $brPro = mysql_query("SELECT * FROM profiles",$con); 
		 while($profile = mysql_fetch_array($brPro))
		 {
		  if($profile['email']!='')   // to tackle problem of profile overlapping
		  {
			if(($profile['email'] == $_SESSION['user'])||($profile['email']==$_COOKIE['user']))
			  {
			     $q = array ($profile['q1'],$profile['q2'],$profile['q3'],$profile['q4'],$profile['q5']);
			  ?> 
				  <div class="rateBox"> <?php echo $profile['averageRatings']; ?>
				  <div style="position:absolute; top:30px;right:-60px;font-family:tahoma;font-size:13px;font-style:italic;color:#101010;"> &lt;&lt; your<br />rating</DIV> 
				  </div>
					<div class="rateBox" style="left: 165px;">  <?php echo $profile['raters']; ?>
					<div style="position:absolute; top:30px;right:-65px;font-family:tahoma;font-size:13px;font-style:italic;color:#101010;"> &lt;&lt; people<br />rated you</DIV>
					</div>
						<div class="rateBox" style="left: 340px;"> <?php echo $profile['peopleInterested']; ?>
						<div style="position:absolute; top:30px;right:-95px;font-family:tahoma;font-size:13px;font-style:italic;color:#101010;"> &lt;&lt; people<br />interested in you</DIV>
						</div>  
				  <DIV class="separator" style="top:270px;"> <?php echo $profile['profileType']. ", "; ?>
					   <span style="font-size:15px;"> 
					     <?php echo $profile['course']." ".$profile['year']; ?> 
					   <br />
					   <?php if($profile['profileType']=='Student')
					           echo "Studying";  else echo "Teaching ";?><span style="font-size:15px;font-weight:bold;">
					    <?php echo $profile['subject'];?></span>
						<?php if(!$profile['homeTown']=='')
								   {?><span style="position:absolute;right:3px;font-size:14px; top:10px;">| From <span style="font-weight:bold;">
								   <?php echo $profile['homeTown'];}?></span></span></span>
				  </DIV></DIV>
				  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
					<?php
					       if(($profile['dobDay']!='Day')&&($profile['dobDay']!='')&&($profile['dobMonth']!='Month')&&($profile['dobMonth']!='')&&($profile['dobYear']!='Year')&&($profile['dobYear']!=''))
							   {
						        echo "<span style='font-weight:bold;'>Date Of Birth : </span>".$profile['dobDay']." ".$profile['dobMonth']." ".$profile['dobYear']."<br /><br />"; 
								echo "<span id='infoSep'></span><br />";
							   }	
						   if($profile['lastInstitute']!='')
						      {
								echo "<span style='font-weight:bold;'>Attended : </span>".$profile['lastInstitute']."<br /><br />";
								echo "<span id='infoSep'></span><br />";
							  }
						   if($profile['areaInterest']!='')
							  {
						        echo "<span style='font-weight:bold;'>Interested In : </span>". $profile['areaInterest']."<br /><br />";
								echo "<span id='infoSep'></span><br />";
							  }
						   if($profile['fieldExpertise']!='')
						      {
						        echo "<span style='font-weight:bold;'>Skills : </span>".$profile['fieldExpertise']."<br /><br />";
								echo "<span id='infoSep'></span><br />";
							  }
						   if($profile['currentLearning']!='')
						      {
						        echo "<span style='font-weight:bold;'>I am learning : </span>".$profile['currentLearning']."<br /><br />";
								echo "<span id='infoSep'></span><br />";
							  }
						   if($profile['currentReading']!='')
						      {
								echo "<span style='font-weight:bold;'>I am reading : </span>".$profile['currentReading']."<br /><br />";
								echo "<span id='infoSep'></span><br />";
							  }
						   if($profile['favoritePlace']!='')
						      {
								echo "<span style='font-weight:bold;'>Favorite place in campus : </span>".$profile['favoritePlace']."<br /><br />";
								echo "<span id='infoSep'></span><br />";
							  }
						   if($profile['profileStatement']!='')
						      {
                               				 echo "<span style='position:absolute;width:500px;color:#702070;top:90px;left:194px;text-align:center;font-family:georgia;font-size:15px;'>"."<span style='font-size:22px;'>&ldquo;</span>". $profile['profileStatement']."<span style='font-size:22px;'>&rdquo;</span></span>";
							 
							  }
					           else
					               {
					                 echo "<SPAN style='position:absolute;width:500px;color:#702070;top:110px;left:194px;text-align:center;font-family:georgia;font-size:15px;'>You are still without a tag!</SPAN>"	;	  
					               }  
					           ?><DIV class="quiz"><?php	  
							$i =0;
							$teas = mysql_query("SELECT teaser FROM kteasers WHERE active = 1",$con);
							while($getTeaser = mysql_fetch_row($teas))
							{	
								echo $getTeaser[0]; ?> : 
								<span style='font-weight:bold;'>
								<?php
								if($q[$i]!='')
								    echo $q[$i]; 
								else
								    echo "Not answered yet!";?> </span><br /><br />
								<span id='infoSep' style='border-color:#90bb90;'></span><br />
								
							<?php
							   $i++;
						    }
							/*else
								{
								echo "Newton's Third Law in one word : <span style='font-weight:bold;'>I tried a lot but failed.</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
						   if($profile['q2']!='')
								{
								echo "If time were money, watches would be : <span style='font-weight:bold;'>".$profile['q2']."</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
							else
								{
								echo "If time were money, watches would be : <span style='font-weight:bold;'>No Idea</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
							if($profile['q3']!='')
								{
								echo "If I wakeup to find world is ending next day, then what I'll do : <span style='font-weight:bold;'>".$profile['q3']."</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
							else
								{
								echo "If I wakeup to find world is ending next day, then what I'll do : <span style='font-weight:bold;'>Still Thinking</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							  }
						    if($profile['q4']!='')	
								{
								echo "One sentence to prove, I'm not dumb : <span style='font-weight:bold;'>".$profile['q4']."</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
							else
								{
								echo "One sentence to prove, I'm not dumb : <span style='font-weight:bold;'>I can't prove it</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
						    if($profile['q5']!='')
						    		{
						    		echo "If tears tasted like strawberries ,then : <span style='font-weight:bold;'>".$profile['q5']."</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
							else
								{
								echo "If tears tasted like strawberries, then : <span style='font-weight:bold;'>No idea then what?</span><br /><br />";
								echo "<span id='infoSep' style='border-color:#90bb90;'></span><br />";
							    }
								*/
					      ?></DIV><form action='profile.php' method='get'>
			       		     <?php
			       			if($profile['projected']==0)
				     		echo "<br /><div style='font-size:11px;color:#aaaaaa;text-align:center;'><button id='button' style='font-size:11px;width:75px;height:20px;' type='submit' name='proj'>Click Here</button> to project this profile. This profile is unprojected and is unavailable to network.</div><br />";
				      		 else
                     					echo "<br /><div style='font-size:11px;color:#aaaaaa;text-align:center;'><button id='button' style='font-size:11px;width:75px;height:20px;'type='submit' name='unproj'>Click here</button> to unproject this profile. This will make it unavailable to the network.</div><br />";				
				    		?></form><?php	      
						    echo "<div style='color:#bbbbbb;font-size:11px;text-align:center;'>The more information you provide in your profile, more easier it will be for people to assess you and better will be the rating for your profile.</div><br />";
							 
								
								
			    }
			 }	
			  
		   }
		 ?>
		 

	<!--	<DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV> -->
        <DIV id="end" style="position:absolute;bottom:-90px;left:250px;height:40px;">
	     <br />AHENS &copy; 2012
		 </DIV>
		  
	  </DIV>
	  
	</DIV>
        <div class="bottombar"> 
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
  <div class="deck">
		<div class="decklink" onclick="location.href='http://kf.ahens.com/home/search';">Search</div>
		<div class="decklink" style="left:270px;font-weight:bold;" onclick="location.href='http://kf.ahens.com/home/profile.php';">Profile</div>
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
       <!-- <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='search/';">Search Network</span>
				<span class="upperLink" style="left:160px;background-color:#dedeff;" onclick="location.href='profile.php';">My Profile</span>
				<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')" /></span>			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
	          </DIV>
        </DIV> -->
       
	<!--<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	<FORM action="" method="GET">
	   <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/settings.php';">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='http://kf.ahens.com/home/ahens/theahensbuilders.php';">The Ahens Builders</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/help.php';">Help</DIV>
	   <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out</button>
	   </FORM>
	 </DIV>  -->
	<DIV class="midPlate">
	<span style="position:absolute;left:430px;font-family:tahoma;font-size: 26px;font-style:bold;">
	<?php 
	        $brow = mysql_query("SELECT * FROM campusdata",$con);
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
      <DIV id="searchbox" style="text-align:center;"></DIV>
    <DIV class="glassSignup2">
	<DIV class="sideLink2" id="sideLnk1" onmouseover="sidelinkover('sideLnk1')" onmouseout="sidelinkout('sideLnk1','Common Room')" onclick="location.href='users/commonroom.php';">
	Common Room
	</DIV>
    <DIV class="sideLink2" id="sideLnk2">
	<Form action="index.php" method ="get"><button id="ed" type="submit" name="editPro" style="font-size:15px;font-family:'tahoma';cursor:pointer;border-style:none;background-color:white;color:rgb(120,120,120);" onmouseover="sidelinkover('ed')" onmouseout="sidelinkout('ed', 'Edit Profile')">Edit Profile</button></form>
	</DIV>
    <DIV class="sideLink2" id="sideLnk3" onmouseover="sidelinkover('sideLnk3')" onmouseout="sidelinkout('sideLnk3','Mirror')" onclick="location.href='mymirror.php';">
	Mirror
	</DIV>
	<DIV class="sideLink2" id="sideLnk4"  onmouseover="sidelinkover('sideLnk4')" onmouseout="sidelinkout('sideLnk4','Flakes&trade;')" onclick="location.href='flakes.php';">
	Flakes&trade;
	</DIV> 
	<DIV class="sideLink2" id="sideLnk5"  onmouseover="sidelinkover('sideLnk5')" onmouseout="sidelinkout('sideLnk5','Stone&trade;')" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade;
	</DIV><DIV style="font-weight:bold;" class="sideLink2" id="sideLnk6"  onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 <span style="font-size:15px;"> Be prepared for great new apps for you. Designed to ease much of your life. Soon to be launched at 
	 Appcetra.</span>
	
    </DIV> 
    
	<DIV class="cover" id="cvr"></DIV>
	<DIV id="chatbox" class="chatbox" onclick="onlinename()"> Chatbox [Test]</DIV> 
	  <DIV id="notiFrame2" style="top:270px;"> </DIV>
	 <DIV id="notif"> </DIV>
	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"> <Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
	  
	 <!-- <DIV id="note" class="notice"> New kTeasers are available. <br />Renew your profile. <br /><br ><span id="prof" style="color:blue;"onclick="document.getElementById('note').style.visibility='hidden';">Close</span>
		 		 <DIV style="position:absolute; top:50px;left:-30px;width:0;height:0;border-top:30px solid transparent;border-bottom:30px solid transparent; border-right:30px solid black;"></DIV>
			<DIV style="position:absolute; top:50px;left:-29px;width:0;height:0;border-top:30px solid transparent;border-bottom:30px solid transparent; border-right:30px solid white;"></DIV>
		  </DIV> -->
		 
	    <?php
	    if($namerror==1)
		  { ?>
		  <DIV class="cover" id="cvr" style="visibility:visible;"></DIV> 
		  <div style="position:absolute; top:20px; left:450px; width:500px;background-color:#FFEEEE; border:2px solid #F7D058;font-family:'trebuchet ms',arial;padding:14px;font-size:15px;box-shadow:0 0 30px 14px #ffffff;">
		     <div style="position:absolute; top:0px; left:0px; width:500px;padding:10px 14px 10px 14px;background-color:#FEF3D0;text-align:center;border-bottom:1px solid #ff9090;font-size:20px;font-weight:bold;">
                  AHENS CHECKPOINT
			  </div><br /><br />
			 <div style="font-family:arial;font-size:15px;">Please take a little pain for us, to allow ahens to serve you better. </div><br />
			<div style="background-color:#FEF3D0;padding:12px;border:1px solid #F2B906; border-radius:9px;-moz-border-radius:9px;-o-border-radius:9px;-webkit-border-radius:9px;-ms-border-radius:9px;"> 
           <div style="font-weight:bold;border-bottom:1px solid #aaaaaa;padding:5px;font-size:16px;">Dear <?php echo $username; ?>,</div><br />
            Our revised programmed system has rejected the name <span style="font-weight:bold;"> <?php echo $username; ?></span>, due to following reasons:
			<div style="font-family:tahoma;color:#ff4040;font-size:14px;">
				<ul type="square">
				<?php if($len==1) { ?>
				
			 <li style="border-bottom:1px solid #cccccc; padding:20px;"> Your name was too short to be accepted. Please elaborate it.</li>
				
				
				<?php } if($invchar==1) { ?>
			
			<li style="border-bottom:1px solid #cccccc; padding:20px;">  Your name contains <?php echo $invcount; ?> invalid characters. We still don't think that any genuine name can contain these characters. Some invalid characters are shown inside bracket - &lt; <?php echo $matches[0][0].$matches[1][0].$matches[2][0].$matches[3][0]; ?> &gt; </li>
			  <?php }
				     if($firstletter==1) { ?>
					
				<li style="border-bottom:1px solid #cccccc; padding:20px;"> First letter of your name is not capital. Any proper noun starts with capital letter. Remember school lessons!</li>
					 <?php }
					 if($capital ==1) { ?>
					<li style="border-bottom:1px solid #cccccc; padding:20px;"> 
					Your name contains too many capital letters. A name can at most contain only one capital character i.e. the first letter. But still we allow, three and only three, capital letters.</li>
				
					 <?php }
					if($sur ==1) { ?>
					<li style="border-bottom:1px solid #cccccc; padding:20px;">
				 <?php echo $username; ?>, you haven't provided us your surname. To identify you properly and uniquely we, kindly, need your surname. </li>
					
					<?php } ?> </ul></div>	
					<br />
				   <hr />
				   <br />
				   Kindly, validate your credential by entering a proper name here. We will be grateful to you for your cooperation.<br />
				    <br />
					<form action="" method="get">
					 <input type="text" style="height:30px; width:300px; font-family:verdana; font-size:17px;" name ="changedname" /><br />
					 <?php if($empty==1) echo "<br /><span style='color:red;'>Kindly enter the name!</span><br />"; ?><br />
					<button id="button" type="submit" name="change"> Change </button> 
					</form> 
					
				</div>
			</div>
			
			<?php } ?>
			
</BODY>
</HTML>
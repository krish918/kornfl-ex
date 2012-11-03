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
      $username = '';
	   $indiantime = time()+45000;
      $time =date("F j, Y | g:i a", $indiantime);
      $query = mysql_query("SELECT * FROM corn_users",$con);
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
     if(!isset($_GET['userID']))
            {header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}             
    $get = mysql_query("SELECT * FROM profiles WHERE userID = '$_GET[userID]' ",$con);
	  $getS = mysql_fetch_array($get);
    if($getS['projected']==0)
        {header("location:http://kf.ahens.com/error/noaccess/?usr_unavail=$getS[userID]"); exit;}           
	    $useridT = $getS['userID'];
		$campCodeT = $getS['campusCode'];
        $usernameT = $getS['fullName'];
        $statT = $getS['profileStatement'];
        $emailT = $getS['email'];
		$protypeT = $getS['profileType'];
		$townT = $getS['homeTown'];
		$courseT = $getS['course'];
		$subjectT = $getS['subject'];
		$yearT = $getS['year'];
		$dobT = $getS['dobDay']." ".$getS['dobMonth']." ".$getS['dobYear'];
		$lastT = $getS['lastInstitute'];
	    $areaT = $getS['areaInterest'];
		$fieldT = $getS['fieldExpertise'];
		$favPlaceT = $getS['favoritePlace'];
		$learnT = $getS['currentLearning'];
		$readT = $getS['currentReading'];
		$q1T = array($getS['q1'],
					$getS['q2'],
					$getS['q3'],
					$getS['q4'],
					$getS['q5']);
		$ratingsT = $getS['ratings'];
		$ratersT = $getS['raters'];
		$avgrateT = $getS['averageRatings'];
			
		
		
	    $cFalg=0;
		$checkrate=mysql_query("SELECT * FROM ratinglist", $con);
        while($crow = mysql_fetch_array($checkrate))
            {
                 if(($crow['rater']==$uid)&&($crow['target']==$useridT))			
					{$cFlag =1;
					$preRate = $crow['rating']; 
					break;
					}
		    }
		// checking for already availability in database 	
		$iFlag=0;
		$checkInt = mysql_fetch_row(mysql_query("SELECT * FROM ratinglist WHERE isource='$uid' AND interestedin = '$useridT'",$con));	
			
		 // intersted in updating code begins
		$intDone =2; 
		if(isset($_GET['interested']))
		{
		if($_GET['interested']==1)
		 {
		  if($checkInt==null)
		   {
			mysql_query("UPDATE profiles SET peopleInterested=peopleInterested+1 WHERE userID='$useridT'",$con);
			mysql_query("INSERT INTO ratinglist (isource, interestedin) VALUES ('$uid','$useridT')",$con);
			$intDone =1;
		    }	
		 }
		
		if($_GET['interested']==0)
		 {
		  if($checkInt!=null)
		   {
			mysql_query("UPDATE profiles SET peopleInterested=peopleInterested-1 WHERE userID='$useridT'",$con);
			mysql_query("DELETE FROM ratinglist WHERE isource = '$uid' AND interestedin='$useridT'",$con);
			$intDone =0;
			}
		 }
		}
		// interested in updating code ends
		// to create variables to show notice for interest in & out
		$checkInt = mysql_fetch_row(mysql_query("SELECT * FROM ratinglist WHERE isource='$uid' AND interestedin = '$useridT'",$con));
		 if($checkInt != null)
			$iFlag=1;
		else
			$iFlag =2;

		// rating begins
  if($cFlag==0)
   {  
	if(isset($_GET['rating']))
		    {
			 $donecho =0;	
		     $queryGet ="INSERT INTO ratinglist (rater,target,rating) VALUES ('$uid','$useridT','$_GET[rating]')";
			 $queryUpd = "UPDATE profiles SET ratings = ratings + '$_GET[rating]',raters = raters+1, averageRatings = (ratings/raters) WHERE userID = '$useridT'";
		     if(mysql_query($queryGet,$con)&&mysql_query($queryUpd,$con))
			    {
				  mysql_query("INSERT INTO notification (raterID,time) VALUES ('$uid','$time')",$con); //notification sidebar
                		 $donecho=1;		
                		 $cFlag=1;
				 $preRate = $_GET['rating'];
				}
		      else $donecho = 2;
		    }
    }
	// rating ends
	
		  //ranking begins
	  $i=0;

	  $rankStart =mysql_query("SELECT profileID FROM profiles, corn_users WHERE (profiles.campusCode='$campCodeT') AND (profiles.userID=corn_users.userID) ORDER BY profiles.ratings DESC, profiles.averageRatings DESC, corn_users.loginCount DESC, corn_users.timeSpent DESC, corn_users.userID DESC",$con);
			  while($rank = mysql_fetch_row($rankStart))
				{
				 $i++;
				 mysql_query("UPDATE profiles SET rank ='$i' WHERE profileID = '$rank[0]'",$con);
				}

        //ranking ends	
		
  $get2 = mysql_query("SELECT * FROM profiles WHERE userID = '$_GET[userID]'",$con);
	  while($getS2 = mysql_fetch_array($get2)) 
	 {$ratersT = $getS2['raters'];
		$avgrateT = $getS2['averageRatings'];
		$rankT = $getS2['rank'];
		
		$intT = $getS2['peopleInterested'];
          }  
    $firstname= explode(" ",$usernameT);      
	$emailflag=mysql_fetch_row(mysql_query("SELECT emailHidden FROM corn_users WHERE userID='$_GET[userID]'",$con));
	if(isset($_POST['teaserSubmit']))
	{
	 if($_POST['teaser']!='')
	  {
	   $teaser = validate($_POST['teaser']);
	   if(mysql_query("INSERT INTO kteasers (userID, teaser) VALUES('$uid', '$teaser')",$con))
				$teaserIns =1;
			else
				$teaserIns =0;
	  }
      else
			$teaserIns =2;
	 }
	function validate($input)
		{
			$input = trim($input);
			$input =htmlspecialchars($input);
			
			return $input;
		}	
			
	//counting stories published And stories read
      $countPub = mysql_num_rows(mysql_query("SELECT * FROM stone WHERE userID = '$useridT'",$conStone));
	  $countRead =	mysql_num_rows(mysql_query("SELECT * FROM readerdata WHERE readerID = '$useridT' AND action='r'",$conStone));
	  $countInf = mysql_num_rows(mysql_query("SELECT * FROM readerdata WHERE readerID = '$useridT' AND action='i'",$conStone));
	  
	//profile visits
	if($uid!=$useridT)
      mysql_query("INSERT INTO profile_visit (visitor, target, time) VALUES ('$uid','$useridT','$time')",$con);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesProfile" id="profileShowCase" lang="en">
  <HEAD>
      <TITLE> <?php echo $usernameT;?>'s profile</TITLE>
       <META name="description" content="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
	   <LINK type="text/css" rel="stylesheet" href="../profile_style.css" />
	   <LINK type="text/css" rel="stylesheet" href="search_style.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
  </HEAD>
  <BODY class="kornContent" onload="startNotification(), updatenotif(),loginstatus()">
  
    <DIV class="mainContainer" style="font-family:tahoma;font-size:13px;text-align:left;width:500px;padding-left:194px;padding-right:196px;">
	     <DIV class="DPbox" id="dpb" style="top:155px;cursor:auto;color:purple;font-size:13px;"> 
		 <?php
		   $pic =mysql_query("SELECT * FROM pictures WHERE userID = '$useridT'",$con);
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
	    <br /><br /><br /><br /><br /><br /><br /><br /><br />
		<DIV id="head" style="width:500px;font-family:tahoma;font-size:42px;text-align:center;border-style:solid;border-width:0px 0px 1px 0px;border-color:#ddddFF;">
         <?php echo $usernameT; ?>
		 <DIV style="font-size:14px;color:#cc9090;"><?php if($emailflag[0]==0) echo $emailT; else echo "Email address kept private";   ?></DIV>
         </DIV><br />
		 <div style="width:500px;">
		 <span style="font-size:17px;font-weight:bold;"> <?php echo $protypeT; ?>, </span><?php echo $courseT." ".$yearT; ?><br />
		 
		 <?php
		     if($protypeT=="Student")
			     echo "Studying ".$subjectT;
			  else
                 echo "Teaching ".$subjectT;
         ?>
         <span style="position:absolute;top:235px;right:190px;"><?php if($townT!='') {?>|From <span style="font-weight:bold;"> <?php echo $townT; ?></span></span><?php } ?>		 
		 </div>
		 <?php
			  
		if($cFlag==0) 
        {
         ?>	
        <div style="width:500px;">		
		 <span class="kfRateMeter" ></span><span class="kfRateMeter2" ></span><span class="kfRateMeter4"></span><span class="kfRateMeter5"></span><div class="kfRateMeter3"></div>
		 <br /><br /><div class="rateBar" onclick="location.href='showProfile.php?rating=1&userID=<?php echo $_GET['userID']; ?>';" style="cursor:pointer;"></div><div class="rateBar" style="left:330px;background-color:#ffaaaa;cursor:pointer;" onclick="location.href='showProfile.php?rating=2&userID=<?php echo $_GET['userID']; ?>';" ></div><div class="rateBar" style="left:440px;background-color:#ff9090;cursor:pointer;" onclick="location.href='showProfile.php?rating=3&userID=<?php echo $_GET['userID']; ?>';"></div>
		 <DIV class="rateBar" style="left:543px;background-color:#ff8080;cursor:pointer;" onclick="location.href='showProfile.php?rating=4&userID=<?php echo $_GET['userID']; ?>';"></DIV>
		 <DIV class="rateBar" style="left:620px;background-color:#ff6565;cursor:pointer;" onclick="location.href='showProfile.php?rating=5&userID=<?php echo $_GET['userID']; ?>';"></DIV><br /><br />
		 <span style="position:relative;left:38px;font-family:verdana;font-size:38px;"><a id="rate" href="showProfile.php?rating=1&userID=<?php echo $_GET['userID']; ?>">1</a></span>
		 <span style="position:relative;left:105px;font-family:verdana;font-size:38px;"><a id="rate" href="showProfile.php?rating=2&userID=<?php echo $_GET['userID']; ?>">2</a></span>
		 <span style="position:relative;left:187px;font-family:verdana;font-size:38px;"><a id="rate" href="showProfile.php?rating=3&userID=<?php echo $_GET['userID']; ?>">3</a></span>
		 <span style="position:relative;left:262px;font-family:verdana;font-size:38px;"><a id="rate" href="showProfile.php?rating=4&userID=<?php echo $_GET['userID']; ?>">4</a></span>
		 <span style="position:relative;left:312px;font-family:verdana;font-size:38px;"><a id="rate" href="showProfile.php?rating=5&userID=<?php echo $_GET['userID']; ?>">5</a></span>
		 </div>
		 <br /><div style="font-family:tahoma; color:#aaaaaa; font-size:12px;text-align:center;width:500px;"> How and why will you rate <?php echo $firstname[0]; ?>? <a id="sLink" onclick="showDialog()" style="color:#cc9090;">Click here</a><br />
		 </div><br />
		 <?php }
		 else
		  { ?> 
		  <div style="width:500px;">
		      <span class="kfRateMeter" style="background-color:#eeeeff;border-color:#eeeeff;"></span><span class="kfRateMeter2" style="background-color:#ddddff;border-color:#eeeeff;"></span><span class="kfRateMeter4" style="background-color:#ccccff;border-color:#eeeeff;"></span><span class="kfRateMeter5"style="background-color:#bbbbff;border-color:#eeeeff;"></span><div class="kfRateMeter3" style="background-color:#aaaaff;border-color:#eeeeff;"></div>
		 
		        <?php echo "<br /><br /><span style='font-family:georgia,tahoma;font-size:39px;color:#ff9090;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $preRate. "|</span><span style='font-family:georgia,tahoma;font-size:18px;font-style:italic;'>rated by you</span>";
				?>
             <br /><br /><div style="font-family:tahoma; color:#aaaaaa; font-size:12px;text-align:center;width:500px;">You cannot rate <?php echo $firstname[0]; ?> now. Wait for next kTeasers&trade;.
		 </div><br /></div>
		 <?php
		  }		
		   if($donecho==1) echo "<div style='background-color:#ffbbbb;border-style:solid;border-width:1px;border-color:#ff7070;'>".$firstname[0]." will not be informed about your response. Rating meter is now disabled till next kTeaser&trade;</div>";
		   if($donecho ==2)
		          echo "<div style='background-color:#ffbbbb;border-style:solid;border-width:1px;border-color:#ff7070;'>Some error occured at ahens server! Please try again.</div>";
		   if(isset($_POST['teaserSubmit']))
				{
					if($teaserIns==1)
						echo "<div style='background-color:#ffbbbb;border-style:solid;border-width:1px;border-color:#ff7070;'>Your teaser was succesfully submitted. All the best!</div>";
					else if($teaserIns==2)
						echo "<div style='background-color:#ffbbbb;border-style:solid;border-width:1px;border-color:#ff7070;'>You didn't enter the teaser!</div>";
						else
						     echo "<div style='background-color:#ffbbbb;border-style:solid;border-width:1px;border-color:#ff7070;'>Some error occured at ahens server! Please try again.</div>";
				}	
			if(isset($_GET['interested']))
				{
					if($intDone==1)
						echo "<div style='background-color:#ffbbbb;border-style:solid;border-width:1px;border-color:#ff7070;'>".$firstname[0]." will not be informed about this.</div>";
					if($intDone==0)	
						echo "<div style='background-color:#ffbbbb;border-style:solid;border-width:1px;border-color:#ff7070;'>".$firstname[0]." is now out of your interest. No information will be sent to ".$firstname[0].".</div>";
				}		
		?>   
		
		<DIV style="font-family:georgia;font-size:15px;color:#cc9090;text-align:center;border-style:solid;border-width:0px 0px 1px 0px;border-color:#ddddFF;width:500px;"><span style="font-size:20px;">&ldquo;</span><?php if($statT!='') echo $statT; else echo $usernameT. " has no profile statements."; ?><span style="font-size:20px;">&rdquo;</span>
		 </DIV> 
		<BR />
		<DIV class="teasers"><IMG src="http://kf.ahens.com/images/kTeasersSmall.jpg" alt="kTeasers logo" /> </DIV>
		<DIV style="width:500px;text-align:center;">
		<?php
		    $i =0;
			$teas = mysql_query("SELECT teaser FROM kteasers WHERE active = 1",$con);
		  while($getTeaser = mysql_fetch_row($teas))
			{?>
			
		    <DIV style="font-size:13px;"><?php echo $getTeaser[0]; ?></DIV>
		    <DIV style="font-size:13px;font-weight:bold;border-style:solid;border-width:0px 0px 1px 0px;border-color:#efefef;"><?php if($q1T[$i]!='')echo $q1T[$i]; else echo "Not Answered yet!"; ?></DIV>
		    <br />
		   <?php 
		    $i++;
		   }
		   ?>
		 </DIV> 
		 <DIV class="teasers">Original kTeasers&trade; 2.1 by <a href="http://kf.ahens.com/home/users/showProfile.php?userID=56">Krishna</a>. <span onclick="showTeaserReq()" style="color:#ff7060;cursor:pointer;" >Click Here</span> to create your own kTeasers.</DIV>
		 <DIV style="position:absolute;top:350px;left:710px;width:80px;height:80px;background-color:#ee8040;text-align:center;">
		 <span style="font-size:40px;"><?php echo $avgrateT; ?></span><br />Rating</DIV>
	         <DIV style="position:absolute;top:350px;left:795px;width:80px;height:80px;background-color:#ee8040;text-align:center;">
		 <span style="font-size:40px;"><?php echo $rankT; ?></span><br />Rank</DIV>
		 <DIV style="position:absolute;top:435px;left:710px;width:165px;height:80px;background-color:#ee4080;text-align:center;">
		 <span style="font-size:40px;"><?php echo $ratersT; ?></span><br />People rated this profile</DIV>
		  <DIV class="interested">
		  <?php
		 if($iFlag==1) 
		    { ?>
		    You & <span style="font-size:19px;">  <?php echo ($intT-1) ; ?> </span> other people are interested in <span style="font-weight:bold;"><?php echo $firstname[0]; ?></span> <br />
			<a style="color:#ff9090;text-decoration:none;" href="showProfile.php?interested=0&userID=<?php echo $useridT; ?>">
                 Cancel    </a>
			<?php }
          else if($iFlag==2)
			 {
				?>  <span style="font-size:18px;">  <?php echo $intT ; ?> </span>people are interested in <br /><span style="font-weight:bold;"><?php echo $firstname[0]; ?> </span><br />
			<a style="color:#ff9090;text-decoration:none;" href="showProfile.php?interested=1&userID=<?php echo $useridT; ?>">
               <?php if($uid!=$useridT) { ?>  Be the one <?php } ?></a>
				<?php }
			  ?>
                  </DIV>
		 
		 <br />   
		 <?php
		 if(($getS['dobDay']!='Day')&&($getS['dobDay']!='')&&($getS['dobMonth']!='Month')&&($getS['dobMonth']!='')&&($getS['dobYear']!='Year')&&($getS['dobYear']!=''))
		  {?>
		  <DIV class="proInfo"><?php echo $firstname[0];?> was born on</DIV>
		 <span style="font-size:14px;"><?php echo $dobT; ?></span><br /><br />
		 <?php }
		 if($lastT!='')
		 {?>
		 <DIV class="proInfo"><?php echo $firstname[0] ;?> attended</DIV>
		  <span style="font-size:14px;text-align:center;"><?php echo $lastT; ?></span><br /><br />
		  <?php }
		  if($areaT!='')
		  {?>
		    <DIV class="proInfo"><?php echo $firstname[0] ;?> is interested in</DIV>
		 <span style="font-size:14px;"><?php echo $areaT; ?></span><br /><br />
		 <?php }
		 if($fieldT!='')
		 {?>
		   <DIV class="proInfo"><?php echo $firstname[0] ;?> has following field of expertise</DIV>
		 <span style="font-size:14px;"><?php echo $fieldT; ?></span><br /><br />
		 <?php }
		 if($favPlaceT!='')
		 {?>
		   <DIV class="proInfo"><?php echo $firstname[0] ;?> finds following places best to spend time in campus</DIV>
		 <span style="font-size:14px;"><?php echo $favPlaceT; ?></span><br /><br />
		 <?php }
		 if($learnT!='')
		 {?>
		    <DIV class="proInfo"><?php echo $firstname[0] ;?> is right now learning</DIV>
		 <span style="font-size:14px;"><?php echo $learnT; ?></span><br /><br />
	         <?php }
	         if($readT!='')
	         {?>
	             <DIV class="proInfo"><?php echo $firstname[0] ;?>, these days, is reading</DIV>
		 <span style="font-size:14px;"><?php echo $readT; ?></span><br /><br />		 
		 <?php } ?>
		 <br /><hr /><br />
		 <span style="font-size:12px;color:#aaaaaa;"> ahens right now is in beta version. Release candidate is expected soon.</span><br /><br />
			<!--<DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV> -->
        <DIV id="end" style="position:absolute;bottom:-90px;left:250px;height:40px;">
	     <br />AHENS &copy; 2012
	  </DIV> 
	  
	    <DIV class="storyinfo"> <span style="font-size:40px;"><?php echo $countPub; ?></span> stories published</DIV>
	    <DIV class="storyinfo" style="top:400px; width:136px;"> <span style="font-size:40px;"><?php echo $countRead; ?></span> stories read</DIV>  
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
   
     <!--      <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='../search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='../';"> My Profile </span>
	<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')"/></span>				
			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  </DIV>
            </DIV> -->
              
	
	        
	
	<DIV class="midPlate" style="top:145px;">
	  <span style="position:absolute;left:430px;font-family:tahoma;font-size: 26px;font-style:bold;">
	  <?php 
	        $brow = mysql_query("SELECT * FROM campusdata",$con);
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
	<!--<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
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
	<DIV class="sideLink2" id="sideLnk6" onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','<?php echo $firstname[0]; ?>\'s Mirror')" onclick="location.href='peerMirror.php?userid=<?php echo $useridT; ?>';">
	<?php echo $firstname[0]; ?>'s Mirror
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
		 <DIV class="dialogueBox" id="dbox">
	 <DIV id="upBar">How to rate</DIV>
	 <br /><span style="font-size:13px;">Well, we don't know why would you like to rate <?php echo $usernameT; ?>. But we can tell you how!
		               Best criteria to rate are the Kornflakes Teasers&trade;. Other than that, you can rate the person on the basis of other info in his profile and his behaviour on Kornflakes. 
					   Also you can make your real experience with that person, the base for rating!<br /><br />Caution!<br />Kornflakes considers kTeasers&trade; the sole basis of ratings.
                       Rating meter will be disabled till next teasers appear. So, be careful about rating, as once you rate a person, you can't rate him until next teaser.</span>
	 <br /><br />
	 <button type= "button" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hide2()">OK</button>
	 </DIV>
	 <DIV class="dialogueBox" id="dbox3" style="position:fixed;opacity:0.99;top:200px;">
	 <DIV id="upBar">Send Your Teasers</DIV><br />
	 <br /><span style="font-size:15px;"> Enter Your Teaser<br /><br />
	            <FORM action="" method="post">
		               <INPUT type="text" style="height:30px;width:260px;" name="teaser" />
					   <br /><br />
					   <span style="font-size:13px;">Sending your teaser to ahens doesn't mean it will be displayed on KF. <br />Only the best teasers will qualify for kTeasers!<br />
					      Your teasers will be displayed along with your name. You can send one or more teasers to us. There is no upper limit.<br /> <br />All the best!
					    </span>
						<br /><br />
						<Button id="button" style="width:180px;" type="submit" name="teaserSubmit" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">Send teaser to ahens</button>		
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<Button id="button" type="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hideTeaser()" >Close</button>		
				</FORM>	
	 
	 </DIV>
<DIV id="notif"></DIV>	 
	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
  </BODY>	
</HTML>	
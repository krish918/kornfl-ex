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
	$conStone = mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>");
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
	$a = $_GET['limita']; $b = $_GET['limitb'];
	$sq =  mysql_query("SELECT * FROM notification ORDER BY notificationID DESC LIMIT $a,$b",$con);
	$flag=0;
	$get=mysql_fetch_row(mysql_query("SELECT notificationStat FROM corn_users WHERE userID = '$_SESSION[id]'",$con));
	while($notif =mysql_fetch_array($sq))
		{
		//notification for writing flakes
	 	if($notif['flakesID']!=0)
		 {
		   $flakes=mysql_fetch_array(mysql_query("SELECT userID,flaker,about,time FROM flakes WHERE flakesID = '$notif[flakesID]' AND campus='$_SESSION[camp]'",$con));
		   if(($flakes!=null) && ($flakes['userID']!=$_SESSION['id']))
		   {
		    $linkF="http://kf.ahens.com/home/peerflakes.php?userid=".$flakes['userID'];
		    $flag=1;
			if($notif['notificationID']>$get[0])
			  echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'> <a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$flakes['userID']."'><strong>".$flakes['flaker']."</strong></a> flaked about <span style='font-weight:bold;'>".$flakes['about']."</span>";
			else
			  echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$flakes['userID']."'><strong>".$flakes['flaker']."</strong></a> flaked about <span style='font-weight:bold;'>".$flakes['about']."</span>";
		    echo "<br /><span style='font-size:10px;'>".$flakes['time'];
		    echo "</span></DIV>";
		   }
		
		 }  
		 //notification for updating profile
		if($notif['profileID']!=0)
		 {
		   $profile=mysql_fetch_array(mysql_query("SELECT fullName,userID FROM profiles WHERE profileID = '$notif[profileID]' AND campusCode = '$_SESSION[camp]'",$con));
		   if(($profile!=null) && ($profile['userID']!=$_SESSION['id']))
		   {
		   $linkP = "http://kf.ahens.com/home/users/showProfile.php?userID=".$profile['userID'];
		   $flag=1;
		   if($notif['notificationID']>$get[0])
			  echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$profile['userID']."'>".$profile['fullName']."</a></span> updated his/her profile.";
			else
		   echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$profile['userID']."'>".$profile['fullName']."</a></span> updated his/her profile.";
		   echo "<br /><span style='font-size:10px;'>".$notif['time'];
		    echo "</span></DIV>";
		   }
		 } 
		 //notification for rating somebody
		 if($notif['raterID']!=0)
		 {
		   $rater=mysql_fetch_array(mysql_query("SELECT userName,userID FROM corn_users WHERE userID = '$notif[raterID]' AND campusJoined = '$_SESSION[camp]'",$con));
		   if(($rater!=null)&&($rater['userID']!=$_SESSION['id']))
		   {
		     $linkS = "http://kf.ahens.com/home/users/showProfile.php?userID=".$notif['raterID'];
		   $flag=1;
		   if($notif['notificationID']>$get[0])
			  echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$rater['userID']."'>".$rater['userName']."</a></span> rated somebody.";
			else
		   echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$rater['userID']."'>".$rater['userName']."</a></span> rated somebody.";
		   echo "<br /><span style='font-size:10px;'>".$notif['time'];
		   echo "</span></DIV>";
		   }
		 } 
		 // notification for joining network
		 if($notif['userID']!=0)
		 {
		   $user=mysql_fetch_array(mysql_query("SELECT userName, userID FROM corn_users WHERE userID = '$notif[userID]' AND campusJoined ='$_SESSION[camp]'",$con));
		   if($user!=null)
		   {
		   $linkT = "http://kf.ahens.com/home/users/showProfile.php?userID=".$notif['userID'];
		   $flag=1;
		   if($notif['notificationID']>$get[0])
			  echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$user['userID']."'>".$user['userName']."</a></span> joined your network.";
			else
		   echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$user['userID']."'>".$user['userName']."</a></span> joined your network.";
		   echo "<br /><span style='font-size:10px;'>".$notif['time'];
		   echo "</span></DIV>";
		   }
		 }
		 
		// notification for publising a story
	    if($notif['author']!=0)
			{
			 $aData = mysql_fetch_row(mysql_query("SELECT userName, campusJoined, userID FROM corn_users WHERE userID ='$notif[author]'",$con));
			 $cname = mysql_fetch_row(mysql_query("SELECT campusName FROM campusdata WHERE campusCode = '$aData[1]'",$con));
			 $stoneid = mysql_fetch_row(mysql_query("SELECT stoneID FROM stone WHERE userID = '$notif[author]' AND time='$notif[time]' ",$conStone));
			  $link = "http://kf.ahens.com/home/stone/readone.php?showstory=".$stoneid[0];
			  $link2 ="http://kf.ahens.com/home/users/showProfile.php?userID=".$notif['author'];
			if($notif['author'] != $_SESSION['id'])
				{
			$flag=1;
			if($aData[1] !=$_SESSION['camp']) 
			   { 
			   if($notif['notificationID']>$get[0])
			     echo " <div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$link2.">".$aData[0]."</a></span> from <span style='font-weight:bold;'>".$cname[0]."</span> published a story on stone. ";
			  else
			     echo " <div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$link2.">".$aData[0]."</a></span> from <span style='font-weight:bold;'>".$cname[0]."</span> published a story on stone. ";
			   }
		    else
				{ 
			   if($notif['notificationID']>$get[0])
			     echo " <div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$link2.">".$aData[0]."</a></span> from your own campus published a story on stone. ";
			   else
			     echo " <div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$link2.">".$aData[0]."</a></span> from your own campus published a story on stone.";	
				}
		    echo "<br /><span style='font-size:10px;'>".$notif['time'];
		    echo "</span> </div> ";
			   }
			} 
			
	    // notification for reading liking
		if(($notif['reader']!=0) && ($notif['writer']!=0) && ($notif['action']!=''))
			{
			  $getc = mysql_fetch_row(mysql_query("SELECT campusJoined,userName FROM corn_users WHERE userID = '$notif[reader]'",$con));
			  if(($getc[0]==$_SESSION['camp']) && ($notif['reader']!=$_SESSION['id']))
				{	
				$flag=1;
				$author = mysql_fetch_row(mysql_query("SELECT campusJoined,userName FROM corn_users WHERE userID = '$notif[writer]'",$con));
				$campName = mysql_fetch_row(mysql_query("SELECT campusName FROM campusdata WHERE campusCode = '$author[0]'",$con));
				//link for reader and writer
				$linkR = "http://kf.ahens.com/home/users/showProfile.php?userID=".$notif['reader'];
				$linkW = "http://kf.ahens.com/home/users/showProfile.php?userID=".$notif['writer'];
				
				// determining author's campus & generating name
				if($author[0] == $_SESSION['camp'])
					$string ="your own campus";
				else
					$string = "<span style='font-weight:bold;'>".$campName[0]."</span>";
				// determining if author is not you	
				if($notif['writer']==$_SESSION['id'])
						$string2 = "you.";
					else 
						$string2 = "<span style='font-weight:bold;'><a id='streamlink' href=".$linkW.">".$author[1]."</a></span> from ".$string.".";	
				//for reading
				if($notif['action']=='r')
					{
					// if reader & writer are same or not
					if($notif['writer']!=$notif['reader'])
						{
						if($notif['notificationID']>$get[0])	
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold'><a id='streamlink' href=".$linkR.">".$getc[1]."</a></span> read a story published by ".$string2;
						else  
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$linkR.">".$getc[1]."</a></span> read a story published by ".$string2;
						echo "<br /><span style='font-size:10px;'>".$notif['time'];
						echo "</span></DIV>";	
						}
					else
						{
						if($notif['notificationID']>$get[0])	
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold'><a id='streamlink' href=".$linkW.">".$getc[1]."</a></span> read his/her own story.";
						else  
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$linkW.">".$getc[1]."</a></span> read his/her own story.";
						echo "<br /><span style='font-size:10px;'>".$notif['time'];
						echo "</span></DIV>";	
						
						}
					}
				// for influencing	
				if($notif['action']=='i')
					{
				    //checking if reader and writer are same	
					if($notif['writer']!=$notif['reader'])
					  {
						if($notif['notificationID']>$get[0])	
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold'><a id='streamlink' href=".$linkR.">".$getc[1]."</a></span> was influenced by a story published by ".$string2;
						else 
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$linkR.">".$getc[1]."</a></span> was influenced by a story published by ".$string2;
						echo "<br /><span style='font-size:10px;'>".$notif['time'];
						echo "</span></DIV>";   
					  }
					else
						{
						if($notif['notificationID']>$get[0])	
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;background-color:#dddfff;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold'><a id='streamlink' href=".$linkW.">".$getc[1]."</a></span> was influenced by his/her own story";
						else 
							echo "<div id='streamdiv' style='padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;'><span style='font-weight:bold;'><a id='streamlink' href=".$linkW.">".$getc[1]."</a></span> was influenced by his/her own story.";
						echo "<br /><span style='font-size:10px;'>".$notif['time'];
						echo "</span></DIV>";   
					
						}
					}
				}	
			}
		 // for comments
		if($notif['stoneID']!=0 && $notif['commenter']!=0)
			{
			$flag =1;
			$idu =mysql_fetch_row(mysql_query("SELECT userID,campus,name from stone WHERE stoneID = '$notif[stoneID]'",$conStone));
			$commenter = mysql_fetch_row(mysql_query("SELECT userID,campusJoined,userName from corn_users WHERE userID = '$notif[commenter]'",$con));
			$commCamp = mysql_fetch_row(mysql_query("SELECT campusName from campusdata WHERE campusCode = '$commenter[1]'",$con));
			$linkStone = "http://kf.ahens.com/home/stone/readone.php?showstory=".$notif['stoneID'];
			$linkCommenter = "http://kf.ahens.com/home/users/showProfile.php?userID=".$notif['commenter'];
			$linkTarget = "http://kf.ahens.com/home/users/showProfile.php?userID=".$idu[0];
			if(($notif['stoneID']!=2) ||  ($_SESSION['id']==56) || ($_SESSION['id']==220))
				{ 
			if(($idu[0] == $_SESSION['id']) && ($commenter[0]!=$_SESSION['id']))
			 {
			  if($commCamp[0] == $idu[1])
			    $string = "your own campus commented on your story.";
			  else
				$string = "<span style='font-weight:bold;'>".$commCamp[0]."</span> commented on your story.";		
			   
			  if($notif['notificationID']>$get[0])	
						{ ?><div id="streamdiv" onclick="location.href='<?php echo $linkStone; ?>';" style="cursor:pointer;background-color:#dddfff;padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;"><span style="font-weight:bold"><a id="streamlink" href="<?php echo $linkCommenter; ?>"><?php echo $commenter[2]; ?></a></span> from <?php echo $string; ?>	
				<?php } else 
					   { ?><div id="streamdiv" onclick="location.href='<?php echo $linkStone; ?>';" style="cursor:pointer;padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;"><span style="font-weight:bold"><a id="streamlink" href="<?php echo $linkCommenter; ?>"><?php echo $commenter[2]; ?></a></span> from <?php echo $string; ?>	
				 <?php } echo "<br /><span style='font-size:10px;'>".$notif['time'];
				  echo "</span></div>";
			 }
			else if(($commenter[1]==$_SESSION['camp'])&&($commenter[0]!=$_SESSION['id']))
			   {
				  if($commenter[0]==$idu[0])
				   $string2 = "his/her own story."; 
				  else
					$string2 = "story of <span style='font-weight:bold;'><a id='streamlink' href=".$linkTarget.">".$idu[2]."</a></span> from <span style='font-weight:bold;'>".$idu[1]."</span>";	
				  if($notif['notificationID']>$get[0])	
						{ ?> <div id="streamdiv" onclick="location.href='<?php echo $linkStone; ?>';" style="cursor:pointer;background-color:#dddfff;padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;"><span style="font-weight:bold;"><a id="streamlink" href="<?php echo $linkCommenter; ?>"><?php echo $commenter[2]; ?></a></span> from your own campus commented on <?php echo $string2; }	
				    else 
					   { ?> <div id="streamdiv" onclick="location.href='<?php echo $linkStone; ?>';" style="cursor:pointer;padding-top:12px;padding-bottom:12px;border-style:solid; border-width:0px 0px 1px 0px; border-color:#bbbbbb;"><span style="font-weight:bold;"><a id="streamlink" href="<?php echo $linkCommenter; ?>"><?php echo $commenter[2]; ?></a></span> from your own campus commented on <?php echo $string2; }
				  echo "<br /><span style='font-size:10px;'>".$notif['time'];
				  echo "</span></div>";
	            } 
		    }
         }
	   }
    if($flag==0)
		echo "0";
	$notifStat = mysql_fetch_row(mysql_query("SELECT notificationID FROM notification ORDER BY notificationID DESC",$con));
	mysql_query("UPDATE corn_users SET notificationStat = '$notifStat[0]' WHERE userID ='$_SESSION[id]'",$con);	   	  
?>		
	
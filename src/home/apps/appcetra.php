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

if(!$con)  {    header("location:http://kf.ahens.com/error/?conerror=1");	exit;  }
	     mysql_select_db("<db-name>", $con);  
 session_start(); 
   $uid = mysql_fetch_row(mysql_query("SELECT userID from corn_users WHERE Email = '$_SESSION[user]'",$con));  
     $subscriber_num = mysql_num_rows(mysql_query("SELECT * from appsubscription WHERE appID='$_GET[appID]'",$con));
	 	$sql2 = mysql_query("SELECT userID from appsubscription WHERE appID = '$_GET[appID]'",$con);
			$sql3 = mysql_query("SELECT userID from appsubscription WHERE appID = '$_GET[appID]' AND userID='$uid[0]'",$con); 
			  if($_GET['appname']==1)   {       $sql1 = mysql_fetch_row(mysql_query("SELECT appName from appcetra where appID = '$_GET[appID]'",$con));	   
			   echo "<div style='font-size:22px;font-weight:bold;'>Welcome to ".$sql1[0]."!</div>  This is not a core app, so you will have to subscribe for it.";	    exit;   }  
			   if($_GET['oldsubs']==1)   {           if($subscriber_num==0) echo "Nobody has subscribed for this app yet. Be the first!"; 
			   else if($subscriber_num==1) {          $onlyName = mysql_fetch_array($sql2);		  $getSubName = mysql_fetch_row(mysql_query("SELECT userName from corn_users WHERE userID = '$onlyName[userID]'",$con));	
			   	  if($uid[0]!=$onlyName['userID'])		  echo "Only <a href='http://kf.ahens.com/home/users/showProfile.php?userID=".$onlyName['userID']."'>".$getSubName[0]."</a> has subscribed for this app. Be the next!";		 
			   	   else		   echo "Only you are subscribed for this app.";	
			   	   	  }       
 else echo $subscriber_num." people have subscribed for this app. <a id='listLink' style='color:blue;' href='javascript: showlist(1)'>Know more</a> about them.";   exit;   } 
			     if($_GET['checksubs']==1)   {       $checkSubs = mysql_fetch_array($sql3);	   if($checkSubs != null)	     echo 1;		exit;    }  
			      if($_GET['subslist']==1)	{	 while($getall = mysql_fetch_array($sql2))	     
				    {		     $getName = mysql_fetch_row(mysql_query("SELECT userName,campusJoined from corn_users WHERE userID = '$getall[userID]'",$con));	  
				         $getCampus = mysql_fetch_row(mysql_query("SELECT campusName from campusdata WHERE campusCode = '$getName[1]'",$con));	
						 		 echo "<a id='listLink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$getall['userID']."'><div style='padding:7px;'>".$getName[0]."<div style='color:#949494;font-size:11px;'>".$getCampus[0]."</div></a></div>";			 }			
					 exit;			}
					 if($_GET['getSubscribedApps']==1)	
					 	{	        	$getSubsQuery = mysql_query("SELECT * FROM appcetra,appsubscription WHERE appsubscription.userID = '$_SESSION[uidx]' AND appcetra.appID = appsubscription.appID",$con);		
					 		$num_apps =mysql_num_rows($getSubsQuery);  					
									while($getAppsSubs = mysql_fetch_array($getSubsQuery))				{				 				   ?>				   <a id="listLink" href="<?php echo $getAppsSubs['appLink']; ?>">
										<div class="subs_content">				   <div id="appName_subslist"><?php echo $getAppsSubs['appName']; ?> </div>	
													   <div id="subs_timestamp"> Subscribed on &raquo; <?php echo $getAppsSubs['timestamp']; ?></div>		
													   		   <div id="subs_total"> Total <?php echo $getAppsSubs['totalSubscriptions']; ?> Subscriptions </div>		
												   		   		   </div></a>
													   		   
													   		   		    <?php
																	}
																	}
 ?>
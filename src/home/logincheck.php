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
		header("location:http://kf.ahens.com/error/?conerror=1");
		exit;
	  }
	if(!$conStone)
	  {
		header("location:http://kf.ahens.com/error/?conerror=1");
		exit;
	  } 
	mysql_select_db("<db-name>", $con);
	mysql_select_db("<db-name>", $conStone);

	session_start();
	$indtime = time() + 45000;
	$time =date("d/m/Y | G:i",$indtime); 
	$time_sec = time()-1348483714;
	$query2="SELECT * FROM corn_users WHERE lastUpdate > ('$time_sec'-10) AND lastUpdate<>0 AND userID<>'$_SESSION[id]'";
	if(isset($_GET['countpeople']))
	{
	mysql_query("UPDATE corn_users SET lastUpdateStat='$time', lastUpdate = '$time_sec' WHERE Email = '$_SESSION[user]'",$con);
			
		/*  
		   else
			 mysql_query("UPDATE corn_users SET live =0 WHERE Email ='$_SESSION[user]'",$con);	
		} 
		
	$query2 = "SELECT userName FROM corn_users WHERE live =1 AND campusJoined = '$_SESSION[camp]'";
     	*/
	$countOnline = mysql_num_rows(mysql_query($query2,$con));
	echo $countOnline; 
	}
	
	if(isset($_GET['getpeople']))
		{
		$getall =mysql_query($query2,$con);
	   	while($name = mysql_fetch_array($getall))
			  echo $name['userName']."<br />";	
		}	
		
	if($_GET['reset']==1)
		{
			$not=mysql_query("SELECT flakesID FROM flakes ORDER BY flakesID DESC", $con);
			$notify = mysql_fetch_array($not);
			$_SESSION['lastFlakes'] = $notify[0];	
			$not2=mysql_query("SELECT userID FROM corn_users WHERE campusJoined = '$_SESSION[campus]' ORDER BY userID DESC", $con);
			$notify2 = mysql_fetch_array($not2);
			$_SESSION['lastUser'] = $notify2[0];
			$_SESSION['camp'] = $_SESSION['campus'];
			$not3=mysql_query("SELECT rateID FROM ratinglist WHERE target = '$_SESSION[uidx]' ORDER BY rateID DESC", $con);
			$notify3 = mysql_fetch_array($not3);
			$_SESSION['newRater'] = $notify3[0];
    		$_SESSION['id'] = $_SESSION['uidx'];	
			$stone = mysql_fetch_row(mysql_query("SELECT stoneID FROM stone ORDER BY stoneID DESC", $conStone));
			$_SESSION['stoneNotif'] = $stone[0];
			$read = mysql_fetch_row(mysql_query("SELECT dataID FROM readerdata ORDER BY dataID DESC", $conStone));
			$_SESSION['readNotif'] = $read[0];
			$comment = mysql_fetch_row(mysql_query("SELECT commentID FROM comments ORDER BY commentID DESC", $conStone));
			$_SESSION['comment'] = $comment[0]; 
		 	
		}	
		  
?>			
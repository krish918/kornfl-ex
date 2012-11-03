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
$get=mysql_fetch_row(mysql_query("SELECT notificationStat FROM corn_users WHERE userID = '$_SESSION[id]'",$con));
$me = $_SESSION['id'];
$camp =$_SESSION['camp'];
$sql = mysql_fetch_row(mysql_query("SELECT profileID FROM profiles WHERE userID = '$me'",$con));//for excluding user himself
$county=0;
$checkNew=mysql_query("SELECT * FROM notification WHERE notificationID > '$get[0]'",$con);
	 
	 
	 while($newNotif=mysql_fetch_array($checkNew))
	 {
	  $sql2 = mysql_fetch_row(mysql_query("SELECT flakesID FROM flakes WHERE (flakesID = '$newNotif[flakesID]') AND (userID ='$me')",$con)); //for excluding user himself
	   // data for campus check
      $c1 = mysql_fetch_row(mysql_query("SELECT campusJoined FROM corn_users WHERE userID = '$newNotif[raterID]'",$con));
	  $c2 = mysql_fetch_row(mysql_query("SELECT campusCode FROM profiles WHERE profileID = '$newNotif[profileID]'",$con));
	  $c3 = mysql_fetch_row(mysql_query("SELECT campus FROM flakes WHERE flakesID = '$newNotif[flakesID]'",$con));
	  $c4 = mysql_fetch_row(mysql_query("SELECT campusJoined FROM corn_users WHERE userID = '$newNotif[userID]'",$con));
	  $c5 = mysql_fetch_row(mysql_query("SELECT campusJoined FROM corn_users WHERE userID = '$newNotif[reader]'",$con));
	  $c6 = mysql_fetch_row(mysql_query("SELECT campusJoined FROM corn_users WHERE userID = '$newNotif[commenter]'",$con));
	  $c7 = mysql_fetch_row(mysql_query("SELECT userID FROM stone WHERE stoneID = '$newNotif[stoneID]'",$conStone));
	  // campus check ends
	  if($newNotif['raterID']!= $me && $newNotif['profileID']!=$sql[0] && $newNotif['flakesID']!=$sql2[0] && $newNotif['author']!= $me && $newNotif['reader']!= $me && $newNotif['commenter']!=$me)
	   {
	     if(($c1[0]==$camp) || ($c2[0]==$camp) || ($c3[0]==$camp) || ($c4[0]==$camp)|| ($newNotif['author']!=0) || ($c5[0]==$camp) ||($c6[0]==$camp) || ($c7[0]==$me))
			$county++;
	   }
		
	  }	
	  echo $county;
?>	
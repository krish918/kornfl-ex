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
mysql_select_db("<db-name>", $con);
session_start();
$_SESSION['checkpoint']=1;
	$indiantime = time()+45000;
	$dat =date("F d, Y | h:i a",$indiantime);
	
if($_GET['act']==1){
	$query="SELECT * FROM activationData WHERE (activationString='$_GET[cs]' AND userID='$_GET[uid]') AND used =0";
	$checkString = mysql_num_rows(mysql_query($query,$con));
	if($checkString!=1){
		header("location:http://kf.ahens.com/error/noaccess/?access=denied");
	}
	else {
		$getDetail = mysql_fetch_array(mysql_query($query,$con));
		if(mysql_query("UPDATE activationData SET usedAt='$dat',used=1 WHERE activationID='$getDetail[activationID]'",$con))
		{
		if(mysql_query("UPDATE corn_users SET activation=-1 WHERE userID='$_GET[uid]'",$con))
				header("location:http://kf.ahens.com?actused=done");	
		else
			header("location:http://kf.ahens.com/error/?conerror=1");		
		}
		else header("location:http://kf.ahens.com/error/?conerror=1");
			
	}
} 
?>  	
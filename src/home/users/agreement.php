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
      while($row = mysql_fetch_array($query))
       {
          if(($row['Email']==$_SESSION['user'])||($row['Email']==$_COOKIE['user']))
             {
               $uid = $row['userID'];
               $username = $row['userName'];
	       if($row['campusJoined'] == '')
                       die("AHENS SYSTEM IS DENYING ACCESS! PLEASE PRESS BACK BUTTON.");	
		else $campCode =$row['campusJoined'];
		
             }
       } 
   
      if($_GET['sflakesID']!='')
        {
               $sql = "INSERT INTO agreelist (flakesID, agreeID) VALUES ('$_GET[sflakesID]','$uid')";
               if(!mysql_query($sql,$con)) echo mysql_error();
               $update = "UPDATE flakes SET agree =agree+1 WHERE flakesID = '$_GET[sflakesID]'";
               if(!mysql_query($update,$con)) echo mysql_error(); 
        }
      if($_GET['sdisflakesID']!='')
         {
              $sql = "INSERT INTO agreelist (flakesID, disagreeID) VALUES ('$_GET[sdisflakesID]','$uid')";
               if(!mysql_query($sql,$con)) echo mysql_error();
               $update = "UPDATE flakes SET disagree =disagree+1 WHERE flakesID = '$_GET[sdisflakesID]'";
               if(!mysql_query($update,$con)) echo mysql_error();
          }
       if($_GET['aflakesID']!='')
          {
               $sql = "UPDATE agreelist SET agreeID ='$uid', disagreeID =0 WHERE ((flakesID = '$_GET[aflakesID]') AND (disagreeID ='$uid'))";
                if(!mysql_query($sql,$con)) echo mysql_error();
                $update = "UPDATE flakes SET agree = agree+1, disagree = disagree-1 WHERE flakesID ='$_GET[aflakesID]'";
                if(!mysql_query($update,$con)) echo mysql_error();
           }
       
       if($_GET['adisflakesID']!='')
          {
               $sql = "UPDATE agreelist SET agreeID =0, disagreeID ='$uid' WHERE ((flakesID = '$_GET[adisflakesID]') AND (agreeID ='$uid'))";
                if(!mysql_query($sql,$con)) echo mysql_error();
               $update = "UPDATE flakes SET agree = agree-1, disagree = disagree+1 WHERE flakesID='$_GET[adisflakesID]'";
                if(!mysql_query($update,$con)) echo mysql_error();
           }
      header("location:commonroom.php");     
 ?>


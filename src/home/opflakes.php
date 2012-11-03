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
      if($_GET['flakesid']!='')
         {
          if(mysql_query("DELETE FROM flakes WHERE flakesID = '$_GET[flakesid]'",$con))
            {
			 mysql_query("DELETE FROM notification WHERE flakesID = '$_GET[flakesid]'",$con);
              header("location:http://kf.ahens.com/home/flakes.php?deleted=1");
             }
           else
              header("location:http://kf.ahens.com/home/flakes.php?deleted=0");
         }
       if($_GET['hideid']!='')
         {
           if(mysql_query("UPDATE flakes SET hidden =1 WHERE flakesID = '$_GET[hideid]'",$con)) 
               header("location:http://kf.ahens.com/home/flakes.php?hidden=1");
             else
                header("location:http://kf.ahens.com/home/flakes.php?hidden=0");  
          }
       if($_GET['unhideid']!='')
          {
            if(mysql_query("UPDATE flakes SET hidden =0 WHERE flakesID = '$_GET[unhideid]'",$con)) 
               header("location:http://kf.ahens.com/home/flakes.php?show=1");
             else
                header("location:http://kf.ahens.com/home/flakes.php?show=0"); 
          }      
?>            
                          
             
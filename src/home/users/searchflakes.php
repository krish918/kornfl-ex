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
		header("location:http://www.ahens.com/error.html");
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
	   function linkcheck($a)
		{
		  $link_pattern = '/\b(http:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.[^\s][^\s]+/';
          preg_match($link_pattern, $a, $matches);
		  $replace="<a href='$matches[0]'>$matches[0]</a>";
          $mod_flakes=str_ireplace($matches[0], $replace, $a); 
		  return $mod_flakes;
		}  
      if($_GET['query']!='')
         {
           $search=strlen($_GET['query']);
		   $_GET['query'] = stripslashes($_GET['query']);
            $get = mysql_query("SELECT * FROM flakes WHERE campus = '$campCode' AND hidden = 0 ORDER BY flakesID DESC",$con);
            $count=0;
           while($getFlakes = mysql_fetch_array($get))
             {
               if((stripos($getFlakes['flaker'],$_GET['query'])>-1)||(stripos($getFlakes['about'],$_GET['query'])>-1))
                 {
                    $isql =mysql_query("SELECT profileProjected FROM corn_users WHERE userID ='$getFlakes[userID]'",$con);
              $checkProj = mysql_fetch_row($isql);
                echo "<DIV style='border-style:solid; border-width: 0px 0px 1px 0px;border-color:#90dd50;padding:4px;'><div style='font-weight:bold;font-family:verdana;background-color:#eeeeff;'>";
                if($checkProj[0]==1) echo "<a style='text-decoration:none;color:#8967ff;' href='showProfile.php?userID=".$getFlakes['userID']."'>".$getFlakes['flaker']."</a>"; 
                   else echo $getFlakes['flaker'];
                    echo "<span style='font-weight:normal;'> flaked about</span> ";
                      if(($getFlakes['about']=='myself')||($getFlakes['about']=='self'))
                          echo "himself"; 
                      else
                          echo $getFlakes['about'];
						$mod_f = linkcheck($getFlakes['flakes']);  
                       echo "</div><br /><span style='font-size:14px;'>".nl2br($mod_f)."</span><br /><br />";
                   $agSql = mysql_query("SELECT flakesID, agreeID,disagreeID FROM agreelist WHERE (flakesID='$getFlakes[flakesID]') AND (agreeID = '$uid' OR disagreeID ='$uid')",$con); 
                  $checkAgree = mysql_fetch_row($agSql);
                   if($checkAgree==NULL)
                       {?><span style="color:#cc8080;font-size:12px;cursor:pointer;" onclick="location.href='agreement.php?sflakesID=<?php echo $getFlakes['flakesID']; ?>';">
                       <?php echo $getFlakes['agree']; ?>  Agree</span> | <span style="color:#cc8080;font-size:12px;cursor:pointer;" onclick="agreement.php?sdisflakesID=<?php echo $getFlakes['flakesID']; ?>';"><?php echo $getFlakes['disagree']; ?> Disagree</span><br /> 
                       <?php } 
                     else if($checkAgree[0]!=0 && $checkAgree[2]!=0)
                        { ?> <span style="color:#cc8080;font-size:12px;cursor:pointer;" onclick="location.href='agreement.php?aflakesID=<?php echo $getFlakes['flakesID']; ?>';">
                       <?php echo $getFlakes['agree']; ?>  Agree</span> | <span style="color:#cc8080;font-size:12px;"><?php echo $getFlakes['disagree']; ?> Disagree</span><br /> 
                       <?php }  
                        else if($checkAgree[0]!=0 && $checkAgree[1]!=0)
                           { ?> <span style="color:#cc8080;font-size:12px;">
                       <?php echo $getFlakes['agree']; ?>  Agree</span> | <span style="color:#cc8080;font-size:12px;cursor:pointer;" onclick="agreement.php?adisflakesID=<?php echo $getFlakes['flakesID']; ?>';"><?php echo $getFlakes['disagree']; ?> Disagree</span><br /> 
                       <?php } 
                         echo "<span style='font-size:11px;color:#909090'>".$getFlakes['time']."</span></DIV><br />";
                         $count++;
                    
                    }
                  
               
             }
            
                     
             if($count==0)
                {
                  echo "<DIV style='background-color:#ffdddd;border:solid 1px #ff9090;padding:3px;'><span style='font-weight:bold;'>".$_GET['query']."</span> was not found!</DIV><br />";
                }  
             else
               {
                echo "<DIV style='border:solid 1px #9090ff;font-size:14px;padding:3px;'><br />Total ".$count." Flakes were returned!<br /><br /></DIV><br />";
               } 
           } 
         if($_GET['id']!='')
           {
		      $_GET['query'] = stripslashes($_GET['query']);
               $search=strlen($_GET['query1']);
            $get = mysql_query("SELECT * FROM flakes WHERE userID='$_GET[id]' AND hidden = 0 ORDER BY flakesID DESC",$con);
            $count=0;
           while($getFlakes = mysql_fetch_array($get))
             {
               if((strncasecmp($getFlakes['about'],$_GET['query1'],$search)==0)||(strncasecmp($getFlakes['flakes'],$_GET['query1'],$search)==0))
                 {
                    $isql =mysql_query("SELECT profileProjected FROM corn_users WHERE userID ='$getFlakes[userID]'",$con);
              $checkProj = mysql_fetch_row($isql);
                echo "<DIV style='border-style:solid; border-width: 0px 0px 1px 0px;border-color:#90dd50;padding:4px;'><div style='font-weight:bold;font-family:verdana;background-color:#eeeeff;'>";
                if($checkProj[0]==1) echo "<a style='text-decoration:none;color:#8967ff;' href='showProfile.php?userID=".$getFlakes['userID']."'>".$getFlakes['flaker']."</a>"; 
                   else echo $getFlakes['flaker'];
                    echo "<span style='font-weight:normal;'> flaked about</span> ";
                      if(($getFlakes['about']=='myself')||($getFlakes['about']=='self'))
                          echo "himself"; 
                      else
                          echo $getFlakes['about'];
					    $mod_f = linkcheck($getFlakes['flakes']);	  
                       echo "</div><br /><span style='font-size:14px;'>".nl2br($mod_f)."</span><br /><br />";
                       echo $getFlakes['agree']; ?>  Agree</span> | <span style="color:#cc8080;font-size:12px;"><?php echo $getFlakes['disagree']; ?> Disagree</span><br /> 
                       <?php
                         echo "<span style='font-size:11px;color:#909090'>".$getFlakes['time']."</span></DIV><br />";
                         $count++;
                    
                    }
               }   
               
                  
             if($count==0)
                {
                  echo "<DIV style='background-color:#ffdddd;border:solid 1px #ff9090;'><span style='padding:3px;font-weight:bold;'>".$_GET['query1']."</span> was not found!</DIV><br />";
                }  
             else
               {
                echo "<DIV style='border:solid 1px #9090ff;font-size:14px;padding:3px;'><br />Total ".$count." Flakes were returned!<br /><br /></DIV><br />";
               }       
           
           } 
	
		   
         ?>          
                         
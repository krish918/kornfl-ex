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
      $indiantime = time()+45000;
      $time =date("F j, Y | g:i a", $indiantime);
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
     if(($_SESSION['user']=='')&&($_COOKIE['user']==""))	
		     header("Location:../../index.php"); 
	  $noflakes =0;
	  $noabt =0;
	  $insert=0;
          if(($_GET['flakes']!='')&&($_GET['about']!=''))
             {   
                 $about = validInput($_GET['about']);
                  $flakes = validInput($_GET['flakes']);
                  $sense= abuseSensor($about, $flakes);
                   if($sense==1)
                    {
                   $insertFlakes = "INSERT INTO flakes (userID, flaker, about, flakes, campus,time) VALUES ('$uid','$username','".$about."','".$flakes."','$campCode','$time')";
                   if(mysql_query($insertFlakes, $con))
                      {
                        echo "<DIV style='background-color:#ffdddd;border:solid 1px #ff9090;width:500px;'>Your Flakes was successfully posted!</DIV><br />";
                        $getall2 = mysql_query("SELECT * FROM flakes WHERE userID = '$uid' ORDER BY flakesID DESC", $con);
                        $getFlakes = mysql_fetch_array($getall2);
						mysql_query("INSERT INTO notification (flakesID) VALUES ('$getFlakes[flakesID]')",$con); //notification sidebar
                        echo "<DIV style='border-style:solid; border-width: 0px 0px 1px 0px;border-color:#90dd50;padding:4px;'><div style='font-weight:bold;font-family:verdana;background-color:#eeeeff;'> 
                      You <span style='font-weight:normal;'>flaked about</span> ";
                      if(($getFlakes['about']=='myself')||($getFlakes['about']=='self'))
                          echo "yourself"; 
                      else
                          echo $getFlakes['about'];
					   $link_pattern = '/\b(http:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.[^\s][^\s]+/';
                        preg_match($link_pattern, $getFlakes['flakes'], $matches);
					    $replace="<a href='$matches[0]'>$matches[0]</a>";
                       $mod_flakes=str_ireplace($matches[0], $replace, $getFlakes['flakes']); 						
                       echo "</div><br /><span style='font-size:14px;'>".nl2br($mod_flakes)."</span><br /><br /><span style='font-size:11px;color:#909090'>";
                       echo $getFlakes['time']." | ".$getFlakes['agree']. " Agree | ".$getFlakes['disagree']." Disagree<br /></span>"; 
                       ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?flakesid=<?php echo $getFlakes['flakesID']; ?>';">Delete | </span><?php
                        if($getFlakes['hidden']==0)
                       { ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?hideid=<?php echo $getFlakes['flakesID']; ?>';">Hide</span></DIV><br /><?php }
                        else
                         {  ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?unhideid=<?php echo $getFlakes['flakesID']; ?>';">Unhide</span></DIV><br /><?php } 
                       $next =2;
                        }
                     
                        
                   else
                        echo "<DIV Style='background-color:#ffdddd;border:solid 1px #ff9090;width:500px;'>Some error ocurred at ahens server. Please try later!</DIV>";
                    } 
                    else
                       echo "<DIV Style='background-color:#ffdddd;border:solid 1px #ff9090;width:500px;'>Your Flakes contains abusive or restricted words. Please try a different spelling or drop the word!</DIV>";  
                     
              }
            if($next==2)
              {
                 while($getFlakes = mysql_fetch_array($getall2))
                  {
                        echo "<DIV style='border-style:solid; border-width: 0px 0px 1px 0px;border-color:#90dd50;padding:4px;'><div style='font-weight:bold;font-family:verdana;background-color:#eeeeff;'> 
                      You <span style='font-weight:normal;'>flaked about</span> ";
                      if(($getFlakes['about']=='myself')||($getFlakes['about']=='self'))
                          echo "yourself"; 
                      else
                          echo $getFlakes['about'];
					 $link_pattern = '/\b(http:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.[^\s][^\s]+/';
                        preg_match($link_pattern, $getFlakes['flakes'], $matches);
					    $replace="<a href='$matches[0]'>$matches[0]</a>";
                       $mod_flakes=str_ireplace($matches[0], $replace, $getFlakes['flakes']); 								
                       	  
                       echo "</div><br /><span style='font-size:14px;'>".nl2br($mod_flakes)."</span><br /><br /><span style='font-size:11px;color:#909090'>";
                       echo $getFlakes['time']." | ".$getFlakes['agree']. " Agree | ".$getFlakes['disagree']." Disagree<br /></span>"; 
                       ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?flakesid=<?php echo $getFlakes['flakesID']; ?>';">Delete | </span><?php
                        if($getFlakes['hidden']==0)
                       { ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?hideid=<?php echo $getFlakes['flakesID']; ?>';">Hide</span></DIV><br /><?php }
                        else
                         {  ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?unhideid=<?php echo $getFlakes['flakesID']; ?>';">Unhide</span></DIV><br /><?php } 
                    }
                 }    
                    
           if($_GET['getal']==1)
             {
                $getall = mysql_query("SELECT * FROM flakes WHERE userID = '$uid' ORDER BY flakesID DESC", $con);
                
                while($getFlakes = mysql_fetch_array($getall))
                   { 
 		              $link_pattern = '/\b[http:\/\/]*[www]*\..\..\b/';
                        preg_match($link_pattern, $getFlakes['flakes'], $matches);
                       $mod_flakes=str_ireplace($matches[0], "replaced", $getFlakes['flakes']); 						
                       
                      echo "<DIV style='border-style:solid; border-width: 0px 0px 1px 0px;border-color:#90dd50;padding:4px;'><div style='font-weight:bold;font-family:verdana;background-color:#eeeeff;'> 
                      You <span style='font-weight:normal;'>flaked about</span> ";
                      if(($getFlakes['about']=='myself')||($getFlakes['about']=='self'))
                          echo "yourself"; 
                      else
                          echo $getFlakes['about'];
					 $link_pattern = '/\b(http:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.[^\s][^\s]+/';
                        preg_match($link_pattern, $getFlakes['flakes'], $matches);
					    $replace="<a href='$matches[0]'>$matches[0]</a>";
                       $mod_flakes=str_ireplace($matches[0], $replace, $getFlakes['flakes']); 								
                       	  
                       echo "</div><br /><span style='font-size:14px;'>".nl2br($mod_flakes)."</span><br /><br /><span style='font-size:11px;color:#909090'>";
                       echo $getFlakes['time']." | ".$getFlakes['agree']. " Agree | ".$getFlakes['disagree']." Disagree<br /></span>"; 
                       ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?flakesid=<?php echo $getFlakes['flakesID']; ?>';">Delete | </span><?php
                        if($getFlakes['hidden']==0)
                       { ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?hideid=<?php echo $getFlakes['flakesID']; ?>';">Hide</span></DIV><br /><?php }
                        else
                         {  ?><span style="cursor:pointer;font-size:12px;color:#aaaaff;" onclick="location.href='opflakes.php?unhideid=<?php echo $getFlakes['flakesID']; ?>';">Unhide</span></DIV><br /><?php } 
                    }
               }                  
             
           function validInput($input)
             {
               $input = trim($input);      
               $input = htmlspecialchars($input);	
             			   
               return $input;
             }  
            function abuseSensor($a,$f)
             {
               $abuselist = array("fuck","asshole","ashole","asshol","ashol","bhonsree","bhosri","bhonsdi","bhons","bhos","basterd","bastard","chod", "chutiya","choot","chutiy","gaand");
              $count=0;
              for($i=0;$i<17;$i++)
              {
                if((stripos($a,$abuselist[$i])===false)&&(stripos($f,$abuselist[$i])===false))    
                   $count++;
              } 
              if($count==17)
                  return 1;
                else return 0;
              }  
              
              
?>
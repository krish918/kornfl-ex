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
 $conStone=mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>");  if(!conStone)
	     {
		header("location:http://kf.ahens.com/error.html");
		exit;
	     }
 	 if(!con)
	     {
		header("location:http://kf.ahens.com/error.html");
		exit;
	     }
  session_start();
  $count=0;
  $indtime = time() + 45000;
  $time = date("F j, Y | g:i a",$indtime);  
  mysql_select_db("<db-name>",$conStone);
  mysql_select_db("<db-name>",$con);
  $uid = mysql_fetch_row(mysql_query("SELECT userID FROM corn_users WHERE email = '$_SESSION[user]'",$con));
  if($_GET['val']!='')
   {
    mysql_query("INSERT INTO comments (stoneID, commenter, comment,time) VALUES ('$_SESSION[stoneid]','$uid[0]','$_GET[val]','$time')",$conStone);
    mysql_query("INSERT INTO notification (stoneID, commenter,time) VALUES('$_SESSION[stoneid]', '$uid[0]','$time')",$con);
   }
  $sql = mysql_query("SELECT * FROM comments WHERE stoneID ='$_SESSION[stoneid]' ORDER BY commentID",$conStone);
		 while($com = mysql_fetch_array($sql))
			$count++;
	if($_SESSION['stop']==0)
	{
	$b=10;			
	if($count>10) $a =$count-$b; 
	else $a =0;
	}				
   if(isset($_GET['lb'])||$_SESSION['stop']==1)
		{
			$a = 0;
			$b = $count;	
			$_SESSION['stop']=1;
		}	
		if($a>0)
			{ ?><a id="prof" style="font-size:12px;" href="javascript:loadallcomnt(0,<?php echo $count; ?>)"> Show <?php echo $a; ?> more earlier comments</a> <?php }
		$sql = mysql_query("SELECT * FROM comments WHERE stoneID ='$_SESSION[stoneid]' ORDER BY commentID LIMIT $a,$b",$conStone);
		 while($com = mysql_fetch_array($sql))
			{
			$name = mysql_fetch_row(mysql_query("SELECT userName FROM corn_users WHERE userID = '$com[commenter]'",$con));
			$pro = mysql_fetch_row(mysql_query("SELECT projected FROM profiles WHERE userID = '$com[commenter]' AND projected =1",$con));
			?><DIV style="padding:15px;border-bottom:1px solid #dddddd;"> <?php
			if($pro!=null)
			{ ?>
			   <span id="prof" style="font-weight:bold;font-size:14px;" onclick="location.href='http://kf.ahens.com/home/users/showProfile.php?userID=<?php echo $com['commenter']; ?>';"><?php echo $name[0]; ?></span> 
			<?php }
			else
				echo "<span style='font-weight:bold;font-size:14px;'>".$name[0]."</span>";
			?><br />
			<?php 
			$link_pattern = '/\b(http:\/\/)*(https:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.*[^\s][^\s]+/';
                        preg_match($link_pattern, $com['comment'], $matches);
					    $replace="<a href='$matches[0]'>$matches[0]</a>";
                       $mod_flakes=str_ireplace($matches[0], $replace, $com['comment']); 	  
			echo "<span style='font-size:14px;'>".$mod_flakes."</span><br /><span style='font-size:10px;color:#aaaaaa;'>".$com['time']."</span>"; ?>
          </DIV><?php			
	        }
   
  ?>					

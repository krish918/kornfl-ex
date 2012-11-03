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
   mysql_select_db("<db-name>",$conStone);
  mysql_select_db("<db-name>",$con);
  if($_GET['info']==1)
	{
		$count=0;
	?> <div style="color:#909090;font-size:14px;padding:7px;"> People who were influenced by this story:<hr /></div><?php
		$sql = mysql_query("SELECT readerID FROM readerdata WHERE (stoneID = '$_SESSION[stoneid]') AND (action ='i')",$conStone);
		?> <DIV style="overflow:auto;height:118px;"> <?php
		while($da = mysql_fetch_row($sql))
			{
				$pro = mysql_fetch_row(mysql_query("SELECT projected FROM profiles WHERE userID = '$da[0]' AND projected =1",$con));
				$name = mysql_fetch_row(mysql_query("SELECT userName FROM corn_users WHERE userID = '$da[0]'",$con));
				?>
				<DIV style="font-size:15px;padding:0px 4px 4px 4px;">
				<?php 
				if($pro!=null)
					{ ?>
				<span id="prof" onclick="location.href='http://kf.ahens.com/home/users/showProfile.php?userID=<?php echo $da[0]; ?>';"><?php echo $name[0]; ?> </span>
				  <?php }
				  else
					 echo $name[0]; ?>
				 </DIV><?php 
				$count++;
			}
		if($count==0)
				echo "<DIV style='padding:7px;font-size:15px;'> Nobody yet!</DIV>";
			?> </DIV><?php		
	}
    if($_GET['info']==0)
	{
		$count=0;
	  ?> <div style="color:#909090;font-size:14px;padding:7px;"> People who read this story:<hr /></div><?php
		$sql2 = mysql_query("SELECT readerID FROM readerdata WHERE (stoneID = '$_SESSION[stoneid]') AND (action ='r')",$conStone);
		?> <DIV style="overflow:auto;height:118px;"> <?php
		while($da2 = mysql_fetch_row($sql2))
			{
				$pro = mysql_fetch_row(mysql_query("SELECT projected FROM profiles WHERE userID = '$da2[0]' AND projected =1",$con));
				$name = mysql_fetch_row(mysql_query("SELECT userName FROM corn_users WHERE userID = '$da2[0]'",$con));
				?>
				<DIV style="font-size:15px;padding:0px 4px 4px 4px;">
				<?php 
				if($pro!=null)
					{ ?>
				<span id="prof" onclick="location.href='http://kf.ahens.com/home/users/showProfile.php?userID=<?php echo $da2[0]; ?>';"><?php echo $name[0]; ?></span>
				  <?php }
				  else
					 echo $name[0]; ?>
				</DIV><?php 
				$count++;
			}
		if($count==0)
			echo "<DIV style='padding:7px;font-size:15px;'> Nobody yet!</DIV>";
		?> </DIV><?php	
	}	
	?> <div style="position:absolute;bottom:0px;width:100px;padding:4px;cursor:pointer;" onclick="document.getElementById('loadspecial').style.visibility='hidden';"><hr /><span style="color:#ff8080;" id="prof">Close</span></div>
	<?php
	
?>	
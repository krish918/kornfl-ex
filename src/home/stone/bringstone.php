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
		header("location:http://www.ahens.com/error.html");
		exit;
	     }
 	 if(!con)
	     {
		header("location:http://www.ahens.com/error.html");
		exit;
	     }
  mysql_select_db("<db-name>",$conStone);
  mysql_select_db("<db-name>",$con);
  $count=0;	
  
   if(($_GET['lima']!='') && ($_GET['limb']!=''))
		{	
		$a =$_GET['lima'];
		$b =$_GET['limb'];
		  $sql =mysql_query("SELECT * FROM stone ORDER BY stoneID DESC LIMIT $a, $b",$conStone); 
		  while($st = mysql_fetch_array($sql))
			{
			 if($st['place']!='' && $st['sources']!='')
			 {
			   ?>
			     <DIV style="border-bottom:1px double #aaaaaa;padding:10px;" onmouseover="this.style.backgroundColor='#efefff';showbrief(<?php echo $st['stoneID'];?>,event);" onmouseout="this.style.backgroundColor='transparent';hidebrief();" onclick="location.href='readone.php?showstory=<?php echo $st['stoneID'];?>';">
				 <DIV style='padding:7px; cursor:pointer;font-size:14px;'>
				   <span style='font-weight:bold;'> <?php echo $st['name']; ?> </span> learns <span style='font-weight:bold;'> <?php echo $st['title']; ?> </span>
				   at <?php echo $st['place']; ?> 
				   <br />
				     <span style="font-size:13px; color:#707070;"> Source(s) : <?php echo $st['sources']; ?></span> <br /><br />
			     <DIV style="font-family:verdana; font-size: 12px; font-style:italic;color:#909090;text-align:right;">a profile from <?php echo $st['campus']; ?></DIV>
			     <DIV style="font-family:tahoma;font-size:10px;color:#bbbbbb;text-align:right;"> <?php echo $st['time']; ?> </DIV>
			     </DIV>
				 </DIV>
				 <?php
				  $count++;
				 }
				else
					{
					?>
					<DIV style="border-bottom:1px double #aaaaaa;padding:10px;" onmouseover="this.style.backgroundColor='#efefff';showbrief(<?php echo $st['stoneID'];?>,event);" onmouseout="this.style.backgroundColor='transparent';hidebrief();" onclick="location.href='readone.php?showstory=<?php echo $st['stoneID'];?>';">
					<DIV style="padding:7px;cursor:pointer;">
					 <DIV style="font-weight:bold; font-size: 14px;"> <?php echo $st['title']; ?></DIV>
					  <DIV style="font-size:14px;color:#707070;"> - authored by <?php echo $st['name']; ?> </DIV><br />
					 <DIV style="font-family:verdana; font-size: 12px; font-style:italic;color:#909090;text-align:right;">a profile from <?php echo $st['campus']; ?></DIV> 
					 <DIV style='font-family:tahoma;font-size:10px;color:#bbbbbb;text-align:right;'> <?php echo $st['time']; ?> </DIV> 
					</DIV>
					</DIV>
					<?php
					  $count++;
					  }
			      
			 } 
			if($count==0)
			{
			 if($a==0)
			   echo "<DIV style='background-color:#ffcccc;border:1px solid #ff9090;font-size:14px;padding:7px;'>Sorry, nobody has wrote on stone!</DIV><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
			else
			echo "0"; 
			}
		}
		if($_GET['search']!='' && $_GET['la']!='' && $_GET['lb']!='')
			{
			    $a = $_GET['la'];
				$b = $_GET['lb'];
				$string = $_GET['search'];
				
			    $sql2 =mysql_query("SELECT * FROM stone WHERE title LIKE '%$string%' OR name LIKE '%$string%' OR story LIKE '%$string%' OR campus LIKE '%$string%' ORDER BY stoneID DESC LIMIT $a,$b",$conStone); 
				while(($st2 = mysql_fetch_array($sql2)))
					{
					 
					/* if((stripos($st2['title'], $string)!==false)||(stripos($campName[0], $string)!==false)||(stripos($st2['name'], $string) !==false)||(stripos($st2['story'],$string)!==false))
					 {  */
					   if($st2['place']!='' && $st2['sources']!='')
						{
							?>
							<DIV style="border-bottom:1px double #aaaaaa;padding:10px;" onmouseover="this.style.backgroundColor='#efefff';showbrief(<?php echo $st2['stoneID'];?>,event);" onmouseout="this.style.backgroundColor='transparent';hidebrief();" onclick="location.href='readone.php?showstory=<?php echo $st2['stoneID'];?>';">
							<DIV style='padding:7px; cursor:pointer;font-size:14px;'>
							<span style='font-weight:bold;'> <?php echo $st2['name']; ?> </span> learns <span style='font-weight:bold;'> <?php echo $st2['title']; ?> </span>
							at <?php echo $st2['place']; ?> 
							<br />
							<span style="font-size:13px; color:#707070;"> Source(s) : <?php echo $st2['sources']; ?></span> <br /><br />
							<DIV style="font-family:verdana; font-size: 12px; font-style:italic;color:#909090;text-align:right;">a profile from <?php echo $st2['campus']; ?></DIV>
							<DIV style="font-family:tahoma;font-size:10px;color:#bbbbbb;text-align:right;"> <?php echo $st2['time']; ?> </DIV>
							</DIV>
							</DIV>
							<?php
							 $count++;
						}
					else
						{
						?>
						<DIV style="border-bottom:1px double #aaaaaa;padding:10px;" onmouseover="this.style.backgroundColor='#efefff';showbrief(<?php echo $st2['stoneID'];?>,event);" onmouseout="this.style.backgroundColor='transparent';hidebrief();" onclick="location.href='readone.php?showstory=<?php echo $st2['stoneID'];?>';">
						<DIV style="padding:7px;cursor:pointer;">
						<DIV style="font-weight:bold; font-size: 14px;"> <?php echo $st2['title']; ?></DIV>
						<DIV style="font-size:14px;color:#707070;"> - authored by <?php echo $st2['name']; ?> </DIV><br />
						<DIV style="font-family:verdana; font-size: 12px; font-style:italic;color:#909090;text-align:right;">a profile from <?php echo $st2['campus']; ?></DIV> 
						<DIV style='font-family:tahoma;font-size:10px;color:#bbbbbb;text-align:right;'> <?php echo $st2['time']; ?> </DIV> 
						</DIV>
						</DIV>
						<?php
						$count++;
						}
			      
					/* } */
					}
				  if($count==0)
					{
					 if($a!=0)
					 echo "<DIV style='background-color:#ffcccc;border:1px solid #ff9090;padding:7px;font-size:14px;'> There are no more stories for <span style='font-weight:bold;'>".stripslashes($string)."</span> at stone.</DIV><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
					 else
					 echo "<DIV style='background-color:#ffcccc;border:1px solid #ff9090;padding:7px;font-size:14px;'> There is no any story for <span style='font-weight:bold;'>".stripslashes($string)."</span> at stone.</DIV><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
					 
					} 
				   else if($count<3)
						echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";	
					
			}	
?>		
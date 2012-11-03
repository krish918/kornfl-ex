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
	$count=0;
  	 $get4 = mysql_query("SELECT * FROM mirror WHERE target='$_SESSION[id]' AND unreadFlag = 1", $con);
       while($getR4 = mysql_fetch_array($get4))
        {
        $count++;		 
		}
	 
     if($count!=0)
	    {
	        if($_GET['newreq']==1)
					{
					echo $count; exit;
					}
			else
			 {
				echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;'><span style='font-size:14px;'> You have <span style='font-weight:bold;'>". $count." unread</span> messages at your mirror.</span></DIV>";		   
			  }
		}	  
    $get =mysql_query("SELECT * FROM flakes where flakesID > '$_SESSION[lastFlakes]' ORDER BY flakesID DESC", $con);
    $getR = mysql_fetch_array($get);
    if ($getR!=NULL)
	  {
	  $_SESSION['lastFlakes']=$getR['flakesID'];
       echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;'><span style='font-size:14px;'><span style='font-weight:bold;'>".$getR['flaker']."</span> Flaked About "."<span style='font-weight:bold;'>".$getR['about']."</span></span></DIV>";
   	  }
	$get2 =mysql_query("SELECT * FROM corn_users WHERE userID > '$_SESSION[lastUser]' AND campusJoined = '$_SESSION[camp]' ORDER BY userID DESC", $con);
    $getR2 = mysql_fetch_array($get2);
    if ($getR2!=NULL)
	  {
	   $_SESSION['lastUser'] = $getR2['userID'];
       echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;'><span style='font-size:14px;'><span style='font-weight:bold;'>".$getR2['userName']."</span> joined your network </span></DIV>";
   	  } 
	$get3 =mysql_query("SELECT * FROM ratinglist WHERE rateID > '$_SESSION[newRater]' AND target = '$_SESSION[id]' ORDER BY rateID DESC", $con);
    $getR3 = mysql_fetch_array($get3);
    if ($getR3!=NULL)
	  {
	   $_SESSION['newRater'] = $getR3['rateID'];
       echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;'><span style='font-size:14px;'> Somebody just rated you! </span></DIV>";
   	  } 
	
     
	
	  // for publishing
	  $get5 = mysql_fetch_array(mysql_query("SELECT * FROM stone WHERE stoneID> '$_SESSION[stoneNotif]'",$conStone));
	   if($get5 != null)
		{
		  $_SESSION['stoneNotif'] = $get5['stoneID'];
		  $getc = mysql_fetch_row(mysql_query("SELECT campusJoined FROM corn_users WHERE userID = '$get5[userID]'",$con));
	      $campName = mysql_fetch_row(mysql_query("SELECT campusName FROM campusdata WHERE campusCode = '$getc[0]'",$con));
		  if($getc[0]!=$_SESSION['camp'])
		  echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$get5['name']."</span> from <span style='font-weight:bold;'>".$campName[0]."</span><br /> published a story on stone.</DIV>";
		  else
		  echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$get5['name']."</span> from your own campus published a story on stone.</DIV>";
		}
		
		// for story reading & writing
	   $get6 = mysql_fetch_array(mysql_query("SELECT * FROM readerdata  WHERE dataID > '$_SESSION[readNotif]'",$conStone));
		if($get6!=null)
			{
				$_SESSION['readNotif'] = $get6['dataID'];
				 $getc = mysql_fetch_row(mysql_query("SELECT campusJoined,userName FROM corn_users WHERE userID = '$get6[readerID]'",$con));
				if($getc[0]==$_SESSION['camp'])
				{
			
				if($get6['readerID']==$get6['authorID'])
				    {
					  if($get6['action']=='r')
					    echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$getc[1]."</span> read his/her own story.</DIV>";
					   else if($get6['action']=='i')
						 echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$getc[1]."</span> was influenced by his/her own story.</DIV>";
					}
				else
					{
					$getc2 = mysql_fetch_row(mysql_query("SELECT campusJoined,userName FROM corn_users WHERE userID = '$get6[authorID]'",$con));
					 $campName = mysql_fetch_row(mysql_query("SELECT campusName FROM campusdata WHERE campusCode = '$getc2[0]'",$con));
					 if($getc2[0] == $_SESSION['camp'])
							$string ="your own campus";
					else
							$string = "<span style='font-weight:bold;'>".$campName[0]."</span>";
					if($get6['authorID']==$_SESSION['id'])
							$string2 = "you.";
					else
							$string2 = "<br /><span style='font-weight:bold;'>".$getc2[1]."</span> from ".$string;
					 if($get6['action']=='r')
						echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$getc[1]."</span> read a story published by ".$string2.".</DIV>";
					 else if($get6['action']=='i')
						echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$getc[1]."</span> was influenced by a story published by ".$string2.".</DIV>";
				    }
				}	
			}
		// for commenting	
		 $get7 = mysql_fetch_array(mysql_query("SELECT * FROM comments WHERE commentID > '$_SESSION[comment]'",$conStone));		
	     if($get7!=null)   
			{
			$_SESSION['comment'] = $get7['commentID'];
			$idu =mysql_fetch_row(mysql_query("SELECT userID,campus,name from stone WHERE stoneID = '$get7[stoneID]'",$conStone));
			$commenter = mysql_fetch_row(mysql_query("SELECT userID,campusJoined,userName from corn_users WHERE userID = '$get7[commenter]'",$con));
			$commCamp = mysql_fetch_row(mysql_query("SELECT campusName from campusdata WHERE campusCode = '$commenter[1]'",$con));
			if(($get7['stoneID']!=2) ||  ($_SESSION['id']==56) || ($_SESSION['id']==220))
			{ 
			if($idu[0] == $_SESSION['id'])
				{
			  if($commenter[0]!=$_SESSION['id'])
				{
			   if($commCamp[0] == $idu[1])
				  echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$commenter[2]."</span> from your own campus commented on your story.</DIV>";	
				else
				  echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$commenter[2]."</span> from <span style='font-weight:bold;'>".$commCamp[0]."</span> <br /> commented on your story.</DIV>";		
				}
				}
			else if($commenter[1]==$_SESSION['camp'])
				{
				 if($commenter[0]!=$_SESSION['id'])
				 {
				  if($commenter[0]!=$idu[0])
				    echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$commenter[2]."</span> from your own campus commented on story of <span style='font-weight:bold;'>".$idu[2]."</span> from <span style='font-weight:bold;'>".$idu[1]."</span></DIV>";	
				  else
					echo "<DIV style='position:fixed; bottom:30px;right:10px;background-color:#ffcccc;border:solid 1px #ff9090;width:460px;text-align:center;font-family:tahoma;padding:5px;opacity:0.96;font-size:13px;'><span style='font-weight:bold;'>".$commenter[2]."</span> from your own campus commented on his/her own story.</DIV>";	 
				 }	
				} 
		      }
			}		
			   
 ?> 
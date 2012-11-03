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
  if(!$con) {
                 mail("s3_krish@yahoo.co.in", "Error", "Some error during searching. No db conn");
				die("Some error ocurred from our side. Please try again after some moment. We will soon fix it out.");
			}
	session_start();
	mysql_select_db("<db-name>", $con); 	
if($_GET["searchString"]!="")
	{
	 $str = htmlspecialchars($_GET['searchString']);
	 $str = trim($str);
     $query = "SELECT * FROM profiles WHERE ((fullName like '%$str%' OR email = '$str') AND campusCode = '$_SESSION[campus]') AND projected=1";
	 $q_exec= mysql_query($query);
	 if(mysql_num_rows($q_exec)==0)
	 { 
	    echo "<span style='color:red;'>SORRY!</span> <br /><strong>".$str."</strong> was not found at kornflex!<br /> Pease try a different name or spelling.";
		exit;
	  }
	else
     {	
       while($getname = mysql_fetch_array($q_exec))
	    {
		  ?> <a style="text-decoration:none; color:black;" href="http://kf.ahens.com/home/users/showProfile.php?userID=<?php echo $getname['userID']; ?>"> <div style="padding:4px;border-bottom: solid 1px #aaaaaa;font-size:16px;" onmouseover="this.style.backgroundColor='#e6e6e6';" onmouseout="this.style.backgroundColor='transparent';" ><strong>
		  <?php echo $getname['fullName'];?> </strong>
			<div style="color:#808080;font-size:14px;"><em><?php if($getname['homeTown']!="") { ?> from <?php echo $getname['homeTown']; } if($getname['subject']!="") { ?> &raquo; Studying <?php echo $getname['subject']; } ?></em></div></div></a>
	   <?php
	     $count++;
		 if($count>9)
		  {
			
			?> <a style="text-decoration:none;" href="http://kf.ahens.com/home/search/"><div style="padding:4px;text-align:center;font-weight:bold;font-size:13px;"> Click here to search your subject more extensively </div></a>
		  <?php break;	}
	    }
	 }
   }
 else
	echo "<span style='color:red;'>SORRY! </span> <br />Search String was not parsable by server. Please try some other query."

	?>
	
		 
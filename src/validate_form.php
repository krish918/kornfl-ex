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
      $con = mysql_connect("127.0.0.1","<db-username>","<db-password>");

  if(!$con)
    {
       header("location:http://kf.ahens.com/error/?conerror=1");
	   exit;
	}
  mysql_select_db("<db-name>",$con);
  session_start();
  $indiantime = time()+45000;
  $dateNtime=date("F d, Y | h:i a", $indiantime);
  $ip = $_SERVER['REMOTE_ADDR'];
  $ua = $_SERVER['HTTP_USER_AGENT'];	 
  
if($_GET['entry']!="" && $_GET['field']==1){
	$haystack = $_GET['entry'];
	$pattern1 = '/[^a-zA-Z\s]/';
	$pattern2 = '/\b[A-Z][a-zA-Z\s]*\b/';
	$pattern3 = '/[\sa-zA-Z]/';
	$pattern4 = '/[A-Z]/';
	$pattern5 = '/[\s]{2,}/';
	if(strlen($haystack)<4)
	  		echo 1;
		else if(preg_match($pattern1,$haystack)==1)
	  			echo 2;
			else if(preg_match($pattern2,$haystack)==0)
	  				echo 3;
				else if(preg_match($pattern3,$haystack)==0)
	  					echo 4;
					else if(preg_match($pattern5,$haystack)==1)
							echo 5;
						else if(preg_match_all($pattern4,$haystack,$matches)>4)
	  							echo 6;
	else echo 11;
}
if($_GET['entry']!="" && $_GET['field']==2){
	$haystack = $_GET['entry'];
	$pattern1 = '/\b[a-zA-Z0-9\.\_]{2,}@[a-zA-Z0-9]{2,}\.[a-zA-Z\.]+\b/';
	$check_db = mysql_fetch_row(mysql_query("SELECT * FROM corn_users WHERE Email = '$haystack'",$con)); 
	if(preg_match($pattern1,$haystack)==0)
	   echo 7;
	else if($check_db!=null)
		    echo 9;
	else echo 10;
}
if($_GET['entry']!="" && $_GET['field']==3){
	$haystack = $_GET['entry'];
	if(strlen($haystack)<6)
	  echo 8;
	else echo 12;
	
}
if($_GET['campusquery']!=""){
	$campquery = $_GET['campusquery'];
	$count=0;
	$query = mysql_query("SELECT * FROM campusdata WHERE campusName LIKE '%$campquery%' OR City LIKE '%$campquery%'",$con);
	
	while($getallcamp = mysql_fetch_array($query))
	{
		?>
		<div id='camp_contain' onclick="addCampVal('<?php echo $getallcamp[campusCode]; ?>','<?php echo $getallcamp[campusName]; ?>')">
			<?php 
	  echo "<div id='campusname'>".$getallcamp['campusName']."</div>";
	  echo "<div id='cityname'>".$getallcamp['City']." &raquo; ".$getallcamp['networkCount']." people in network</div>";
	$count++;	 ?> </div> <?php
	}
	if($count==0) echo "No such campus found. You can still register without selecting a campus.<br /><a href='javascript:addCampVal(0,0);'>Close</a>";
}

if($_GET['submitdata']==1){
	$name = validate($_GET['name']);
	$email = validate($_GET['email']);
	$pwd = $_GET['pwd'];
	$campus = $_GET['campus'];
	$gender = $_GET['gender'];
	 $checkArray = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v',
							'w','x','y','z','A','B','C','D','E','F','G','H','I','J','K',
							'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
							
	$checkString="";	
    do
		{ 
		 for($i=0; $i<32;$i++)
		  {
			$ind = mt_rand(0,41);
		    $checkString = $checkString.$checkArray[$ind];
		  } 
	    }while(mysql_num_rows(mysql_query("SELECT * FROM activationData WHERE activationString = '$checkString'"))!=0);	
	 	$time = time();
		$checkredundant = mysql_fetch_array(mysql_query("SELECT * FROM corn_users WHERE Email='$email'",$con));
		if($checkredundant!=null) { echo "Your account is already created.\nYou may login now."; exit;}
	 	$query_ins = "INSERT INTO corn_users (userName,Email, Password, gender,campusJoined,accountCreated, activation,lastLogin,lastIP,lastUserInfo,lastUpdateStat) VALUES('$name','$email','$pwd','$gender','$campus','$dateNtime','$time',0,0,0,0)";					
		if(mysql_query($query_ins,$con))
	  	{
	  	$uid= mysql_fetch_row(mysql_query("SELECT userID FROM corn_users WHERE Email = '$email'",$con));
		$link ="http://kornflex.ahens.com/login/activate/?act=1&cs=".$checkString."&uid=".$uid[0];
		$subject="Activate your Kornflex account";
		$message="Dear ".$name."!\n\n";
		$message .="This message completes your registration process on Kornflex. \nPlease click on the link";
		$message .=" to activate your account.\n\n";
		$message .= $link;
		$message .="\n\nRegards\nThe.ahens.team\n\nPlease do not reply this message.";
		$header ="From : donotreply@ahens.com";		
	  	if(mail($email,$subject,$message,$header))
		{
			mysql_query("INSERT INTO activationData (name,Email,Password,time,emailsentat,emailsentcount,activationString,used,userID) VALUES('$name','$email','$pwd','$dateNtime','$dateNtime',1,'$checkString',0,'$uid[0]')",$con);
		}
		echo "Your account on kornflex has been created. Login from lower panel.";
	  }
	else
		echo "We are experiencing some hiccups. PLease try later!";		
}

	function validate($in){
			$in = trim($in);
			$in = htmlspecialchars($in);
			return $in;
		}
?>
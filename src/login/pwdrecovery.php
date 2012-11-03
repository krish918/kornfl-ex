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
       header("location:http://kf.ahens.com/error/?conerror=1");
	   exit;
	}
  mysql_select_db("<db-name>",$con);
  session_start();
  $indiantime = time()+45000;
  $dateNtime=date("F d, Y | h:i a", $indiantime);
  $ip = $_SERVER['REMOTE_ADDR'];
  $ua = $_SERVER['HTTP_USER_AGENT'];

  if(($_SESSION['user']!="") || ($_COOKIE['user']!=""))
    {
	  $uid = mysql_fetch_row(mysql_query("SELECT userID,invitation FROM corn_users WHERE Email = '$_SESSION[user]' OR Email='$_COOKIE[user]'"));
         if($uid[1]==-1)
		    { header("location:http://kf.ahens.com/home/newkf/"); exit; }
		 else
            { header("location:http://kf.ahens.com/home/"); exit; }
	}
  $emailnotfound=0; $step=0;$password_short=0;
  if($_GET['recreq']==1)
   {
     if(mysql_num_rows(mysql_query("SELECT * FROM link_random_string WHERE (string ='$_GET[cs]' AND userID='$_GET[ri]') AND used=0"))==1)
	   { $step =2;
	     mysql_query("UPDATE link_random_string SET usedAt = '$dateNtime', used= 1 WHERE string = '$_GET[cs]'",$con);
		} 
	 else
          {  header("location:http://kf.ahens.com/error/notfound.php");	exit; }	 
	}
 if(isset($_POST['changep']))
 { if(strlen($_POST['new_pass'])<6)
      { $password_short =1; $step=2; }
	   
     else if(mysql_query("UPDATE corn_users SET Password='$_POST[new_pass]' WHERE userID = '$_POST[uid]'"))
	  {
	      $step = 3;
		  mysql_query("UPDATE link_random_string SET changedAt = '$dateNtime' WHERE string = '$_POST[sid]'",$con);
	  }	  
	 else
      	{  header("location:http://kf.ahens.com/error/?error=777&email='$inpt_email'");	exit; }	
 } 
  $inpt_email = validate_input($_POST['for_recovery_email']);

  $checkArray = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v',
							'w','x','y','z','A','B','C','D','E','F','G','H','I','J','K',
							'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
						
  $checkString="";
  if(isset($_POST['recover_pwd']))
    {
	   $query1 = mysql_query("SELECT userID,userName FROM corn_users WHERE Email = '$inpt_email'");
	   if(mysql_num_rows($query1)==0)
	     $emailnotfound=1;
		else if(mysql_num_rows($query1)==1)
            {
			   $userdetail=mysql_fetch_row($query1);
			 do
			  { 
			   for($i=0; $i<32;$i++)
			   {
			     $ind = mt_rand(0,41);
				 $checkString = $checkString.$checkArray[$ind];
				} 
			 }while(mysql_num_rows(mysql_query("SELECT * FROM link_random_string WHERE string = '$checkString'"))!=0);	
			 
               $subject = "Kornflex Password Recovery";
				$message = "Dear ".$userdetail[1]."," ."\n\n".
							"You have requested to recover your password.\nPlease follow the following link to recover your password."."\n\n\n".
							"http://kornflex.ahens.com/login/pwdrecovery.php?recreq=1&cs=".$checkString."&ri=".$userdetail[0]."\n\n\n".
							"If you have not requested to recover your password, please inform us at help@ahens.com."."\n".
							"Please don't reply this message."."\n\n".
							"The AHENS Team";
				$header = "From : donotreply@ahens.com";
				if(mysql_query("INSERT INTO link_random_string (userID,string,createdAt,used,usedAt,changedAt) VALUES('$userdetail[0]','$checkString','$dateNtime','0','-1','-1')"))
				{
					if(mail($inpt_email,$subject,$message,$header))
						{
							$step =1;
					    }	  
					else
						{ header("location:http://kf.ahens.com/error/?error=555&email='$inpt_email'");	 exit; }			
				}
				else
				    { 
					die(mysql_error());  }
			}	  
		else
           {  header("location:http://kf.ahens.com/error/?redundancyerror=1&email='$inpt_email'");	exit; }	
	}
   
   
   function validate_input($inpt)
   {
            $inpt = trim($inpt);
			$inpt = addslashes($inpt);
			$inpt = htmlspecialchars($inpt); 
			
			return $inpt;
	}	
?>
<!doctype HTML>
<html>
 <head>
   <title> Recover Your Password </title>
   <meta name="description" content='Kornflex is a web utility by AHENS, which enhances and helps your
				campus life, in easy and exclusive ways.' />   
	<meta charset="utf-8" />			
   <link rel="shortcut icon" href="../favicon.ico"/> 
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_1366.css" />
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_error_1366.css" />
   <script type="text/javascript" src="http://kf.ahens.com/script/kf_script.js"> </script>
   <script type="text/javascript">
     var width=(window.innerWidth)?window.innerWidth:document.documentElement.offsetWidth;
	 if(width<1290)
	 {
	   var head = document.getElementsByTagName('head')[0];
	   var link = document.createElement("link");
	   link.type="text/css";
	   link.rel="stylesheet";
	   link.href="http://kf.ahens.com/kornflex_design_all/kf_design_1024.css";
	   var link2 = document.createElement("link");
	   link2.type="text/css";
	   link2.rel="stylesheet";
	   link2.href="http://kf.ahens.com/kornflex_design_all/kf_design_error_1024.css";
	   head.appendChild(link);
	   head.appendChild(link2);
	 }
	   
   </script>
 </head>
 <body class="kornflex_body" id="password_recovery">
      <div class="middle_panel" id="blue_panel">
	
	
	<div class="logo_container" id="kf_logo">
	      <a href="../index.php">  <img src="http://kf.ahens.com/kf_images_all/kornflex_new_small.jpg" alt="kornflex_logo" border=0 style="margin-top:10px;margin-left:10px;" title="Go to Kornflex homepage"/>
	    </a>
	   </div> 
	   
	   <div class="ahens_copyright">
		  AHENS &copy; 2012
	   </div>
	   <div class="dotted_borders">
	   
			<div id="border_left">
				</div>
			<div id="border_right">
				</div>
			<div id="border_top">
				</div>
			<div id="border_bottom">
				</div>
		</div>
		<div class="text_container" id="text">
		   <div class="error_head" id="lost_pwd">
		      Dropped your password at a crowdy street?
		   </div>
		   <div class="error_content" id="pwd_recover">
		   <br />
		          <span style="font-size:25px;font-weight:bold;">
				    Don't panick! Get it again here in just 3 steps.
				  </span>
                    <br /><br />
					
					   
					<span id="step"> Step 1 : </span> 
				<?php	
				if($step>0)
				  { ?>
   				      <span id="completed">COMPLETED</span> <?php
 				  } 
				  else
				    { ?>
					Enter your registred email address and click on 'send mail'.<br /><br />
					<form action="pwdrecovery.php" method="post">
					<input type="text" class="std_input" name="for_recovery_email" />
					<span id="btn_aligner">
					<button type="submit" class="btn" id="pwd_rc" name="recover_pwd"> Send mail </button></span>
					</form>
					<?php } ?>
					<br />
					<span id="step"> Step 2 : </span>
					<?php if($step==1)
					  { ?> <span style="font-style:italic;">A mail has been sent to your email address.<br />
					  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					   &nbsp;&nbsp;&nbsp;
					  Goto your inbox and follow the link in the mail.</span>
					  <?php } else if($step>1)
					      { ?> <span id="completed">COMPLETED</span> <?php }
						  else { ?>
					Will show up when you complete step 1. <?php } ?><br />
					<span id="step"> Step 3 : </span> 
					<?php if($step==2) { ?> 
					   Enter a new password (carefully!) and click on 'Change'.<br /><br />
				       <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<input type="password" class="std_input" name="new_pass" />
					<input type="hidden" name="uid" value='<?php echo $_GET['ri'];?>' />
					<input type="hidden" name="sid" value='<?php echo $_GET['cs'];?>' />
					<span id="btn_aligner">
					<button type="submit" class="btn" id="pwd_rc" name="changep"> Change </button></span>
					</form><br /><br /><span style='font-size:11px;'>CAUTION : Don't refresh this page.</span>
					 <?php } else if($step>2)
					    { ?> <span id="completed">COMPLETED</span> <br />
							Life is all about losing and finding!<br />Wipe off your tears now and <a href="http://kf.ahens.com/login/">Login </a> Here.<?php }
						else { ?> 
					Will show up when you complete step 1 and 2.	<?php } ?>
					<br /><br />
					
					
					
					
			</div>
		
		</div>
	</div>
	<?php if($emailnotfound==1)
	  { ?>
	<div id="emailnotfound">
	  The Email address you entered is not associated with any account. Please verify it!
	</div>
	 <?php } 
	 if($password_short==1)
	  { ?>
	  <div id="emailnotfound">
	  Don't show your economy while typing a password. At least, six characters are required for making a password hot!
	</div>
	 <?php }
	 if($step==1)
	   { ?>
	    <div id="emailnotfound" style="color:black;">
	        Be calm now! You are a little closer!
	</div> <?php }
	  
	   else if($step==2 && $password_short!=1)
	   { ?>
	    <div id="emailnotfound" style="color:black;">
	        Nothing is permanent but change! Now, its time to change your password.
	</div> <?php }
	   else if($step==3) {
	   ?> <div id="emailnotfound" style="color:black;">
	       Woohoo! Hard work always pays. Guess what? We changed your password!
	</div> <?php } ?>
	<script type="text/javascript">
	   var errorBox = document.getElementById("emailnotfound");
	   setTimeout("hideErrorBox("+0+")", 5000);
	   function hideErrorBox(pos)
	    {
		  
		  errorBox.style.top = ""+pos+"px";
          pos=pos-1;
          if(pos==-60) {return;}
		  setTimeout("hideErrorBox("+pos+")", 1);
		}
	 
	 
	</script>
	 
 </body>
 </html>
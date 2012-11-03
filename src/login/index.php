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
   
  if(($_SESSION['user']!='')|| ($_COOKIE['user']!=''))
    {
	   $uid = mysql_fetch_row(mysql_query("SELECT userID,invitation FROM corn_users WHERE Email = '$_SESSION[user]' OR Email='$_COOKIE[user]'"));
         if($uid[1]==-1)
		    { header("location:http://kf.ahens.com/home/newkf/"); exit; }
		 else
            { header("location:http://kf.ahens.com/home/home.php"); exit; }
	}
   
  if(isset($_POST['btn_submit_login']))
  {
  $inpt_email = validate_input_email($_POST['email']);
  $inpt_pwd = validate_input_passwd($_POST['passwd']);
		
  if($inpt_email=="" || $inpt_pwd=="")
         header("location:http://kf.ahens.com/login/?emptyfield=1");
  else 
    {
	 $query1 = mysql_query("SELECT * FROM corn_users WHERE (Email like '$inpt_email' AND Password like '$inpt_pwd') AND activation!=0",$con);
	 if(mysql_num_rows($query1)==0)
	   header("location:http://kf.ahens.com/login/?authorisation_error=1");

	 else if(mysql_num_rows($query1)==1)
        {
		 $uid = mysql_fetch_row(mysql_query("SELECT userID,invitation FROM corn_users WHERE Email like '$inpt_email'"));
		 if(!mysql_query("UPDATE corn_users SET LastLogin = '$dateNtime', LoginCount = LoginCount+1 WHERE userID = '$uid[0]'",$con))
		     { header("location:http://kf.ahens.com/error/?error=333&email='$inpt_email'"); exit; }
		 if($uid[0]!=56)
		  {
		   if(!mysql_query("INSERT INTO SESSION_DATA (userID, time, ip, systemInfo) VALUES ('$uid[0]', '$dateNtime', '$ip', '$ua')",$con))
		       {  header("location:http://kf.ahens.com/error/?error=444&email='$inpt_email'"); exit; }
		  }
	     if(isset($_POST['remember_me']))
		   setcookie('user',$inpt_email, time()+60*60*24*30,'/');
		 $_SESSION['user'] = $inpt_email;
		 $_SESSION['uidx']= $uid[0];		
		 $_SESSION['time'] = time();
		 if($uid[1]==-1)
		    { header("location:http://kf.ahens.com/home/newkf/"); exit; }
		 else
            { header("location:http://kf.ahens.com/home/home.php"); exit; } 		 
		}
	 else
        header("location:http://kf.ahens.com/error/?redundancyerror=1&email='$inpt_email'");	 
	}
   }	
 function validate_input_passwd($inpt)
   {
			$inpt = addslashes($inpt);		
			return $inpt;
	}		
 function validate_input_email($inpt)
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
  <title>Login to Kornflex</title>
  <meta name="description" content='Kornflex is a web utility by AHENS, which enhances and helps your
				campus life, in easy and exclusive ways.' />   
   <meta charset="utf-8" />
     <link rel="shortcut icon" href="../favicon.ico"/> 
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_1366.css" />
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_error_1366.css" />
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_login_1366.css" />
   <script type="text/javascript" src="http://kf.ahens.com/script/kf_script.js"> </script>
       <script type="text/javascript">
    if(window.innerWidth)
       var width = window.innerWidth;
	 else 
	   var width=document.documentElement.offsetWidth;
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
		  var link3 = document.createElement("link");
		 link3.type="text/css";
		 link3.rel="stylesheet";
		 link3.href="http://kf.ahens.com/kornflex_design_all/kf_design_login_1024.css";
		 head.appendChild(link);
		 head.appendChild(link2);
		 head.appendChild(link3);
		} 
   </script> 
  </head>
  <body class="kornflex_body" id="login">
    
     <div class="middle_panel" id="login_panel">
	 
	 <div class="msg_text_detail">
           	<?php
				if($_GET['emptyfield']==1)
			{ ?> We see that you have not entered your Email address or Password or Both.<br />
			     Please enter them to be able to sucessfully signin.<?php } 
			else if($_GET['authorisation_error']==1)
			{ ?> The Email address or Password you entered was not genuine.<br />
			     Possibly, you are typing it wrong or it may not be associated with any account to kornflex.
				 Please enter again. If you are trying to register, please check homepage.
			<?php }
			else if($_GET['loginrequired']==1)
			{ ?> You need to login to see the requested page, as you are not authorised to see the page. This page is only available
                 to registered users.<?php }
			else { ?>
	   Sign into Kornflex and witness how it enhances your campus life.        <br />
       You will find so many attributes under one roof, you can hardly afford to stay behind.
       	   <?php } ?>
	 
	 </div>
	 <div class="logo_container" id="kf_logo_login">
	    <a href="../index.php">  <img src="http://kf.ahens.com/kf_images_all/kornflex_new_small.jpg" alt="kornflex_logo" title="Go to Kornflex Home Page"	 border=0 style="margin-top:10px;margin-left:10px;"/>
	    </a>
	   </div> 
	    <div class="bottom_links_container" id="login_down_link"> 
	     
		 
	      
		   <a class="std_kf_link"  href="javascript:intermed(10,'help_na')"> Help </a> &raquo;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a class="std_kf_link"   href="javascript:intermed(10, 'release_na')"> Release Statement </a> &raquo;
		   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a class="std_kf_link"   href="http://blog.ahens.com"> Blog </a> &raquo;
		   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   <a class="std_kf_link"   href="http://www.ahens.com"> AHENS </a> &raquo;
	   </div>
	   
	
	 </div>
	     <div class="upper_bar_login" id="dark_bar">
	         <div class="main_error_msg">
			<?php
				if($_GET['emptyfield']==1)
			{ ?> Please don't feel<br /> shy to write! <?php } 
			else if($_GET['authorisation_error']==1)
			{ ?> Hey, Do we really <br /> know you? <?php }
			else if($_GET['loginrequired']==1)
			{ ?> Sorry, Guests are <br /> not allowed here! <?php }
			else { ?>
			
			Resume your <br /> interest from here! <?php } ?>
			 </div>
			 <div class="cant_recall_passwd">
			 <a id="pwdrcvr_link" href="http://kf.ahens.com/login/pwdrecovery.php"> I can't recall my password!</a>
			 </div>
	 </div>
	   <form action="index.php" method="post">
	 <div class="text_container" id="sign_box_loginp">
	   <div class="inner_signbox" id="signbox_loginp">
		      Sign in here
		   </div>
		   <div class="signbox_head">&raquo; Please check the capslock key, before typing password.</div>
		   <div id="signbody_loginp">
		   Email Address &raquo;<br /><br />
		   <input type="text" class="std_input" name="email"/>
		   <br /><br /><br />
		   Password &raquo;<br /><br />
		   <input type="password" class="std_input" name="passwd" />
		   <br /><br /><br />
		   <span style="font-size:14px;">
		   <input type="checkbox" name="remember_me" CHECKED /> Keep me logged in </span>
		   
		   
		  
		   </div>
	 </div>
	 
	    <div id="btn_position">
		     <button type="submit" class="btn" name="btn_submit_login" id="btn_loginp"> Sign In </button>
		   </div>
             </form>
 

		
			 
			 <div class="behind_dialog" id="behnd_dlg"></div>
    <div class="stopper_dialog" id="help_na">
	    <div id="dialog_head"> <span style="font-size:60px;font-weight:bold;">?</span> <br />Don't feel rejected </div>
		<br />We can't serve you with the official documentations, until you get an invitation to use new Kornflex from
		       us. For help & support regarding old Kornflakes, please signin.
			   <div id="msg_destroy"></div>
	</div>
	 <div class="stopper_dialog" id="release_na">
	    <div id="dialog_head"> <span style="font-size:60px;font-weight:bold;">!</span> <br />Its ridiculous</div>
		<br />As this is a pre-release version of the new Kornflex&trade;, we can't show any release statement now.
		      It will be made available as soon as first RC of Kornflex goes in air.
			   <div id="msg_destroy2"></div>
	</div>
    	<div class="ahens_detail_login" id="verDet">
		All rights Reserverd &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; AHENS &copy; 2012
		<br />
			  <span style="font-style:italic;font-size:10px;">  Application version 2.0.0.0</span>
	</div>
  </body>
 </html> 
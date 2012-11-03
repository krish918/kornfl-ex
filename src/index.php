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
  $con= mysql_connect("127.0.0.1","<db-username>","<db-password>");
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
  if(($_SESSION['user']!='') || ($_COOKIE['user']!=''))
    {
	  $uid = mysql_fetch_row(mysql_query("SELECT userID,invitation FROM corn_users WHERE Email = '$_SESSION[user]' OR Email='$_COOKIE[user]'"));
         if($uid[1]==-1)
		    { header("location:http://kf.ahens.com/home/newkf/"); exit; }
		 else
            { header("location:http://kf.ahens.com/home/home.php"); exit; }
	}  
  if(isset($_POST['signin']))
  {
  $inpt_email = validate_input_email($_POST['email']);
  $inpt_pwd = validate_input_passwd($_POST['passwd']);
		
  if($inpt_email=="" || $inpt_pwd=="")
   {
     header("location:http://kf.ahens.com/login/?emptyfield=1"); exit; 
    }	 
  else 
    {
	 $query1 = mysql_query("SELECT * FROM corn_users WHERE (Email like '$inpt_email' AND Password like '$inpt_pwd') AND activation!=0",$con);
	 if(mysql_num_rows($query1)==0)
	  {
     	  header("location:http://kf.ahens.com/login/?authorisation_error=1");
		  exit;
	   }	  
	 else if(mysql_num_rows($query1)==1)
        {	
		 $uid = mysql_fetch_row(mysql_query("SELECT userID,invitation FROM corn_users WHERE Email='$inpt_email'"));
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
		 $_SESSION['uidx'] = $uid[0];
		 $_SESSION['time'] = time();
		 if($uid[1]==-1)
		   {
		     header("location:http://kf.ahens.com/home/newkf/");
			 exit;
		   }
          else
              {header("location:http://kf.ahens.com/home/home.php");exit;}		 		  
		
		}
	 else
	    {
        header("location:http://kf.ahens.com/error/?redundancyerror=1&email='$inpt_email'");	 
		exit;
		}	
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
 
 
   <title> Kornflex : Extend your reach inside your campus </title>
   <meta name="description" content='Kornflex is a web utility by AHENS, which enhances and helps your
				campus life, in easy and exclusive ways.' />   
	<meta charset="utf-8" />		
  
   <link rel="shortcut icon" href="favicon.ico"/> 
  
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_1366.css" />
  
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
		 head.appendChild(link);
		} 
		
   </script>   
 </head>
 <body class="kornflex_body" id="kornflex_main">
     
   
    
    <div class="middle_panel" id="blue_panel">
	  <div class="signin_system_container">
	   <div class="signin_box" id="blue_box">
	     <div style="margin-top:70px;margin-left:7px;">Sign in Here	  </div>
	   
	   </div>
	   <div class="input_box" id="your_email">
	       <form action="index.php" method="post">
		   Your Email address<div id="small_down_arrow"></div>
		    
		   
		   <br /><br />
		   <input type="text" name="email" id="inpt_email" class="std_input" />
		   
	   </div>
	   <div class="input_box" id="your_password">
	       Your Password<div id="small_down_arrow"></div><br /><br />
		   <input type="password" name="passwd" id="inpt_passwd" class="std_input" />
	   </div>
	   
	   <div class="btn_container">
	       
		   
		   <input type="checkbox" name="remember_me" value="Keep me logged in" /> 
		     <br />Keep me logged in
			 <br /><br />
		   
	      <button type="submit" name="signin" class="btn" id="signin_btn"> GO </button>
	   <br /><br />
	      <a id="recover_pass" href="http://kf.ahens.com/login/pwdrecovery.php"> Recover my password</a>
	   </div>
			</form>
			
		</div>	
		
		
		<div class="logo_container2" id="kf_logo">
	      <a href="">  <img src="http://kf.ahens.com/kf_images_all/kornflex_new_small.jpg" alt="kornflex_logo" border=0 style="margin-top:10px;margin-left:10px;" title="Reload Kornflex homepage"/>
	    </a>
	   </div> 
	   <div class="bottom_links_container">
	     
		 
	  
		   <a class="std_kf_link" id="help_link" href="javascript:intermed(10,'help_na')"> Help</a> &raquo;
		   <a class="std_kf_link" id="release_link" href="javascript:intermed(10, 'release_na')"> Release Statement </a> &raquo;
		   <a class="std_kf_link" id="blog_link" href="http://blog.ahens.com"> Blog </a> &raquo;
		   <a class="std_kf_link" id="ahens_link" href="http://www.ahens.com"> AHENS </a> &raquo;
	   </div>
	   <div class="ahens_copyright">
		  AHENS &copy; 2012
	   </div>
	    <div class="hidden_content" id="learn_more">
		   <div id="learn_more_head"> Kornflex&trade; is your live campus simulator.</div>
		   <div id="learn_more_head2"> For professionally student </div>
		   <div id="learn_more_content"> 
		    Direct yourself with all new Kornflex. The new Kornflex allows you to optimize your campus 
			activities in a very improved manner. There will be so little in your campus, which you can't find on 
			the new Kornflex. You will find almost all your courses, take them and you will find almost all your instructors.
			Follow them, and you will get assignments, assistance and almost all the lectures notes. Do them and you will find all other
			people doing them, almost all your coursemates. Collaborate with them, and you will find what's hot in the class
            and how the courses are reaching to completions. In that way, Kornflex provides you an omni directional support  
            all through your campus life. It creates a small beautiful world, inside your very own campus.
            Register now, to witness how!			
			</div>
		   
		   
	   </div>
	</div>
	<div class="upper_panel" id="orange_panel">
	   <div class="caption" id="kfTagline">
	     <span style="font-size:60px;color:white;"> Extend Your Reach </span>
		 <br />
		 <span style="font-size:22px;color:white;font-family:'Segoe UI', calibri,'Microsoft Sans Serif',sans-serif;"> 
					Connect with the trends in your campus<br /> and see what's happening around.
		 </span>
		 <br />
		 <div id="linkContainer">
		     <a class="std_link" id="collapse_link" href="javascript: collapse()">Learn More</a><span style='color:#fff;font-family:calibri;'> or Just</span> 
		     <a class="std_link" id="collapse_link2" href="javascript: slide(10)">Register</a>
		  </div>	 
	   
	   </div>
	   <div class="hidden_content2" id="we_closed">
	      <!--  <div id="hid_contnt2_head"> Sorry, We are closed! </div> 
			<div style="font-size:17px;padding-top:10px;"> The new Kornflex is in pre-release version and
			   we are not allowing new users. The new Kornflex is available on invitation basis only.
			   You may recieve invitation from AHENS to use & test new Kornflex, if you are already registerd on
			   old Kornflex. Just sign in and follow simple steps, to request for invitation.
			   
			</div>   -->
		
			<div class="register_head">Open your free account on Kornflex&trade;</div>
				<div id="register_cont_back"></div>
	   	    	<div id="register_content">
	   	    	       <div class="dummy" id="dummy_fn">Your Nice Name</div>
	   	     		   <div class="dummy" id="dummy_em">A Working Email Address </div>
	   	   			   <div class="dummy" id="dummy_pwd">A Good Password</div>
	   	    	       <div class="dummy" id="dummy_camp">Your Campus</div>
	   	    	       <div class="dummy" id="dummy_gender">Change If You Are Not Male </div>
	   	    		<input type="text" name="fullname" class="inpt_reg" id="reg_name" value="" onkeydown="clearinput('reg_name','dummy_fn'),show" onfocus="highlight('dummy_fn')" onblur="resetinput('dummy_fn','reg_name'),validate_inpt(this.value,1)" /><br />
	   	    		<input type="text" name="email_reg" class="inpt_reg" id="reg_email" value=""  onkeydown="clearinput('reg_email','dummy_em')" onfocus="highlight('dummy_em')" onblur="resetinput('dummy_em','reg_email'),validate_inpt(this.value,2)" /><br />
	   	    		<input type="password" name="pwd_reg" class="inpt_reg" id="reg_pwd" value="" onkeydown="clearinput('reg_pwd','dummy_pwd')" onfocus="highlight('dummy_pwd')" onblur="resetinput('dummy_pwd','reg_pwd'),validate_inpt(this.value,3)" /><br />
	   	    		<input type="text" name="campus" class="inpt_reg" id="reg_camp" value="" onkeydown="clearinput('reg_camp','dummy_camp'),bringcampus(this.value)" onfocus="highlight('dummy_camp')" onblur="resetinput('dummy_camp','reg_camp')" /><br />
	   	    		<input type="hidden" name="camp_code" id="hidden_camp" value="NOT" />
	   	    		<select id="select_gender" name="sel_gender">
	   	    			<option value="Male"> Male </option>
	   	    			<option value="Female">Female</option>
	   	    		</select>
	   	    		
	   	    	</div>  
	   	    	<div class="validate_msg" id="valid_msg"> </div>	
	   	    	<div id="agree_terms">By pressing register button, You agree to the all mentioned
	   	    			 <a href="http://www.ahens.com/FAQ/conditions.html">Terms & Conditions</a>
	   	    			  for using any of the ahens services.
	   	    	</div>
	   	    	    <div id="loader_submit"></div>
	   	    		<button type="button" name="reg_submit" id="kf_reg_button" onclick="submitdata()"> &raquo; </button>
	   	    	    
	   	    
	   	        <div class="campuslist" id="list_camp"></div>		
	     </div>   
	   <div class="image_container" id="kf_image_bg">
	  <img src="http://kf.ahens.com/kf_images_all/kornflex_home_image.jpg" alt="Freshmen reaching his dorm, at harvard" border=0 
	    style="margin-top:5px; margin-left:5px;"/>
	    <div class="image_caption" id="intxt"> <span style="font-size:35px;">Freshmen Induction</span><br />
								 Carnegie Mellon University, Pittsburgh <br />
							<span style="font-size:13px;font-style:italic;"> Total of 6,072 undergraduate students enrolled this fall.
						</span><br />
							
		</div>	
		<div id="imageHeader">Photo courtsey : http://cmu.edu </div> 
	</div> 
	
	  
	</div>	
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
	
	 
   <?php if($_GET['actused']=="done" && $_SESSION['checkpoint']==1)
   			{
   				$_SESSION['checkpoint']=0;
				?><div id="no_js"> Wohooo! You have successfully activated your account. Now, dive in without any worries. </div>		
   	<?php }
	?>		
	<!-- <div class="top_panel" id="white_panel"></div>	-->
    <noscript><div id="no_js"> Please enable JAVASCRIPT to experience this site properly.</div> </noscript>
 </body>
</html> 
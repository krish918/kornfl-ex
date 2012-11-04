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
 $conApp=mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>"); if(!$con)
  {
    header("location:http://kf.ahens.com/error/?conerror=1");
	exit;
  }	
   if(!$conApp)
  {
    header("location:http://kf.ahens.com/error/?conerror=1");
	exit;
  }
   mysql_select_db("apptease", $conApp);
   mysql_select_db("<db-name>", $con);
   session_start();	
   $indiantime = time()+45000;
	$dat =date("F d, Y | h:i a",$indiantime);
	
	 if($_COOKIE['user']!='' && $_SESSION['flag']!=1) 
	  {
	  	$_SESSION['flag']=1;
		$_SESSION['time']=time();
	  }	
	//logout
	
			if(isset($_POST['apptease_out']))
				{
			mysql_query("UPDATE corn_users SET lastIP = '$_SERVER[REMOTE_ADDR]', lastUserInfo = '$_SERVER[HTTP_USER_AGENT]' WHERE Email ='$_SESSION[user]'",$con);
			$current = time();
			$spent = $current - $_SESSION['time'];
			mysql_query("UPDATE corn_users SET timeSpent = timeSpent + '$spent'  WHERE Email ='$_SESSION[user]'",$con);
			mysql_query("UPDATE corn_users SET live = 0 WHERE Email = '$_SESSION[user]'", $con);
			 setcookie("user","",time()-3600,'/');
			session_destroy();
			header("location:http://kf.ahens.com/?logout=success");
			exit;
				}
	
	//Check login status
	
		if(($_SESSION['user']=='')&&($_COOKIE['user']==''))	
		{  $_SESSION['url_temp']=$_SERVER['REQUEST_URI'];
		header("location:http://kf.ahens.com/login/?loginrequired=1"); exit;} 
	$_SESSION['url_temp']='';	
		
    $sql_query1 = "SELECT * FROM corn_users WHERE Email = '$_SESSION[user]' OR Email = '$_COOKIE[user]'";
	$getUserDetail = mysql_fetch_array(mysql_query($sql_query1,$con));
	$campus = $getUserDetail['campusJoined'];
	$uid = $getUserDetail['userID'];
    $_SESSION['uidx']=$uid;
	$_SESSION['campus']=$campus;
	if($uid!=56)	
	mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);	
		//check subscription for this app
		
			$check_subscription = mysql_num_rows(mysql_query("SELECT * FROM appsubscription WHERE userID='$_SESSION[uidx]' AND appID=1",$con));
		if($check_subscription==0)
			{
				header("location:http://kf.ahens.com/home/apps/apptease/ver10/error/?nosubscription=1");
					exit;	
			}
				
     $_SESSION['shown']=0;	
	 $getUserDetail = mysql_fetch_array(mysql_query("SELECT * FROM corn_users WHERE userID='$_SESSION[uidx]'",$con));
     
	$getFinalScores=mysql_fetch_array(mysql_query("SELECT * FROM scores WHERE userID='$_SESSION[uidx]'",$conApp));
   ?>
   <!doctype HTML>
<html class="kornflex_apps">
<head>

   <title> Apptease History | <?php echo $getUserDetail['userName']; ?> </title>
   <meta type="description" content="Apptease is an app on Kornflex , which lets you prepare for standard tests like SAT, GRE, GMAT, GATE" />
   <meta charset="utf-8" />
   <link type="shortcut/icon" href="favicon.ico" />
   <link type="text/css" rel="stylesheet" href="http://kf.ahens.com/home/apps/apptease/ver10/apptease_design_1366.css" />
   <link type="text/css" rel="stylesheet" href="http://kf.ahens.com/kornflex_design_all/kf_home_1366.css" />
     <script type="text/javascript" src="http://kf.ahens.com/korn_script.js"> </script> 
     <script type="text/javascript" src="http://kf.ahens.com/script/kf_home.js"> </script>
	 <script type="text/javascript" src="http://kf.ahens.com/home/apps/apptease/ver10/apptease_script.js"> </script>
</head>

<body class="apptease" id="history" onload="loginstatus(), startNotification(), updatenotif_new(), message(), resetNotification()">
   <div class="canvas" id="history_canvas">
      <div class="history_title" id="history">
	    Score Statement
	  </div>
	  <div class="super_scores" id="super">
	   <div class="super_inner" id="in1"> Total questions seen  </div>
	      <div id="tot_q"> 
		  <?php 
		  echo $getFinalScores['totalQuestions'];
		    ?>
		  </div>
		<div class="super_divid" id="a1"></div> 
			<div id="med_detail">
			   Attempted <span style="font-size:17px;font-weight:bold;width:150px;"> 
				<?php
				  echo $getFinalScores['totalAttempt']; 
				?>
			   </span><br />
			   Correct <span style="font-size:17px;font-weight:bold;"> 
			   <?php echo $getFinalScores['totalCorrect'];
			   ?>
			   </span><br />
			   Passed <span style="font-size:17px;font-weight:bold;"> 
			   <?php echo $getFinalScores['totalPasses'];
			   ?>
			   </span><br />
			</div>		
		<div class="super_divid" id="a2"></div>	
		<div id="finalScore"> <div class="super_inner" id="in2"> Final Score </div> 
			<?php echo $getFinalScores['totalScore'];
			   ?>
		</div>
		<div id="bestSession"> <div class="super_inner" id="in2"> Best Session </div>
		<?php
		         $getBestQuery =  mysql_query("SELECT session FROM user_sessions WHERE userID = '$_SESSION[uidx]' ORDER BY score DESC, (attemptCount-correctCount) ASC, correctCount DESC,questionCount DESC, passCount DESC",$conApp);
				 $getBestSession = mysql_fetch_row($getBestQuery);	
					echo $getBestSession[0];	
		?>
		</div>
		
	  </div>
	  <div class="descript">All above data, is for all the sessions combined. So, its your aggregate data.</div>
	  <div class="session_wise_dat_link" id="sessionWiseDataCaller" onclick="bring_session(0,10)">
	     See History
	  </div>
	  <div class="canvas_body_main" id="session_data_content"></div>
   <div id="session_loader" style="padding:20px;"> </div>
   </div>
    <!-- Buttons -->
	   	  <a href="http://kf.ahens.com/home/home.php">
		<div title="Go to your home" class="back_button" id="escape" onmouseover="this.childNodes[1].style.borderRight='solid 15px #80BABE'"; 
		onmouseout ="this.childNodes[1].style.borderRight='solid 15px #18BABE';">
		  
		esc
		<div id="esc_arrow"></div>
	</div> </a>
	<form id="logout" action="" method="post">
	  <button  name="apptease_out" type="submit" class="app_logout" id="apptease_logout"
	   onmouseover="logout_app_effect()"
	   onmouseout="logout_app_effect_out()"> 
	    
	     :)
		<div class="logout_arrow"></div>
	  </button>
	  
	</form>
	<a href="http://kf.ahens.com/home/apps/apptease/ver10/"><div class="back_button" id="enter_button" onmouseover="this.childNodes[1].style.borderLeft='solid 20px #da244d';"
	onmouseout="this.childNodes[1].style.borderLeft='solid 20px #cf244d';">
      app
	  <div class="logout_arrow" id="return_arrow">
	  </div>
	</div></a>
		     
	<!-- Buttons End -->		    
		  
		   
   <div class="bg" id="glassy_bg"></div>
   <div class="bg" id="lower_bar"></div>
   <a href="index.php"><div class="textContainer" id="header_container" title="Go to app homepage"> A P P T E A S E </div></a>
     <div id="kornflex_canvas_text"> Kornflex Canvas Ver 1.0 <br /> AHENS &copy; 2012 </div>
    <div class="search_bar" id="finder">
     <input type="text" class="home_search" id="search_bar" value="Not for searching cattles and pets!" onfocus="removeVal()" onblur="bringVal()"/>
   </div>
   <div class="search_button" id="find_him" onmouseover="buttonBEffect()" onmouseout="buttonDEffect()" onclick="perform_search()"> find people
   </div>
      <div class="newmessage" id="newmessage"></div>
		<div class="newmessage" id="newchat"></div>
   
   <div class="panel" id="upper_action_panel">
      <a href="http://kf.ahens.com/home/mymirror.php"><img id="mail_icon" src="http://kf.ahens.com/kf_images_all/mail.png" border=0 /></a>  
      <img id="chat_icon" src="http://kf.ahens.com/kf_images_all/kopetestatusmessage.png" border=0 />
      
      <a href="http://kf.ahens.com/home/ahens/settings.php"><img id="settings_icon" src="http://kf.ahens.com/kf_images_all/application_x_desktop.png" border=0 title="Change your settings" /></a>
   </div>
   <div class="username"> <?php echo $getUserDetail['userName']; ?> </div>
   

   		<div class="hidden_box" id="search_box">
       		<div class="close" id="exitt" onclick="hideRes()"> X </div>
	   		<div id="search_result"></div>
	    </div>		
	    

	    
	      <div class="cover" id="cvr2"></div> 
  <div class="dialogBox" id="subscriberlist2">
      <div id="heading_subslist2"> People who answered it correct &raquo;
	       <div id="close_box_small" onclick="hideSubsList2()">X</div>
	  
	  </div>
	  <div id="subslist"></div>
	  <br />
			 <hr style="width:70%;" /> 
  </div>
   
   </div>
   
   	       <!-- code #4 notifications live -->
   <div id="notiFrame2"> </div>
	 <div id="notif"> </div>
	 <div id="notiFrame"> <div id="content"></div> <div id="more" style="text-align:center;"></div></div>
   <!-- code #4 notifications end -->
   
   
  </body>
</html>  
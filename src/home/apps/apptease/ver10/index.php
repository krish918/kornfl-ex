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
    header("location:http://kf.ahens.com/error/?conerror=1&db=cu");
	exit;
  }	
   if(!$conApp)
  {
    header("location:http://kf.ahens.com/error/?conerror=1&db=at");
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
    $_SESSION['uidx'] =$uid;
	$_SESSION['campus'] = $campus;
	if($uid!=56)	
	 mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$dat')",$con);
	
       $check_subscription = mysql_num_rows(mysql_query("SELECT * FROM appsubscription WHERE userID='$_SESSION[uidx]' AND appID=1",$con));
		if($check_subscription==0)
			{
				header("location:http://kf.ahens.com/home/apps/apptease/ver10/error/?nosubscription=1");
					exit;	
			}	
					
	$_SESSION['timer']=90;
   //CHECK THE CURRENT SESSION OF USER. IF NOT SET IT TO ZERO.	
  $new_user =0; 
  $getUserDetail = mysql_fetch_array(mysql_query("SELECT * FROM corn_users WHERE userID='$_SESSION[uidx]'",$con));
  $get_session = mysql_fetch_array(mysql_query("SELECT * FROM user_sessions WHERE userID = '$_SESSION[uidx]'",$conApp));
  $get_score = mysql_fetch_array(mysql_query("SELECT * FROM scores WHERE userID='$_SESSION[uidx]'",$conApp));
  
  if($get_session==null)
   {
     
	mysql_query("INSERT INTO user_sessions (userID, session,score,questionCount,correctCount,timestamp,passCount,attemptCount) VALUES('$_SESSION[uidx]',0,-1,-1,-1,'$dat','-1','-1')",$conApp);
   }
   if($get_score==null)
    {
	   
	  mysql_query("INSERT INTO scores (userID, totalScore, currentSession,lastTimePlayed,totalCorrect,totalQuestions,totalPasses,totalAttempt) VALUES('$_SESSION[uidx]',0,0,'$dat','0','0','0','0')",$conApp);
	  }
	if($get_score['currentSession']==0 || $get_score==null) $new_user=1;  
   ?>
   
<!doctype HTML>
<html class="kornflex_apps">
<head>
   <title> Apptease | <?php echo $getUserDetail['userName']; ?> </title>
   <meta type="description" content="Apptease is an app on Kornflex , which lets you prepare for standard tests like SAT, GRE, GMAT, GATE" />
   <meta charset="utf-8" />
   <link type="shortcut/icon" href="favicon.ico" />
   <link type="text/css" rel="stylesheet" href="http://kf.ahens.com/home/apps/apptease/ver10/apptease_design_1366.css" />
   <link type="text/css" rel="stylesheet" href="http://kf.ahens.com/kornflex_design_all/kf_home_1366.css" />
     <script type="text/javascript" src="http://kf.ahens.com/korn_script.js"></script>
     <script type="text/javascript" src="http://kf.ahens.com/script/kf_home.js"> </script>
	 <script type="text/javascript" src="http://kf.ahens.com/home/apps/apptease/ver10/apptease_script.js"> </script>
</head>

<body class="apptease" onload="loginstatus(), startNotification(), updatenotif_new(), message(),resetNotification()">
   <div class="canvas" id="apptease_canvas">
		<div class="legends_container">
		   <div id="highest_scorer"><div id="heading1"> Highest Scorer &raquo; </div>  
			<?php
				$highest_scorer = mysql_fetch_row(mysql_query("SELECT totalScore, userID FROM scores ORDER BY totalScore DESC", $conApp));
				if($highest_scorer[1]==$_SESSION['uidx'] && $highest_scorer[0]!=0)
				  echo "It's You! ";
				 else if($highest_scorer[0]!=0)
                   {
				     $name_highest_scorer = mysql_fetch_row(mysql_query("SELECT userName FROM corn_users WHERE userID = '$highest_scorer[1]'",$con));
					   echo "<div style='padding:2px;'><a id='listLink' href='http://kf.ahens.com/home/users/showProfile.php?userID=".$highest_scorer[1]."'>".$name_highest_scorer[0]."</a></div>";
					}
					else echo "Nobody yet!"; 	
			?>
		   </div>
		   <div id="my_highest_score"><div id="heading2"> My Total Score &raquo;</div> 
            <?php
			$my_score = mysql_fetch_array(mysql_query("SELECT * FROM scores WHERE userID= '$_SESSION[uidx]' ORDER BY totalScore DESC",$conApp));
			if($my_score==null || $my_score['currentSession']==0)
			  echo "You haven't played yet.";
			 else
               echo $my_score['totalScore']." | In ".$my_score['currentSession']." session.";			 
			
			?>
		   </div>
		   <div id="see_scores"><a id="applink" href="javascript:showHighScores();">  Meet the players</a> </div>
		   <div id="session_history"><a id="applink" href="history.php"> Show me my history</a> </div>
		   <div id="divider1"></div>
		</div>
		<div class="bigbutton" id="main_button" onclick="javascript:showFields()"> Start Playing </div>
		<div class="mini">
		<?php if($new_user!=0)
					{
						?>   
				<span style="font-size:15px;color:#505050;"> It seems, you are playing for the first time. So let us be a little easy on you.</span><br /> 
				<?php } 
		    else { ?> 
				You already know how to play. <?php } ?> 
					Choose your area of interest and we will throw random questions for 90 odd seconds. Till
					then no escape. And rewards? Well, we give 3 
					for each smart answer and take 1 for nonsense guesses. Simple! Isn't it? Go on.
		</div>

		<div class="layer2" id="choose_field">
		  <div id="dotted_border"> 
		   		<div id="layer2_heading_text">
		      		With which of the following beauties, you want to spend your next 90 seconds?	
		      		<div id="layer2_arrow_down"></div>
		      		<div id="layer2_arrow_down_outline"></div>
				</div>
				<div id="layer2_maintext">
			  		<div class="set_q" id="civil_set_q" onclick="noEscape(-100),getQuestion('civilE',-2,'','')">Civil Engineering</div>
			    	<div class="set_q" id="gen_set_q" onclick="noEscape(-100),getQuestion('GI',-2,'','')"><br /><br />General Intelligence</div>
			  		<div class="set_q" id="facts_set_q" onclick="noEscape(-100),getQuestion('GK',-2,'','')"> <br /><br />General Facts </div>
			 		<div class="set_q" id="comp_set_q"><br /><br />Computer Science</div> 
					<div class="set_q" id="mech_set_q"><br /><br />Mechanical Engineering</div> 
					<?php if($_SESSION['uidx']==220 || $_SESSION['uidx']==56)
					        {
					        	?>
					        	<div class="set_q" style="position:absolute;color:green; bottom:5px;left:5px;border:none;" onclick="noEscape(-100),getQuestion('snh',-2,'','')"> <span id="linktext">For my sweetie</span> </div> 
					       <?php }  ?>
			        <div class="set_q" id="non_set_q" onclick="showLast()"> <span id="linktext">No Thanks! </span></div>
			 
			    </div>   
		  </div>
		</div>
		<div class="drawing_board_behind" id="main_draw_board_behind"></div>
		 <div class="drawing_board" id="main_draw_board">
		   <div class="great_timer_outer" id="great_time"> 
				<div class="the_great_timer">
						<div id="receding_bg">
							<div id="receding_bg_arrow"></div>
							<div id="time_count"></div>
			
						</div>
				</div>
		   </div>
		  
		 	<div class="question_design" id="question_bg">
		 </div>
		  <div id="please_wait"></div>
		   <div id="display_app"></div>
	
		   <div class="timer" id="apptease_timer">
		    
		   </div>
		 </div>
		 	 	 <div id="temp_draw_board"> 
  		 </div> 
  		 	<div id="temp_draw_board2">
  		 </div>
	
		  <div class="session_end" id="end">
		     <div id="end_exclam_mark"> ! </div>
		    <div id="end_head">Oo-Oaaaaw</div><br />
			<div id="end_med">Time's up sweetheart!</div><br /><br />
			<div id="end_last"> Sit back and relax now. <br />We are doing something, which you won't like to know.
			</div><br /><br /><br /><br />
			<div id="loader"></div>
		 </div>
		 
   </div>	
   <div class="bg" id="glassy_bg"></div>
   <div class="bg" id="lower_bar"></div>
   <div class="textContainer" id="header_container"> A P P T E A S E</div>
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
  <div class="username" title="Have a tour of your upcoming Profile!">
  	   <a id="userLink" href="http://kf.ahens.com/home/newkf/index.php"><?php echo $getUserDetail['userName']; ?></a>
  </div>
 
   
 
   
   
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
	
	<!--  code#6 NO ESCAPE -->
	<div class="no_escape" id="no_escape_upper">
	   <div id="inner_escape"> We did this to stop you from running away! </div>
	   <div id="inner_esc2"> Now, concentrate on your problems. </div>
	   <div id="inner_esc3"> Well, let us elaborate this one. Suppose you are in middle of a challenging
				problem and some dude out there messages you!<br /> Nonsense distraction & interruptions!
				We provide none. :) 
		</div>		
	</div>
   <div class="no_escape" id="no_escape_left" onclick="showNoEsc(this)">
	   
	</div>
	<div class="no_escape" id="no_escape_right" onclick="showNoEsc2(this)">
	   
	</div>
	     <div class="hidden_box" id="search_box">
       			<div class="close" id="exitt" onclick="hideRes()"> X </div>
	   			<div id="search_result"></div>
   </div>
	<!--  code#6 ends -->
  <div class="cover" id="cvr2"></div> 
   
     <!-- CODE #7 SHOW ALL HIGHEST SCORES -->
  
  <div class="dialogBox" id="subscriberlist2">
      <div id="heading_subslist2"> All Players &raquo;
	       <div id="close_box_small" onclick="hideSubsList2()">X</div>
	  
	  </div>
	  <div id="subslist"></div>
	  <br />
			 <hr style="width:70%;" /> 
  </div>
  
     <!-- code #7 ends -->
  

  
     <!-- code#8 score card -->
   <div class="score" id="scorecard">
		   <div id="score_head"> Mini Score Card <div id="miniText"> Don't Swipe! </div></div> 
		   <div id="scoreHeadText"> Oh, Comeon! We won't show to your mommy, happy!</div>
		   <div id="totalScoreBox"> <div id="innerHeadScoreBox"> Score </div> 
					<div id="score_container"></div>
		   
		   </div>
		   <div id="score_divider"></div>
		   <div class="small_box_score" id="questions">
		     <div id="in_small_box_score"> 
			     Total Questions 
				
			 </div>
			  <div id="res_container"></div>
		   </div>
		   <div class="small_box_score" id="attempt">
		     <div id="in_small_box_score"> 
			     Total Attempts 
				
			 </div>
			  <div id="res_container1"></div>
		   </div>
		   <div class="small_box_score" id="correct">
		     <div id="in_small_box_score"> 
			     Total Correct
				
			 </div>
			  <div id="res_container2"></div>
		   </div>
		   <div class="small_box_score" id="passed">
		     <div id="in_small_box_score"> 
			     Hand's Up
				
			 </div>
			  <div id="res_container3"></div>
		   </div>
		   <div class="history_button"><a id="history_link" href="history.php"> 
									Click here to get detailed score statement. </a>
			</div>
		   <div id="big_close_button"> <a id="big_close_link" href="">X</a> </div>
		   <div id="session_no_disp"></div>
    </div>
 
    <!-- code #8 ends -->
         
         <!-- code #4 notifications live -->
   <div id="notiFrame2"> </div>
	 <div id="notif"> </div>
	 <div id="notiFrame"> <div id="content"></div> <div id="more" style="text-align:center;"></div></div>
   <!-- code #4 notifications end -->
<!--<div id="debug" style="position:absolute;top:120px;left:0px;font-size:11px;color:#452788;padding:3px;width:330px;border:solid 1px #676767;visibility:hidden;">
	
 
	
</div>-->


</body> 
</html>   
  
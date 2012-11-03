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
	session_start();
    mysql_select_db("<db-name>",$con);
	 $indtime = time() + 45000;
	  $time = date("F j, Y | g:i a",$indtime);
	if(($_SESSION['user']=='')&&($_COOKIE['user']==""))	
	 {  header("location:http://kf.ahens.com/login/?loginrequired=1"); exit;}
	$uid=mysql_fetch_row(mysql_query("SELECT userID From corn_users WHERE Email = '$_SESSION[user]' OR Email='$_COOKIE[user]'")); 
	 if($uid[0]!=56)	
	 mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid[0]','$_SERVER[REQUEST_URI]','$time')",$con);
	if(isset($_GET['choice_invite_submit']))
	{
	  if($_GET['invite']=="nothanks")
	   mysql_query("UPDATE corn_users SET invitation=0 WHERE userID='$uid[0]'");
	   else
	      mysql_query("UPDATE corn_users SET invitation=1 WHERE userID='$uid[0]'");
	   header("location:http://kf.ahens.com/home/home.php"); exit;	  
	}
	$getInvitationStatus = mysql_fetch_row(mysql_query("SELECT invitation FROM corn_users WHERE userID='$_SESSION[uidx]'",$con));
?>

<!doctype HTML>

<html>
  <head>
   <title> Welcome to new Kornflex </title>
    <meta name="description" content='Kornflex is a web utility by AHENS, which enhances and helps your
				campus life, in easy and exclusive ways.' />   
	<meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_1366.css" />
    <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_newkf_1366.css" />
    <link rel="shortcut icon" href="http://kf.ahens.com/favicon.ico" />
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
		link2.href="http://kf.ahens.com/kornflex_design_all/kf_design_newkf_1024.css";
		head.appendChild(link);
		head.appendChild(link2);
	
		
	  }
	
	</script>
   </head>
   <body class="kornflex_body" id="kf_profile">
      <div class="kf_middle_panel" id="pro_second_bar">
	       <div class="kf_operations_panel" id="pro_op_panel">
		      <ul type="circle" class="bullets" id="blt1"> <li> Message Julia </li> </ul>
			  <ul type="circle" class="bullets" id="blt2"> <li> See Julia's achievements </li> </ul>
			  <ul type="circle" class="bullets" id="blt3"> <li> Collaborate with Julia </li> </ul>
			  <ul type="circle" class="bullets" id="blt4"> <li> Create joins with Julia </li> </ul>
	       </div>
		   
		   <div class="kf_std_panel" id="name_detail">
		       <div style="font-size:40px;"> Julia Wojceichowska</div>
			   <div id="subject_detail"> Mastering Physics </div>
			   <div id="university_detail"> at University of Warsaw </div><br />
			   <div style="font-size:14px;color:blue;"> Detailed Bio </div>
			</div>
	    
	 </div>
     <div class="kf_upper_panel" id="pro_first_bar">
	        <div class="kf_dp_container" id="pro_dp">
			 <img src="http://kf.ahens.com/kf_images_all/dp.jpg" style="margin-left:4px; margin-top:4px;" border=0 />
	        </div>
	    
	 </div>
	
	 <div class="kf_up_small_panel" id="pro_notif_left_pane">
	    &raquo;Prof. Samual richard has submitted 2 new Assignments.<br />
		&raquo;You have only 2 days left to finish PH91 Assignment.
		 <div id="all_alerts_left"> 
		   <div id="down_arrow_alert">
		   </div>
		   </div>
	 </div>
	 <div class="kf_up_small_panel" id="pro_chat_right_pane">
	        <div id="all_alerts_right">
			  
			    		   <div id="down_arrow_alert">
							</div>
			</div>
			 <div style="margin-left:30px;">
			 Chat is not available. First connect to any joins. You can chat only with people in your joins.
			 </div>
	 </div>
	
	<div class="profile_main_attributes" id="pro_attrib">
	 <div class="kf_inactive_tab" id="worklog_tab">
	      Worklog
	  </div>
	  <div class="kf_inactive_tab" id="flexbook_tab">
	      Flexbook
	  </div>
	  <div class="kf_inactive_tab" id="joins_tab">
	      Joins
	  </div>
	  <div class="kf_inactive_tab" id="resources_tab">
	     Resources
	  </div>
	 <div class="attrib_content" id="desk">
	     <div id="inactivity_info">
		    Julia is inactive for last 3 days!
		 </div>
		 <div class="desk_cont" id="desk_items">
		     <div id="serial_no_block"> 797 </div>
			 <div id="text_line"> Julia attended HU 9.02 special lectures at L09. </div>
			 <div id="date_time">Mentioned on August 18, 2012 at 5:00 PM </div>
		 </div>
		 <div class="desk_cont" id="desk_items">
		     <div id="serial_no_block"> 796 </div>
			 <div id="text_line"> Julia attended a seminar on 'Future Of Material Science' at Priceton University.</div>
			 <div id="date_time">Mentioned on August 03, 2012 at 11:28 PM </div>
		 </div>
		 <div class="desk_cont" id="desk_items">
		     <div id="serial_no_block"> 795 </div>
			 <div id="text_line"> Julia finished an assignment on 'Polarization'.</div>
			 <div id="date_time">Mentioned on August 01, 2012 at 7:08 PM </div>
		 </div>
		 <div class="desk_cont" id="desk_items">
		     <div id="serial_no_block"> 794 </div>
			 <div id="text_line"> Julia wrote in Flexbook, "Is such a huge funding for LHC is worth our money and ti..."</div>
			 <div id="date_time">Mentioned on July 15, 2012 at 11:56 PM </div>
		 </div>
		 <div class="desk_cont" id="desk_items">
		     <div id="serial_no_block"> 793 </div>
			 <div id="text_line"> Julia participated in Flexmeet by Dr. Sean Pitterson.</div>
			 <div id="date_time">Mentioned on July 7, 2012 at 08:16 PM </div>
		 </div>
		 <div id="expand_more"> Expand Desk 
		    <div id="desk_down_arrow"></div>
		 </div>
		 
		 
		 
		 
	 
	 </div>
	  <div class="kf_active_tab" id="desk_tab">
	      Julia's Desk
	 </div>
	 
	</div> 
	 
	 <div class="kf_long_panel" id="instructors_pane">
	      <div id="head_pane_in"> Julia's Instructors &raquo;</div>
		   <div class="usr_instructor_content">
				<div class="instructor_pic"> 
				  <img src="http://kf.ahens.com/kf_images_all/inst1.jpg" style="margin-left:0px; margin-top:2px;" border=0 />
				</div>
				<div class="instructor_pic">
					<img src="http://kf.ahens.com/kf_images_all/inst2.jpg" style="margin-left:0px; margin-top:2px;" border=0 />
				</div> 
				<div class="instructor_pic"> 
				    <img src="http://kf.ahens.com/kf_images_all/inst3.jpg" style="margin-left:0px; margin-top:2px;" border=0 />
				</div>
				<br /> 
				See all 7 Instructors
		  </div>
		  <div class="usr_instructor_detail" id="inst1">
		          Warren Kiala <br />
				  <span style="font-size:13px; color:#aaaaaa;"> Assistant Professor </span>
		  </div>
		  <div class="usr_instructor_detail" id="inst2">
		          Aaron Brown <br />
				  <span style="font-size:13px; color:#aaaaaa;"> Senior Lecturer </span>
		  </div>
		  <div class="usr_instructor_detail" id="inst3">
		          Laura Sorkin <br />
				  <span style="font-size:13px; color:#aaaaaa;"> Teaching Assistant </span>
		  </div>
	 </div>
	 <div class="kf_long_panel" id="courses_pane">
	       <div id="head_pane_in"> Julia's Courses &raquo;</div>
		    <div class="usr_instructor_content">
				<div class="instructor_pic" id="course_code"> MA 91</div>
				<div class="instructor_pic" id="course_code"> CY 102</div> 
				<div class="instructor_pic" id="course_code"> PH 70 </div>
				<br /> 
				See all 5 Courses
		  </div>
		   <div class="usr_instructor_detail" id="inst1">
		          Multi-variable Calculus
		  </div>
		  <div class="usr_instructor_detail" id="inst2">
		          Material science and its application <br />
				   
		  </div>
		  <div class="usr_instructor_detail" id="inst3">
		          Electromagnetism <br />
				   
		  </div>
		  
		   
	 </div>
	 <div class="kf_divider" id="links_divider">
	 </div>
	 <div class="kf_std_panel" id="account_links">
	   <ul type="square"> <li> David Jones </li></ul><div id="pro_small_down_arrow"></div>
	 </div>
	 <div class="kf_std_panel" id="find_people">
	     <form action="" method="get">
		    <input type="text" class="std_input" id="fp_change" name="find_people_inpt" value="Coursemates, instructors or joins"/>
			<button type="submit" class="btn" id="fp_change2" name="find_people_btn"> Find </button>
		  </form>	
	 </div>
	 
	 <div class="header_links" id="header_links_left">
	         Orangestream <br /><br /> Profile<br /> <br /> Joins<br /> <br /> Flexbook
	 </div>
	 <div class="header_links" id="header_links_right">
	          Assignments <br /><br /> Class<br /> <br />Lecturenotes<br /> <br />Flexmeets
	 </div>
	 <div class="side_info"> 1,294 other people on Kornflex have taken Physics as their majoring subject.
	 <br /><br /><span style="color:blue;font-size:13px;">Learn More</span>
	 </div> 
     <div class="endLine"> 
     <div class="logo_cntn"> 
	    <a href="../index.php">  <img src="http://kf.ahens.com/kf_images_all/kornflex_new_small.jpg" alt="kornflex_logo" border=0 style="margin-top:10px;margin-left:10px;" title="Kornflex"/></a>
	 </div>	
      <div class="endlink_home">	Testers &raquo; Developers &raquo; Engine &raquo; Report &raquo; Status &raquo; Support &raquo; Blog &raquo; AHENS &raquo;</div> 
	  <div class="ahns_cntnr" id="ahens_copyright_home">
		       AHENS &copy; 2012 | All rights reserved
			   <br />
			  <span style="font-style:italic;font-size:12px;">  Application version 2.0.0.0</span>
		   </div>
	 </div>
	  <div class="cover_home" id="hideall">
	 </div>
    <?php if($getInvitationStatus[0]==-1)
	  {
	  	?>
	   
	
	 <div class="head_msg">
	     <div class="curved_side" id="side_declare">
		   So, <br />this is <br />it!
	     </div>
		 <div id="msg_content">
		    <div id="contnt_head">
			 Right behind this window, you are watching The New Kornflex&trade;
			 </div>
			 <div id="msg_main_contnt">
			   This is a generic profile, on new Kornflex, as seen from an alien account. Unfortunately and very sadly,
			   you can not use new Kornflex, right now. The new Kornflex is open for only a very few amount of
			   people. You may get a chance to review and use the new Kornflex, if you get an invitation from
			   AHENS to use Kornflex. Exercise choices below, to do so.
			  </div>
			  <div id="choice_form">
			     <form action="" method="get">
				  <input type="radio" name="invite" value="nothanks" CHECKED /> No thanks! Don't invite me. I am patient enough to wait for stable release.
				  <br />
				  <input type="radio" name="invite" value="invt"  /> Please invite me to be an elite & impatient user of the new Kornflex&trade;! 
				  <br />
				  <div id="choice_footer" > Please note that, while we can't predict the Release Candidate, the maximum time
				    to receieve invitation(if any) will not be more than 45 days!</div> 
				  <button type="submit" class="btn" id="btn_invite" name="choice_invite_submit"> To the new Home &raquo; </button>
				 </form> 
			  </div>	  
			  
			  </div>
		 
		 </div>
		 <?php } ?>
	 
   </body>
</html>       
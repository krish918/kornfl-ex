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
	if($_GET['limit_a']!="" && $_GET['limit_b']!="")
	 {
	     $a = $_GET['limit_a']; $b=$_GET['limit_b'];
		 $c = $a + $b;
		 $count=0; $flag=0;
	     $query1 = mysql_query("SELECT * FROM user_sessions WHERE userID = '$_SESSION[uidx]' AND session<>0 ORDER BY session DESC LIMIT $a,$b",$conApp);
		$num_row = mysql_num_rows(mysql_query("SELECT * FROM user_sessions WHERE userID = '$_SESSION[uidx]'",$conApp));
		
		while($getSessionWise = mysql_fetch_array($query1))
		 {
		   $flag=1;$count++; $_SESSION['shown']=0;
           ?> <div class="session_content" id="session_wise_detail" onclick="load_deep_detail('question_wise_data<?php echo $getSessionWise['recordID'];?>',<?php echo $getSessionWise['session']; ?>)"> <?php
			
			  echo "<div id='session_no'>Session ".$getSessionWise['session']."</div>";
			    
			  echo "<div id='session_field'>".$getSessionWise['sessionField']."</div>";  
			
			  echo "<div id='date'>".$getSessionWise['timestamp']."</div>";  
			 
			  echo "<div class='minor'><div id='miner_in'>Score &raquo; <span style='font-weight:bold;'>".$getSessionWise['score']."</span></div>";  
			 
			 
			  echo "<div id='miner_in'>Questions &raquo; <span style='font-weight:bold;'>".$getSessionWise['questionCount']."</span></div>"; 
		 
			 
			  echo "<div  id='miner_in'>Attempts &raquo; <span style='font-weight:bold;'>".$getSessionWise['attemptCount']."</span></div>";  
			 
			  
			  echo "<div id='miner_in'>Correct &raquo; <span style='font-weight:bold;'>".$getSessionWise['correctCount']."</span></div>";  
			 
			  
			  echo "<div  id='miner_in'>Unattempted &raquo; <span style='font-weight:bold;'>".$getSessionWise['passCount']."</span></div></div>";  
		      
			  
			  ?> </div><div class="collapsable" id="question_wise_data<?php echo $getSessionWise['recordID']; ?>"></div></div><?php
			 
          }	
		 
		if($count>0)
          {
			 
		  ?>
			<div class="showMoreItem" id="showMoreSession" onclick="bring_session(<?php echo $c; ?>,10)"><div id="push_down">  </div> Show More </div>
			
			<?php
			if($a>=10)
			  {
			  ?>
			  <div class="showMin" id="showMinSession" onclick="redraw_canvas()"><div id="push_up"></div>Show Minimum</div>
			  <?php
			  }
			}
		if($num_row==1)
			echo "<br /><div style='font-size:20px;font-weight:bold;font-style:italic;color:#d46a4f;'>You need to first play at least one session for this!</div>";  
		else if($count==0 && $_SESSION['shown']==0)
		   {
		    $_SESSION['shown']=1;
			echo "<br /><div style='font-size:20px;font-weight:bold;font-style:italic;color:#d46a4f;'>No more sessions present.</div>";
			}
	
	 }
?>
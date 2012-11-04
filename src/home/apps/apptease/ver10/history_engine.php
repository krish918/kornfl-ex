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
  if($_GET['sessionx']!="")
   {
         $query1 = mysql_query("SELECT * FROM user_attempt WHERE userID='$_SESSION[uidx]' AND session='$_GET[sessionx]' ORDER BY recordID DESC",$conApp);
		 if(mysql_num_rows($query1)==0) { echo "<strong>Sorry!</strong><br />No questions in this session!";exit;}
		 while($getRecord = mysql_fetch_array($query1))
		  {
		    $choiceString = "choice".$getRecord['answer'];
		    $getQuestionBase = mysql_fetch_array(mysql_query("SELECT * FROM questionbase WHERE questionID = '$getRecord[questionID]'",$conApp));
		   ?><div id="q_wise_detail_container"> <?php
		    echo "<div id='session_wise_question'><span id='little_q_style'>Q. </span>".$getQuestionBase['question']."</div>";
			?> <div id="your_answer"> <?php
			if($getRecord['correct']==0)
			    echo "<span style='color:#A82312;font-weight:normal;font-size:16px;'>You didn't answer it!</span>";
				else echo "<span id='answer_text'>Your Answer :</span> ".$getQuestionBase[$choiceString];
				?> </div>
			<div id="ans_check"> 
				<?php
					if($getRecord['correct']==3)
					    echo "<img src='http://kf.ahens.com/kf_images_all/tick.gif' />";
					  else if($getRecord['correct']==-1)
					      echo "<img src='http://kf.ahens.com/kf_images_all/wrong.gif' />";
						else
							echo "<img src='http://kf.ahens.com/kf_images_all/pass.gif' />";
				?></div>
				
				<?php
				if($getRecord['correct']==0 || $getRecord['correct']==-1)
			      {
   				    echo "<div id='correct_answer'> <span id='answer_text'>Correct Answer : </span>".$getQuestionBase['choice5']."</div>";
				  } ?>
				  <div id="divid_q_data1"></div>
				  <div class="diff_level" id="diff"> Difficulty Level </div> <div id="dif_disp">
				  <?php echo $getQuestionBase['difficultyLevel']; ?></div>
				  <div class="diff_level" id="att"> Total Attempts </div>
				     <div id="attempt_disp">
						<?php echo $getQuestionBase['totalAttempt']; ?></div>
				   <div class="diff_level" id="corr"> Total Surrenders </div>
				      <div id="correct_disp">
					        <?php echo $getQuestionBase['totalPasses']; ?> </div>
					 <div id="divid_q_data2"></div>	
						<div id="tts"> TIME TO SOLVE </div> 
						  <div id="tts_disp"> 
						      <?php if($getRecord['timeTaken']>0)
							         echo $getRecord['timeTaken']." <span style='font-size:15px;'>Sec.</span>";
									 else
									  echo "NA";
									  ?>  </div>
						<div id="tts_avg"> AVERAGE TIME BY <br />NETWORK </div>	
                              <div id="tts_avg_disp">
								<?php
								    if($getQuestionBase['TTS']!=0)
									   {
									    $avg = $getQuestionBase['TTS']/$getQuestionBase['totalAttempt'];
										echo sprintf("%.2f Sec.",$avg);
										}
									else echo "NA";
									?></div>
						<div id="solvers">
						 <?php
						     $query ="SELECT DISTINCT userID FROM user_attempt WHERE (correct = 3 AND questionID = '$getRecord[questionID]') AND userID<>'$_SESSION[uidx]'";
						     $getDistinctPlayers = mysql_num_rows(mysql_query($query,$conApp));
							  if($getDistinctPlayers!=0)
							  {
							 echo "Total of <span style='font-size:30px;font-weight:bold;'>".$getDistinctPlayers." </span><br />person answered this question correctly.";
							
							    ?> <a href="javascript:showCorrectPeople(<?php echo $getRecord[questionID]; ?>)"> Know more </a> about them.
							    <?php }
							else
								{ if($getRecord['correct']==3) echo " <strong> Woohoo!</strong><br /> It seems nobody except you knew the answer of this question.";
									else   echo "<strong>Don't worry!</strong> <br />If you couldn't get through. Smarter men than you have tried & failed!";
								}		 	
						  ?>
						</div>
						
						<div id="vert_div"></div>
						<?php
							if($getQuestionBase['description']!="" && $getQuestionBase['description']!=" ")
							{
							  ?><div id="desc"> <div id="b_head">
										Briefings Available &raquo;</div> 
										<div id="brief_text">
										<?php echo $getQuestionBase['description']; ?>
										</div>
								</div>
							<?php
							}
						  $sql_check_again= mysql_query("SELECT * FROM user_attempt WHERE (userID='$_SESSION[uidx]' AND questionID='$getRecord[questionID]') AND session<>'$_GET[sessionx]'",$conApp);
						  if(mysql_num_rows($sql_check_again)!=0) 
						  { ?> <div id="duplic_q"> <?php
							echo "You encountered this question also in session &raquo; ";	
							while($getagain = mysql_fetch_array($sql_check_again))
							{
							  if($getagain['correct']==-1)
								echo "<span style='color:#ff5679;'>".$getagain['session']."</span> | ";
							  else if($getagain['correct']==3)
								echo "<span style='color:#56cf79;'>".$getagain['session']."</span> | ";	
								else
									echo "<span style='color:#F6D457;'>".$getagain['session']."</span> | ";	
							}
							?> </div><?php
						  }	
						?>	
				      <br /></div><br /><?php
			 
		  }
   
   }
   if($_GET['showCorrectUsers']==1 && $_GET['questionID']!="")
     {
	    $query2 ="SELECT DISTINCT userID FROM user_attempt WHERE (correct = 3 AND questionID = '$_GET[questionID]') AND userID<>'$_SESSION[uidx]'";
         $exec_q = mysql_query($query2,$conApp);
	   while($getUserData = mysql_fetch_row($exec_q))
		{
		    $getTime = mysql_fetch_row(mysql_query("SELECT session, timestamp FROM user_attempt WHERE (userID = '$getUserData[0]' AND correct = 3) AND questionID = '$_GET[questionID]' ORDER BY recordID DESC",$conApp));
			$getName = mysql_fetch_row(mysql_query("SELECT userName,campusJoined FROM corn_users WHERE userID = '$getUserData[0]'",$con));
			$getCampus = mysql_fetch_row(mysql_query("SELECT campusName FROM campusdata WHERE campusCode = '$getName[1]'",$con));
			$getScore = mysql_fetch_row(mysql_query("SELECT totalScore FROM scores WHERE userID = '$getUserData[0]'",$conApp));
			?>
			<a id="listLink" href="http://kf.ahens.com/home/users/showProfile.php?userID=<?php echo $getUserData[0]; ?>"><div class="correct_data_list" id="correct_data">
			  <div class="username_correct" id="un_corr">
			   <?php
			    echo $getName[0];
				?>
			  </div>
              <div class="session_detail_correct" id="s_d_c">
                 <?php echo "Answered latest in session ".$getTime[0].", on ".$getTime[1]; ?>
			  </div>
              <div class="campus_name_correct" id="c_n_c">
                 <?php echo $getCampus[0]; ?>
			  </div>	
			  <div class="score_correct" id="c_c">
					<span style="font-size:11px;font-weight:normal;color:#808080;">Score &raquo;</span><br />	<?php echo $getScore[0]; ?>
			  </div>			
			</div>	</a>
			<?php
		}
	 }
   if($_GET['getScores']==1)
		{
			 
			$getScores = mysql_query("SELECT * FROM scores WHERE userID<>'$_SESSION[uidx]' ORDER BY totalScore DESC",$conApp);
            if(mysql_num_rows($getScores)==0) { echo "<div style='padding:5px;'>No players found! </div>"; exit; }
            while($scoreMatrix = mysql_fetch_array($getScores))
              { 
			    $getScorerName = mysql_fetch_row(mysql_query("SELECT userName,campusJoined FROM corn_users WHERE userID = '$scoreMatrix[userID]'",$con));
				$getCampus = mysql_fetch_row(mysql_query("SELECT campusName FROM campusdata WHERE campusCode = '$getScorerName[1]'",$con));
				?>
				
				<a id="listLink" href="http://kf.ahens.com/home/users/showProfile.php?userID=<?php echo $scoreMatrix['userID']; ?>">
 				<div class="high_score_container" id="score_contain">
					<div class="highest_score"> <div id="score_text">Score </div> <?php echo $scoreMatrix['totalScore']; ?> </div>
						<div id="score_divider_popup"></div>
					<div id="name_plate"> <?php echo $getScorerName[0]; ?> </div>
						<div id="extra_info"> 
						 <?php echo "<span style='font-weight:bold;'> Sessions &raquo;</span> ".$scoreMatrix['currentSession']. " &nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									if($scoreMatrix['currentSession']==0) echo "Not played yet!"; else echo "<span style='font-weight:bold;'>Last Played &raquo; </span>" .$scoreMatrix['lastTimePlayed']; ?>
						</div>
						<div id="college_name"> 	
	                  <?php echo $getCampus[0]; ?>			
						</div>
				 </div></a>

				<?php
				 
				}
		}		
				
?>
			
   

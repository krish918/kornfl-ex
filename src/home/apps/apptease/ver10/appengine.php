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
 $conApp=mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>");
 if(!$con)
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
   $datwithsecond= date("F d, Y | H:i:s",$indiantime);
   $random_question=0;
  
   $fieldOfQuestion = array (
   						"compSc" => "Computer Science",
   						"civilE" => "Civil Engineering",
   						"GK" => "General Facts",
   						"GI" => "General Intelligence",
   						"ME" => "Mechanical Engineering",
   						"NS" => "Natural Sciences",
   						"snh" => "Questions about Krishneha :)" 						
   );
   $getLastSession = mysql_fetch_row(mysql_query("SELECT currentSession FROM scores WHERE userID = '$_SESSION[uidx]'",$conApp));
  
     //TO CANCEL A SESSION IF PAGE IS REFRESHED
   if($_GET['answer']==-2)    //Check quiz is being started from beginning
      {   
			
			$getLastSession_valid = mysql_fetch_row(mysql_query("SELECT session FROM user_attempt WHERE userID = '$_SESSION[uidx]' ORDER BY session DESC",$conApp)); 
					//check entry in running table and end table are same
			 
			 
			 if($getLastSession_valid[0]!=$getLastSession[0])
					$_SESSION['currentPlaySession'] = $getLastSession_valid[0]+1;
				else
				     $_SESSION['currentPlaySession'] = $getLastSession[0] +1;
	      
	   }
	  
	 
      
   /*$count_session =mysql_num_rows(mysql_query("SELECT * FROM userAttempt WHERE session= '$_SESSION[currentPlaySession]' AND userID='$_SESSION[uidx]'",$conApp));
   $count_field_ques = mysql_num_rows(mysql_query("SELECT * FROM questionbase WHERE field='$_GET[field]'",$conApp));
   */ 
  if($_GET['getscore']==1)
   {
       $getDemanded = mysql_fetch_row(mysql_query("SELECT score FROM user_sessions WHERE userID='$_SESSION[uidx]' AND session='$getLastSession[0]'",$conApp));    
       echo $getDemanded[0];
   }
   if($_GET['getquestion']==1)
   {
       $getDemanded= mysql_fetch_row(mysql_query("SELECT questionCount FROM user_sessions WHERE userID='$_SESSION[uidx]' AND session='$getLastSession[0]'",$conApp));    
       echo $getDemanded[0];
   }
    if($_GET['getcorrect']==1)
   {
       $getDemanded= mysql_fetch_row(mysql_query("SELECT correctCount FROM user_sessions WHERE userID='$_SESSION[uidx]' AND session='$getLastSession[0]'",$conApp));    
       echo $getDemanded[0];
   }
    if($_GET['getpassed']==1)
   {
       $getDemanded= mysql_fetch_row(mysql_query("SELECT passCount FROM user_sessions WHERE userID='$_SESSION[uidx]' AND session='$getLastSession[0]'",$conApp));    
       echo $getDemanded[0];
   }
       if($_GET['getattempt']==1)
   {
       $getDemanded= mysql_fetch_row(mysql_query("SELECT attemptCount FROM user_sessions WHERE userID='$_SESSION[uidx]' AND session='$getLastSession[0]'",$conApp));    
       echo $getDemanded[0];
   }
  if($_GET['session_end']==1)
   {
     $totalScore =0;
	 $query = "SELECT * FROM user_attempt WHERE userID='$_SESSION[uidx]' AND session='$_SESSION[currentPlaySession]'";
     $total_temp = mysql_query($query,$conApp);
	 $question_count = mysql_num_rows($total_temp);
	 while($countTotal = mysql_fetch_array($total_temp))
	  {
	    $totalScore = $totalScore + $countTotal['correct'];
	  }	
	  $total_correct = mysql_num_rows(mysql_query("SELECT * FROM user_attempt WHERE (userID='$_SESSION[uidx]' AND session='$_SESSION[currentPlaySession]') AND correct = 3",$conApp));
      $total_passes = mysql_num_rows(mysql_query("SELECT * FROM user_attempt WHERE (userID='$_SESSION[uidx]' AND session='$_SESSION[currentPlaySession]') AND correct = 0",$conApp));  
      $total_attempt = mysql_num_rows(mysql_query("SELECT * FROM user_attempt WHERE (userID='$_SESSION[uidx]' AND session='$_SESSION[currentPlaySession]') AND (correct = 3 OR correct=-1)",$conApp));
	  $get_qid = mysql_fetch_array(mysql_query($query,$conApp));
	  $get_field =mysql_fetch_row(mysql_query("SELECT field FROM questionbase WHERE questionID='$get_qid[questionID]'",$conApp));
	  $field = $get_field[0];	
	 mysql_query("INSERT INTO user_sessions (userID, session, score,questionCount,correctCount,timestamp,passCount,attemptCount,sessionField) VALUES ('$_SESSION[uidx]','$_SESSION[currentPlaySession]','$totalScore','$question_count','$total_correct','$dat','$total_passes','$total_attempt','$fieldOfQuestion[$field]')",$conApp); 
	  mysql_query("UPDATE scores SET totalScore =totalScore + '$totalScore', currentSession='$_SESSION[currentPlaySession]', lastTimePlayed='$dat', totalCorrect = totalCorrect + '$total_correct', totalQuestions = totalQuestions+'$question_count', totalPasses = totalPasses +'$total_passes',totalAttempt= totalAttempt + $total_attempt WHERE userID='$_SESSION[uidx]'",$conApp);
    echo $_SESSION['currentPlaySession'];
  }
   if($_GET['answer']!="" && $_GET['questionID']!="")
   {
		$correct =-1;
        if($_GET['answer']==5) $correct =3; 
		 else if($_GET['answer']==0) $correct = 0;
		 $timeTaken=0;
		/*$getTimeTaken = mysql_fetch_row(mysql_query("SELECT timeTaken FROM user_attempt WHERE userID = '$_SESSION[uidx]' AND session='$_SESSION[currentPlaySession]' ORDER BY recordID DESC",$conApp)); 
        if($getTimeTaken !=null)
		    $timeTaken =  (90-$getTimeTaken[0])-$_GET['time'];
		else */
		if($_GET['answer']!=0)
		{
				$timeTaken = $_SESSION['timer'] - 	$_GET['time'];
				$_SESSION['timer']=$_GET['time'];
		}
		else { $_SESSION['timer']= $_GET['time']; $timeTaken =0; }

		$getoldq = mysql_num_rows(mysql_query("SELECT * FROM user_attempt WHERE (questionID = '$_GET[questionID]' AND userID ='$_SESSION[uidx]') AND session='$_SESSION[currentPlaySession]'",$conApp));
		if($getoldq==0) {
		mysql_query("INSERT INTO user_attempt (userID,questionID,answer,session,correct,timestamp,timeTaken) VALUES('$_SESSION[uidx]','$_GET[questionID]','$_GET[answer]','$_SESSION[currentPlaySession]','$correct','$datwithsecond','$timeTaken')",$conApp);
		if($correct==3)
		    mysql_query("UPDATE questionbase SET totalCorrect= totalCorrect+1 WHERE questionID = '$_GET[questionID]'",$conApp);
		if($correct!=0)
		   mysql_query("UPDATE questionbase SET totalAttempt=totalAttempt+1 WHERE questionID = '$_GET[questionID]'",$conApp);
         else
           mysql_query("UPDATE questionbase SET totalPasses=totalPasses+1 WHERE questionID = '$_GET[questionID]'",$conApp); 		 
        mysql_query("UPDATE questionbase SET TTS=TTS+'$timeTaken' WHERE questionID = '$_GET[questionID]'",$conApp); 		 
		}
	}
   if($_GET['field']!="")
   { 
      $count_session =mysql_num_rows(mysql_query("SELECT * FROM user_attempt WHERE session= '$_SESSION[currentPlaySession]' AND userID='$_SESSION[uidx]'",$conApp));
   $count_field_ques = mysql_num_rows(mysql_query("SELECT * FROM questionbase WHERE field='$_GET[field]'",$conApp));
       if(($count_field_ques == null)||($count_session>=$count_field_ques-2)) {
		   echo "<div style='position:absolute;width:565px;text-align:center;'><div style='font-weight:bold;font-size:60px;'>:(</div><div style='font-size:30px;font-weight:bold;'>Boo! Poor we. <br /><span style='font-size:22px;'>Went out of question.</span></div><br /><br />You seem little faster. No new questions for you! <br />Please wait for countdown.</div>"; exit;
		   } 
	  $getLimit = mysql_fetch_row(mysql_query("SELECT questionID from questionbase ORDER BY questionID DESC",$conApp));
		$exact_field_match =0;
		while($exact_field_match==0)
		{
			$random_question = mt_rand(1,$getLimit[0]);
				$check_field = mysql_fetch_array(mysql_query("SELECT * FROM questionbase WHERE field='$_GET[field]' AND questionID ='$random_question'",$conApp));
		  if($check_field==null)
		          continue;
			 
			else
              {
                 $check_session_redundancy = mysql_fetch_array(mysql_query("SELECT * FROM user_attempt WHERE (session = '$_SESSION[currentPlaySession]' AND userID = '$_SESSION[uidx]') AND questionID = '$random_question'",$conApp));
				 if($check_session_redundancy==null)
                    {
                      if($random_question!=$_SESSION['question'])					
				       	$exact_field_match=1;
					}	
			  }		 							
		}
	
		 $_SESSION['question'] = $random_question;
        echo "<div id='bigQ'>Q.</div><div id='question'>".$check_field['question']."</div>";
		echo "<div id='bigA'>A.</div>";
	 
		$i=1;
		 $check_array =array(5);
		 
		 echo "<div class='choices'>";
		while($i<6)
		{
		  $choiceNo = mt_rand(1, 5);
		  if($i>=2)
		   {
		     $j=0;
		     while($j<$i-1)
			  {
			   if($choiceNo == $check_array[$j]) { $choiceNo = mt_rand(1, 5);$j =0;}
			   else $j++;
			   }
		   }
		  $check_array[$i-1] =$choiceNo;	
		  $arg = "choice".$choiceNo;
           ?>
           <div id="choice" onclick="getQuestion('<?php echo $_GET['field']; ?>','<?php echo $choiceNo;?>','<?php echo $random_question; ?>',<?php echo $_GET['time'];?>)">
           <?php
		    echo "<span id='choiceBox'>".$i."</span>&nbsp;&nbsp;&nbsp;&nbsp;<span class='choiceTex' id='choiceText'>".$check_field[$arg]."</span>"; ?></div><?php 
          $i++;
		}
		echo "</div>";
		?><!--<div id="debug2">PerfectlyDistinctRandomNum algo debugging: <?php if($bestCase==1) echo "<strong>Perfect Randomization => BEST CASE </strong>"; else if($maxTime>20) echo "<STRONG>WORST RANDOMIZATION => CRITICAL CASE </STRONG>";
		   			 else echo "Acceptable complexity"; 
		   			 ?><br />
		   			 Cost in arbitrary unit => <?php echo $maxTime; ?>
		   	</div>-->
		
          <div class="pass_button" onclick="getQuestion('<?php echo $_GET['field']; ?>','0','<?php echo $random_question; ?>',0)"> Surrender  </div>
       <?php echo "<div id='question_info'>  Total Attempts &raquo; ".$check_field['totalAttempt']." | Correct Answers &raquo; ".$check_field['totalCorrect']."</div>";
		echo "<div id='level'> Level &raquo; ".$check_field['difficultyLevel']." on 10 </div>";
	}
	
?>	
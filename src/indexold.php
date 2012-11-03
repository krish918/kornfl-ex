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
   
   // this is the first home page of Kornflex 
   
	$con=mysql_connect("127.0.0.1", "<db-username>", "<db-password>");
	if(!$con)
	  {
		header("location:error.html");
		exit;
	  }
	mysql_select_db("<db-name>", $con);
	$s2 = mysql_query("SELECT * FROM corn_users");
	session_start();
	date_default_timezone_set('IST');
	$indiantime = time() + 45000;
	$dat=date("F j, Y | g:i a", $indiantime);
	$ip =$_SERVER['REMOTE_ADDR'];
	$ui = $_SERVER['HTTP_USER_AGENT'];
	if((isset($_SESSION['user']))||(isset($_COOKIE['user'])))
	  {
	     while($row3=mysql_fetch_array($s2))
		 {
			if(($row3['Email']==$_SESSION['user'])||($row3['Email']==$_COOKIE['user']))
			{
		       if($row3['campusJoined']=='')
						header("Location:welcome.php");
				else 
						header("Location:/home/index.php");
			}
		}	
     }		
	$found=0;
	if(isset($_POST['signin']))
	{
		
		$inpt=valid_input($_POST['iusr']);
		while($row2=mysql_fetch_array($s2))
		{
			if(($row2['Email']==$inpt)&&($row2['Password']==$_POST['ipass']))
			{
			    $uid = $row2['userID'];
				$found=1;
				if(isset($_POST['rem']))
				{
				 setcookie("user",$inpt,time()+(60*60*24*30));	
				}
				$_SESSION['user']=$inpt;
				mysql_query("UPDATE corn_users SET LastLogin = '$dat' WHERE Email='$_SESSION[user]'",$con);
				mysql_query("UPDATE corn_users SET LoginCount = LoginCount +1 WHERE Email = '$_SESSION[user]'", $con);
				/*mysql_query("UPDATE corn_users SET lastUpdate=0 WHERE lastUpdate<>0",$con);*/
				if(($uid!=56)&&($uid!=57))
				mysql_query("INSERT INTO SESSION_DATA (userID, time, ip, systemInfo) VALUES ('$uid', '$dat', '$ip', '$ui')",$con);
                $_SESSION['time']=time();
				if($row2['campusJoined']=='')
				  Header("Location:welcome.php");
				else
				   Header("Location:home/index.php");
		    }
	    }
  }		 
  	$uname = "Full Name";
	$email ="Email Address";
	
    if(isset($_POST['yes']))
      {
       if(mysql_query("INSERT INTO feedback (feedback, feedback2, suggestion) VALUES ('$_POST[feed]','$_POST[feed21], $_POST[feed22], $_POST[feed23], $_POST[feed24], $_POST[feed25], $_POST[feed26], $_POST[feed27]','$_POST[suggestion]')",$con))
	     $sent=1;
		else
         $sent=0; 
      }		 
	   //ranking begins
	/*  $campStartR = mysql_query("SELECT campusCode FROM campusdata WHERE networkCount > 20",$con);
	  $i=0;
	  while($getCampR = mysql_fetch_row($campStartR))
			{
			  $rankStart =mysql_query("SELECT profileID FROM profiles, corn_users WHERE (profiles.campusCode='$getCampR[0]') AND (profiles.userID=corn_users.userID) ORDER BY profiles.ratings DESC, profiles.averageRatings DESC, corn_users.loginCount DESC, corn_users.timeSpent DESC, profiles.fullName",$con);
			  while($rank = mysql_fetch_row($rankStart))
				{
				 $i++;
				 mysql_query("UPDATE profiles SET rank ='$i' WHERE profileID = '$rank[0]'",$con);
				}
			  $i=0;	
			}
        //ranking ends		
   */		
	      ?>
<!DOCTYPE html PUBLIC  "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">

<HTML class="kornflakes" id="korn" lang="en">
<HEAD>
      <TITLE> ahens: discover more about people in your campus</TITLE>
       <META name="description" content="A web utility aiming to create a huge campus network. Log in to your campus,  rate people like you rate movies, create the best profile you can, find people in your campus network, answer witty & weird questions & discover more." />
       <LINK type="shortcut icon" href="favicon.ico" />
       <LINK type="text/css" rel="stylesheet" href="korn_style.css" />
       <SCRIPT type="text/javascript" src="korn_script.js"></SCRIPT>
       <STYLE type="text/css">
            #hidinfo
            	{
            	   position:absolute;
		   top:50px;
		   left:0px;
		   width:440px;
		   height:130px;
            	   background-color:#ddddee;
		   font-family:'lucida sans unicode',tahoma;
		   text-align:center;
		   
		}
      </STYLE>		
</HEAD>
<BODY class="kornContent">


  
    <DIV class="mainContainer" style="height:500px;">
	
	<!--<DIV style="position:absolute;top:300px; left:240px;font-family:'lucida sans unicode';font-size:30px;color:#1C85D4;font-weight:bold;"> STONE<span style="font-weight:normal;">&trade;</span>
	<DIV style="color:#ff9090;font-size:14px;font-family:tahoma;font-weight:normal;">Connecting Brains</DIV></DIV> 
	<DIV style="position:absolute;right:20px;top:10px;"> <DIV id="prof" style="background-color:#ffdddd;border:solid 1px #ff9090;padding:2px;font-family:tahoma;font-size:14px;width:350px;" onclick="location.href='privacy.php';"> AHENS PRIVACY POLICY </DIV>	 
    </DIV>
	<DIV style="position:absolute;right:20px;top:30px;font-family:tahoma;font-size:11px;text-align:right;"> <br />ahens is collecting a lot of information about you daily. <br /> Please don't use ahens before you understand peoperly what information we collect about you <br />and how we use your informations.</DIV> -->
	   <iframe src="https://www.facebook.com/plugins/like.php?href=https://www.facebook.com/ahens09"
        scrolling="no" frameborder="0"
        style="position: absolute;bottom:10px;left:10px;border:none; width:300px; height:30px"></iframe>
	<?php
	 if(isset($_POST['yes']))
	    {
		 if($sent ==1)
		  {
		  ?>
		  <DIV style="position:absolute;left:196px;top:30px;width:500px;border:solid 1px #ddddff;background-color:#eeeefa;padding:2px;font-family:tahoma;font-size:12px;"> Thanks you for your feedback. <br /> We will soon consider it.</DIV>
	     <?php }
		 else
		 {
		 ?>
		  <DIV style="position:absolute;left:196px;top:30px;width:500px;border:solid 1px #eeeeff;padding:2px;font-family:tahoma;font-size:12px;"> Your feedback could not be registered . <br /> Please try after sometime.</DIV>
	      <?php	  
         }
		} 
		 
	   ?>
	 <!-- <DIV class="lowerBar" style="bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute</DIV>-->
  
	<DIV id="end" style="bottom:-90px;left:250px;height:40px;">
	  <br />AHENS &copy; 2012
	  </DIV>
	 <DIV id="des1" class="frontDesign">
                 <DIV id="des1" style="position:absolute; top:-80px; left:-70px; width:100px;height:100px; background-color:#aaaaff;">
                        <DIV id="des1" style="position:absolute;bottom:-70px; left:-70px; width:90px;height:90px; background-color:#ff99ff; ">
                                 <DIV id="des1" style="position:absolute; top:-60px; left:-60px; width:80px;height:80px; background-color:#ffa0ff;">
                                    <DIV id="des1" style="position:absolute; bottom:-50px; left:-50px; width:70px;height:70px; background-color:#ffaaff;">
                                       <DIV id="des1" style="position:absolute; top:-50px; left:-50px; width:60px;height:60px; background-color:#ffabff;">
                                          <DIV id="des1" style="position:absolute; top:-30px; right:-30px; width:50px;height:50px; background-color:#ffbbff;">
                                               <DIV id="des1" style="position:absolute; top:-50px; left:-50px; width:60px;height:60px; background-color:#ffbcff;">
                                                  <DIV id="des1" style="position:absolute; top:-60px; left:-60px; width:80px;height:80px; background-color:#ffccff;">
                                                  <DIV id="des1" style="position:absolute; top:-60px; left:-60px; width:70px;height:70px; background-color:#ffcdff;">
                                                    <DIV id="des1" style="position:absolute; top:-50px; right:-50px; width:60px;height:60px; background-color:#ffceff;">
                                                      <DIV id="des1" style="position:absolute; top:-40px; left:-40px; width:50px;height:50px; background-color:#ffddff;">
                                                         <DIV id="des1" style="position:absolute; bottom:-30px; left:-30px; width:40px;height:40px; background-color:#ffdeff;">
                                                            <DIV id="des1" style="position:absolute; top:-20px; left:-20px; width:30px;height:30px; background-color:#ffd9ff;">
                                                               <DIV id="des1" style="position:absolute; bottom:-16px; left:-18px; width:20px;height:20px; background-color:#ffdfff;">
                                                                 <DIV id="des1" style="position:absolute; top:-8px; left:-8px; width:10px;height:10px; background-color:#ffeeff;">
                                                                 </DIV>
                                                               </DIV>
                                                            </DIV>
                                                         </DIV>
                                                       </DIV>
                                                     </DIV>
                                                  </DIV>
                                                </DIV>
                                             </DIV>
                                          </DIV>  
                                       </DIV>
                                    </DIV>
                                  </DIV>
                                
                            <DIV id="des1" style="position:absolute; top:-150px; right:-140px; width:80px;height:80px; background-color:#acacff;">  
                             <DIV id="des1" style="position:absolute; top:-60px; left:-50px; width:70px;height:70px; background-color:#bbbbff;">
                               <DIV id="des1" style="position:absolute; top:-50px; left:-50px; width:60px;height:60px; background-color:#bdbdff;">
                                 <DIV id="des1" style="position:absolute; top:-30px; right:-40px; width:50px;height:50px; background-color:#ccccff;">
                                     <DIV id="des1" style="position:absolute; top:-30px; left:-35px; width:40px;height:40px; background-color:#ceceff;">
                                        <DIV id="des1" style="position:absolute; top:-20px; right:-10px; width:30px;height:30px; background-color:#ddddff;">
                                           <DIV id="des1" style="position:absolute; top:-18px; left:-15px; width:20px;height:20px; background-color:#dedeff;">
                                              <DIV id="des1" style="position:absolute; top:-8px; right:-7px; width:10px;height:10px; background-color:#eeeeff;">
                                               
                                              </DIV>
                                           </DIV>
                                        </DIV>
                                      </DIV>
                                 </DIV>
                               </DIV>  
                             </DIV>
                            </DIV>    
                         </DIV>
                         
    		</DIV>
         </DIV>
		<!-- <DIV style="position:absolute; top:100px; left:200px;background-color:#ddddff;font-size:14px;padding:4px;border:1px solid #9090ff;font-family:tahoma;">New stylish homepage will be up shortly.</div> -->
	</DIV>
	<script type="text/javascript">
     function loadNotice()
		{
			document.getElementById("dbox5").style.visibility="visible";
			document.getElementById("cvr").style.visibility="visible";
		}
      function hideNotice()
		{
			document.getElementById("dbox5").style.visibility="hidden";
			document.getElementById("cvr").style.visibility="hidden";
		
		}
	</script>
      <DIV class="upLogin"> 
     <FORM method="post" action="<?php echo $_SELF; ?>">
      <DIV id="inner" style="position:absolute;top: 4px; left:490px;"> 
	<input name="iusr" id="imail" type="text" class="signinInput" value="Email" onfocus="omitInpt('imail','Email')" onblur="bringInpt('imail','Email')"/>&nbsp;&nbsp;&nbsp;<input name="ipass" id="ipass" type="text" class="signinInput" value="Password" onfocus="omitpass('ipass','Password')" onblur="bringpass('ipass','Password')" /> 
	&nbsp;&nbsp;&nbsp;<button name="signin" type="submit" id="button" style="width:60px;height:20px;font-size:10px;" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">SIGN IN</button>
     </DIV>
     <span id="loginExtra" style="position:absolute; left:370px; top:6px;font-size:13px;font-family:verdana;"> <INPUT type="checkbox" name="rem" />Remember Me</span> 
    </FORM>
         <span class="upperLink" style="top:0px; left:30px;font-size:13px;" onclick="passRecoverShow()">Boo! I forgot my password.</span>
	<span style="position:absolute; top:8px; left:215px; color: #bb6060;">
	<?php
	          if(isset($_POST['signin']))
	          {
		if($found==0)
			echo "[ Invalid email or password ]";
	         }
	?>
	</span>
    </DIV>
     <div class="bottombar" style="left:0px;width:1347px;"> 
     <div style="position:absolute; left: 30px;top: 2px;">
           <img src="http://www.ahens.com/images/ahensfinalsmall.png" />
     </div>		   
    <div style="position:absolute; left:600px; top:10px;color:#656565;">
     ahens &copy; 2012
	 </div>
     <div style="position:absolute; left:1060px; top:10px;" id="link" onclick="location.href='http://www.ahens.com/home/ahens/about.php';"> About </div>
	 <div style="position:absolute; left:1160px; top:10px;" id="link" onclick="location.href='http://www.ahens.com/home/ahens/help.php';"> Help </div>
	 <div style="position:absolute; left:1260px; top:10px;" id="link" onclick="location.href='http://www.ahens.com/home/ahens/privacy.php';"> Privacy </div>
  </div>
    
 
    <DIV class="midPlate" style="top:280px;">
	<span style="position:absolute;left:430px;font-family:tahoma;font-size: 26px;font-style:bold;">
	kornflakes&trade; is a way to socialise the campuses you live in. </span>
	<span style="position:absolute;left:430px;top: 40px;color:#cccccc;">With Kornflakes, you can begin your campus life once again in a very gentle way. <br />
	Start by registering yourself, choose your campus, create the best profile you can and project it in your campus.<br />
	Share your profile, ideas, gossips,things you are learning and review people in your campus. <br />
        Spread viral messages, get united and much more things to look forward.<br />
	Connect to your whole campus in a way never done before. </span>
	
    </DIV>
    <?php
        if(isset($_POST["submit"]))
         {
         $uname= $_POST['usr'];
         $email=$_POST['email'];
         $passwd=$_POST['pass'];
         }
       ?>  
    <DIV class="glassSignup" style="top:170px;">Signing up at kornflakes is this much simple<br /><br />
    <FORM method="post" action="<?php echo $_SELF; ?>">
	<input type="text" class="regInputBox" name="usr" id="nam" value="<?php echo $uname; ?>" onfocus="omitInpt('nam','Full Name')" onblur="bringInpt('nam','Full Name')"/><br /><br />
	<input type="text" class="regInputBox" name="email" id="mail" value="<?php echo $email; ?>" onfocus="omitInpt('mail','Email Address')" onblur="bringInpt('mail','Email Address')"/><br /><br />
	<input type="text" class="regInputBox" name="pass" id="pass" value="New Password" onfocus="omitpass('pass','New Password')" onblur="bringpass('pass','New Password')"/><br /><br />
                       <button type= "submit" name="submit" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" >SIGN UP</button>
	  <span class="signupErrorInfo">
<?php
		
		
		if(isset($_POST["submit"]))
		{   
			$flag=1;
			if((valid_input($_POST['usr'])=="")||(valid_input($_POST['usr'])=="Full Name"))
				{
				echo "[ Full Name not entered ]";
				$flag=0;	
				}
			else if(preg_match("/[^a-zA-Z\.\_\s]/", valid_input($_POST['usr'])))
					{
					echo "Invalid Full Name.Alphanumerals, [.] and [_] allowed";
					$flag=0;
					}
			
			if((valid_input($_POST['email'])=="")||(valid_input($_POST['email'])=="Email Address"))
				{
				echo "[ Email not entered ]";
				$flag=0;
				}
		        else if(!preg_match("/[\w\-]+\@[\w\-]+\.[\w\-]+/", valid_input($_POST['email'], "Email address not entered")))
					{
					echo "[ Invalid Email Address ]";
					$flag=0;
					}
			    else {
					$flag2=1;
					$s =mysql_query("SELECT * FROM corn_users");
				        while($row=mysql_fetch_array($s))
					{
					  if($row[Email]==valid_input($_POST['email']))
					    { $flag2=0;break;}
					}
					if($flag2==0)
					{
					 echo "[ Email already registered ] ";
					 $flag=0;
					}
				  }	
			if(($_POST['pass']=="New Password")||($_POST['pass']==""))
				{echo "[ Password  not entered ]";$flag=0;}
			else if(strlen($_POST['pass'])<6)
				{echo "[ Password too short ]"; $flag=0;}
				
			 }
		function valid_input($input)
		{
			
			$input= trim($input);
			$input= stripslashes($input);
			$input= htmlspecialchars($input);
		
			return $input;
			
		}
?></span><?php
		if($flag==1)
			{
			    $name = $_POST['usr'];
			    $pass = $_POST['pass'];
			    $to = $_POST['email'];
			    $subject="Activate your ahens account";
			    $message="<HTML><BODY>
			   	<Div style='color:#7070aa;font-family:verdana;'><h2>Dear $name,</h2>
			   	<h1>Welcome to ahens</h1></Div>
		
			   	<DIV style='font-family:tahoma;border-style:solid;border-width:30px 0px 12px 0px;border-color:#303030;width:650px;background-color:#ddddff;'>
				<h2>Create great possibilities inside your own campus!</h2>
				<h3> ahens allows you to communicate with your peers in a way never done before.
			   	 </h3>
				 <h4>Just activate your account here and discover more!</h4>
				 <br /><br />
				 <a href='http://www.ahens.com/activate.php?name=$name&pass=$pass&email=$to'>Click Here to activate your account</a>
			      <br /><br />   
				 Regards<br />the.ahens.team<br /><br />
				 <span style='font-size:11px;color:#999999;'>This email has been sent from an unmonitered Email address. <br />
				 Please do not reply this message.
				 </span>
				 </DIV></BODY></HTML>";
			   $header = "MIME-Version: 1.0"."\r\n"; 
			   $header .= 'Content-type:text/html;charset=iso-8859-1';
		           $header .= "\r\n"."From: krish.ahens@ahens.com";  
			   if(mail($to,$subject,$message,$header))	
				{	
					?>
					<div class="thank"><span style="font-size: 60px; color:#ff3090;">Thank you!</span>	
					<br /><span style="position: absolute; left: 0px;width:330px;text-align:center;font-size: 14px;color:#905050;font-family:'tahoma';">Dear <?php echo $_POST['usr']; ?>,<br/><br/>Request for your registration has been accepted. 
					<br />Your Email Address: <?php echo $_POST['email']; ?><br/>An Email has been sent on the above address.<br /><br />	
					Goto your inbox/spambox and follow instructions to activate your account.<br />All the best!</span></div>
					<?php 
			        }
			     else echo "<span style='font-size:14px;'>Server Busy.Try Later.</span>";   		
				
			}   

              
      
          $flag2=1; 
     if($_SESSION['act']!='')
		  {
		   $_SESSION['act']='';
           if($_GET['actStat']=='aBBbc98765_ffdSER')
	              {
				   ?>
				   <DIV class="thank"> <span style="font-size: 60px; color:#ff3090;">Congrats!</span><br /><br />
          		                    <span style="position: absolute; left: 0px;width:330px;text-align:center;font-size: 14px;color:#905050;font-family:'tahoma';">
          		                    Dear <?php echo $_GET['name']; ?>,<br />
          		                     Your account has been confirmed and now you are ready to get started. <br /><br />
          		                     Log in here and begin.</span><br />
          		                     </DIV>
				<?php
                   }				
	               
		if($_GET['actStat']=='active')		
		      {
			    ?>
				<DIV class='thank'><span style='font-size: 60px; color:#ff3090;'>
				Action Cancelled!</span><br />
                 <span style='position: absolute; left: 0px;width:330px;text-align:center;font-size: 14px;color:#905050;font-family:tahoma;'>
              	Dear <?php echo $_GET['name']; ?> <br />
				You have already activated your account. 
				If you have forgotten your password, try to recover it. <br /><br />Click on the button at login bar.</span></DIV>
			<?php
               }
	   	if($_GET['actStat']=='failed')	
               { ?>
			     <DIV class='thank'><span style='font-size: 60px; color:#ff3090;'>
				Server Error!</span><br />
                 <span style='position: absolute; left: 0px;width:330px;text-align:center;font-size: 14px;color:#905050;font-family:tahoma;'>
              	Some error ocurred at ahens server.<br /><br />Please try later.</span></DIV>
               <? } 			   
	    }		   
   $nameGet = '';    		            
   if(($_GET['resetCode']=='abbbFFGJT655_755gsujsggs755398jhFGFFDDDGswo94jbb')&&($_POST['blocker']=='true'))
	      {
	       
	          $s3 = mysql_query("SELECT * FROM corn_users",$con);
	          while($reset = mysql_fetch_array($s3))  
	             {
	             if($reset['Email']==$_GET['name'])
	             {
	              $nameGet=$reset['userName'];
	              if(strlen($_POST['pass'])>5)
	               {
	                   $newQuery = "UPDATE corn_users SET Password = '$_POST[pass]' WHERE Email = '$_GET[name]'";
	                   if(mysql_query($newQuery,$con)) 
	                     {?><DIV class="thank"><span style="font-size: 60px; color:#ff3090;">Congrats!</span><br /><br />
          		                    <span style="position:absolute;left: 0px;width:330px;text-align:center;font-size: 14px;color:#905050;font-family:'tahoma';">
          		                     Dear <?php echo $nameGet; ?>,<br />
          		                     Your password has been reset.<br /><br /><br />
          		                     Please use your new password to login.</span><br />      
	                        </DIV><?php
	                       
	                      }
	                    else
	                      {?><DIV class="thank"><span style="font-size: 60px; color:#ff3090;">Sorry!</span><br /><br />
          		                    <span style="position:absolute;left: 0px;width:330px;text-align:center;font-size: 14px;color:#905050;font-family:'tahoma';">
          		                    Dear <?php echo $nameGet; ?>,<br />
          		                     Some error ocurred at ahens servers.<br /><br />
          		                     Please try after some time.</span><br />      
	                        </DIV><?php
	                         
	                      
	                       }
	                } 
	               else
	                 {?>
	                   <DIV class="thank"><span style="font-size: 60px; color:#ff3090;">Action Cancelled!</span><br />
          		                    <span style="position:absolute;left: 0px;width:330px;text-align:center;font-size: 14px;color:#905050;font-family:'tahoma';">
          		                    Dear <?php echo $nameGet; ?>,<br />
          		                     Your password was too short or was not entered.<br /><br />
          		                     Please go back to your inbox and re-enter it.</span><br />      		                
          		  </DIV><?php
	                  }
	                }  
	          }
	        }       
            
            
              		 ?>         	  	
          		     		 		                                  	
                        <br /><span style="font-size:9px; font-family:tahoma;">Clicking sign up button means you agree to our <a href =""> terms</a> and have read our <a href="">policy</a>.</span>		 
   </FORM>
  
   </DIV>
  
	 <DIV class="cover" id="cvr"  style="height:715px;"><!--<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />ahens.com is under maintenance<br /><span style="font-size: 18px;">Expected uptime : May 4, 2012 | 12:30 AM IST	</span> --></DIV>
	 <DIV class="dialogueBox" id="dbox">
	     <DIV id="upBar">Password Recovery</DIV>
	     <span id="hidinfo" style="visibility:hidden;"></span>
             <span id="info"> 
               <br /><br /><br />
               Enter your Email address here. 
               You will be able to reset your password after logging into your Email account.
               <br />  <br /> 
               <input id="emailRec" onkeydown="rem(this.value)" type="text" size="50" class="simple" style="height:30px;" />
              </span>
                <br /><br />
	      
	     
	      <span style="position:absolute; top:180px;left:170px;visibility:hidden;" id="but3"><button type="button" id="button" onclick="hide1()" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove';this.style.backgroundColor='#dddddd';">Close</button>
	      </span>
	      <span id="but">
	      <button type="button" id="button" onclick="loadajax()" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">Proceed</button>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <SCRIPT type="text/javascript">
                  var http;
                  var flag;
                   flag = 0;
                  function loadajax()
                      {
                      if(window.XMLHttpRequest)
                            http = new XMLHttpRequest();
                        else
                            http = new ActiveXObject("Msxml2.XMLHTTP");    
                       var mail = document.getElementById("emailRec").value;
                       var msg = document.getElementById("msg");
                       
                       if(mail == "")
                            msg.innerHTML ="<br /><br />You are missing something!";
                       if(mail != "")
                          {
                            document.getElementById("hidinfo").style.visibility = "visible";
                            document.getElementById("but").style.visibility = "hidden";
                    
                           http.onreadystatechange = function()
                               {
                                
                                 if(http.readyState == 1)
                                     document.getElementById("hidinfo").innerHTML = "<br /><br />Sending Mail...<br /><br /><img src='images/loader.gif' />";
                                 if(http.readyState == 2)
                                     document.getElementById("hidinfo").innerHTML = "<br /><br />Requesting...<br /><br /><img src='images/loader.gif' />"
                                 if(http.readyState == 3)
                                     document.getElementById("hidinfo").innerHTML = "<br /><br />Finishing request...<br /><br /><img src='images/loader.gif' />"
                                 if(http.readyState == 4 && http.status==200)
                                      {
                                         flag=1;
                                        document.getElementById("hidinfo").innerHTML = "<br />" + http.responseText;
     
                                  	document.getElementById("but3").style.visibility = "visible";
                                         
                                       }
                                }
                           http.open("get", "recovery.php?data="+mail,true);
                           http.send();   
                         } 
                      
                      
                      }
        
                        
                    
                   </SCRIPT>
                <button type="button" id="button" name="cancel" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hide1()">Cancel</button>
              </span>
	      <span id="msg" style="font-size:14px;">
	      </span>
	 </DIV>	  
 
 <?php
      
	  if(isset($_GET['logout']))
	    {
		   ?>
		 <DIV class="cover" id="cvr"  style="height:715px;visibility:visible;"></DIV>  
		 <DIV class="feedback"><span style="font-size:14px;font-weight:bold;">Please help us improve ahens, through your valuable feedback! </span>
		 <hr />
		 <span style="font-weight:bold;">How would you rate Kornflakes Beta?</span><br /><br />
		 <FORM action="index.php" method="post">
	        <input type="radio" name="feed" value="Poor" /> It's Poor<br />
			<input type="radio" name="feed" value="Average" /> It's Average & looks like a beginner's work.<br />
			<input type="radio" name="feed" value="Good" /> It's Good & has a nice idea.<br />
			<input type="radio" name="feed" value="Excellent" /> It's excellent & looks professional.<br />
			<INPUT type="radio" name="feed" value="Incredible & Amazing" /> Incredible & Amazing<br /><br />
		 <span style="font-weight:bold;">What problems you faced while using Kornflakes Beta? </span><br /><br /> 	
		    <INPUT type="checkbox" name="feed21" value="link problem" /> Links/Buttons were not working. (Bottom links have been kept intentionally dead!). <br />
			<INPUT type="checkbox" name="feed22" value="design problem" /> Design of website was distorted. (Try using Recommended browsers). <br />
			<INPUT type="checkbox" name="feed23" value="meter problem" /> Rating meter was not working. (Try clicking on numbers instead of meter). <br />
			<INPUT type="checkbox" name="feed24" value="flakes problem" /> I can't post flakes. <br />
			<INPUT type="checkbox" name="feed25" value="profile overlap" /> Some other person's profile overlaps over mine. <br />
			<INPUT type="checkbox" name="feed26" value="search prob" /> I have problems in searching the profiles. (Try searching on a better connection).<br />
			<INPUT type="checkbox" name="feed27" value="no problem" /> Everything was fine.<br />
		<br />
         <span style="font-weight:bold;">Your valuable suggestions or problems not mentioned above :</span><br /><br />
           <TEXTAREA style="height:40px;width:450px;font-family:tahoma;font-size:12px;" name="suggestion"></TEXTAREA>
         <br /><br />
         As you have logged out, we have no information about your identity. So, feel free to speak your heart.	<br /><br />
         <button type="submit" id="button" name ="yes" style="width:150px;"onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';"> Send to ahens </button>
		 &nbsp;&nbsp;&nbsp;&nbsp;
		 <button type="submit" id="button" name="no" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';"> No thanks! </button>
		</FORM>
		 </DIV>
        <?php } 
		?>	 
		<DIV class="dialogueBox" id="dbox5" style="top:190px;height:355px;">
	 <DIV id="upBar">Ahens Support</DIV><br /><DIV style="font-size:14px;font-family:tahoma;"><br />The distorted textbox contains effects that your system is not capable to process. 
	    Kornflakes is full of such effects & so, will affect your experience of the application.
			Follow the following steps to get rid of this problem: </DIV>
			<ol style="font-family:tahoma;font-size:12px;text-align:left;">
				<li> Click on the settings icon in topmost right corner of IE9.</li>
				<li> Select internet options.</li>
				<li> Click on Advanced tab</li>
				<li> Under Accelerated Graphics option, Check "Use Software rendering instead of GPU rendering".</li>
				<li> Click OK and restart Internet explorer 9.</li>
			</ol>
				<DIV style="font-size:14px;font-family:tahoma;">
				<span style="color:red;">Caution:</span><br />
				Don't use Internet Explorer 9 in compatiability mode. Compatiabilty mode is toggled on or off after clicking on a small icon
				of "a broken page" in address bar.</DIV><br />

	 
	 <button type= "button" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hideNotice()">OK</button>
	 </DIV>
</BODY>
</HTML>
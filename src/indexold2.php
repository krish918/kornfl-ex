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
   // This is the second homepage of kornflex
   
	$con=mysql_connect("127.0.0.1", "<db-username>", "<db-password>");	  

	if(!$con)
	  {
		header("Location:error.html");
		exit;
	  }
	mysql_select_db("<db-name>", $con);
	$s2 = mysql_query("SELECT * FROM corn_users",$con);
	session_start();
	date_default_timezone_set('IST');
	$indiantime = time() + 45000;
	$dat=date("F j, Y | g:i a", $indiantime);
	$ip =$_SERVER['REMOTE_ADDR'];
	$ui = $_SERVER['HTTP_USER_AGENT'];
	if((stripos($ui,'firefox')===false)&&(stripos($ui,'presto')===false)&&(stripos($ui,'safari')===false)&&(stripos($ui,'chrome')===false)&&(stripos($ui, 'msie 9.0')===false)&&(stripos($ui, 'msie 10.0')===false))
	{
	   header("Location:old.php"); 
	}    
		
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
	if(($_POST['emailin']!='') && ($_POST['passwordin']!=''))
	{
		
		$inpt=valid_input($_POST['emailin']);
		$row2 = mysql_fetch_array(mysql_query("SELECT * FROM corn_users WHERE Email='$inpt'",$con));
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
		   
	    
  }		 	   //ranking begins
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
    $code = 'ahnS9kP#&18krsS';   
	$twittername = 'ahenstrends';
	if($_POST['code']!='' && $_POST['twtr']!='')
		$twittername = $_POST['twtr'];
    function valid_input($input)
		{
			
			$input= trim($input);
			$input= htmlspecialchars($input);
		
			return $input;
			
		}	
	?>
		  
<!DOCTYPE html>

<HTML class="ahensStart" id="korn" lang="en">
<HEAD>
      <TITLE> ahens : Discover more about people in your campus</TITLE>
       <META name="description" content="A web utility aiming to create a huge campus network. Log in to your campus,  rate people like you rate movies, create the best profile you can, find people in your campus network, answer witty & weird questions & discover more." />
       <LINK type="shortcut icon" href="favicon.ico" />
       <LINK type="text/css" rel="stylesheet" href="korn_style.css" />
       
	<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>

	<script type="text/javascript" src="korn_script.js"></script>
</HEAD>
<BODY class="ahens" onload="setTimeout(function(){fadein(1,'note');},4000);">
  <div class="topbar"> 
    <div style="position:absolute; left:80px; top:10px;color:#656565;">
     ahens &copy; 2012
	 </div>
     <div style="position:absolute; left:700px; top:10px;" id="link" onclick="location.href='http://www.ahens.com/home/ahens/about.php';"> About </div>
	 <div style="position:absolute; left:800px; top:10px;" id="link" onclick="location.href='http://www.ahens.com/home/ahens/help.php';"> Help </div>
	 <div style="position:absolute; left:900px; top:10px;" id="link" onclick="location.href='http://www.ahens.com/home/ahens/privacy.php';"> Privacy </div>
  </div>
  <div class="midcircle"></div>
  <div class="ahenslogo"> <a href="javascript: todefault();"><img src="http://www.ahens.com/images/ahensfinal.png" border=0/> </a></div>
  <div class="tagline"> a.gentle.way.to.discover.more<br />about.people.in.your.campus</div>
  <div class="buttonstart" onclick="transit(0,400,175,'signup');" id="start"> Start </div>
  <div class="buttonresume" onclick="transitup(0,-240,230,'signin');" id="resume"> Resume </div>
  <div class="twitterwidget">
  <div id="alltweets">
  <script>
	var obj=	new TWTR.Widget({
				version: 2,
				type: 'profile',
				rpp: 24,
				interval: 5000,
				width: 250,
				height: 300,
				theme: {
						shell: {
						background: 'rgb(47, 50, 0)',
						color: '#ffffff'
								},
					tweets: {
						background: 'rgb(212, 58, 46)',
						color: '#ffffff',
						links: '#5afa0a'
							}
				},
			features: {
					scrollbar: true,
					loop: true,
					live: true,
					behavior: 'all'
				}
			}).render().setUser('<? echo $twittername; ?>').start();
	if(obj.rpp==0)
		{
		alert("asd");
		document.getElementById('alltweets').innerHTML = "This User is not available on twitter. Click Here to create your account on twitter";
		document.getElementById('fbutton').style.visibility = "hidden";
		}	
</script> </div>
 <br />	
<a id="fbutton" href="https://twitter.com/<?php echo $twittername; ?>" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @krishenters</a>
 <script>
      !function(d,s,id)
	  {
	    var js,fjs=d.getElementsByTagName(s)[0];
		if(!d.getElementById(id))
		  {
		     js=d.createElement(s);
			 js.id=id;
			 js.src="//platform.twitter.com/widgets.js";
			 fjs.parentNode.insertBefore(js,fjs);
		  }
	   }(document,"script","twitter-wjs");
 </script>
 <br /><br />
  Now, your tweets too can get featured exclusively<br /> at ahens homepage!
  <span onclick="fadein(1,'signup');fadein(1,'signin');fadeout(0,'getfeatured');fadein(1,'whistle');" id="prof"> Click here.</span>
</div>	
<div class="fbplugin"> 
        <iframe src="https://www.facebook.com/plugins/like.php?href=https://www.facebook.com/ahens09"
        scrolling="no" frameborder="0"
        style="border:none;width:300px; height:24px;"></iframe>
</div>        
   <div class="getfeatured" id="getfeatured">
     <div onmouseover="fadeout(0,'button2');" onmouseout="fadein(1,'button2');">
	<button type="button" class="blackbutton" id="button2" onclick="fadein(1,'getfeatured');fadein(1,'button1');fadein(1,'button2');"> Hide </button><br />
	<br />
     If you love tweeting as much as you like ahens,
	 we will give you a lovely place on ahens.com.<br /><br />
	 All you have to do is to be a power user of ahens.com.
	 We will figure out power user by sorting time spent by users on ahens.com.
	 You would have to spend an appreciable amount of time, otherwise nobody will qualify. 
	 Once you win, we will provide you a secret code. Enter it here, with your twitter username, ofcourse
	 and get instantly featured on ahens homepage.
	 <br /><br />
	 Is there any benefit?<br />
	 Ofcourse, ahens.com is being visited by appreciable amount of users and each of them will go through your tweets.
	 Your tweets are the first thing any user sees on ahens.com. If you are liked, you'll be certainly followed.
	 <br />
	 <br />
	 </div>
	 <form method="post" action="" id="twt">
	  <input id="inpt1" name="code" value="15 Letter Secret Code" onfocus="if(this.value=='15 Letter Secret Code') this.value='';checkentry();" onblur="if(this.value.length==0) this.value='15 Letter Secret Code';checkentry();" onkeyup="checkentry();"/>
	  <input id="inpt2" name="twtr" value="Twitter Username" onfocus="if(this.value=='Twitter Username') this.value='';checkentry();" onblur="if(this.value.length==0) this.value='Twitter Username';checkentry();" onkeyup="checkentry();"/><br />
	  <br />  <button name="tweet" type="button" class="blackbutton" id="button1" onclick="validnsub('<?php echo $code;?>')"> Get Featured </button>
	 </form>  <br />
	 <div id="msg" style="color:#ff6060; font-family:tahoma; font-size:13px;visibility:hidden;"></div>
   </div>	
   <div class="signup" id="signup">
      <form id="signup" action="" method="post">
	   <div id="textboxcontainer"> Your own valid Email Address <input type="text" id="email" class="signupbox" onfocus="fadein(1,'signuperror')"/> </div>
		<div id="textboxcontainer"> A New Password  <input type="password" id="password" class="signupbox" onfocus="fadein(1,'signuperror')"/> </div>
		<div id="textboxcontainer"> Your Original Complete Name <input type="text" id="username" class="signupbox" onfocus="fadein(1,'signuperror')"/></div>
	    <div style="color:#909090; font-family:arial; font-size:11px;padding: 2px;"> <br />By signing up at ahens, you are allowing
			ahens to use your basic informations for moderate purposes. Proceed, only if, you are mature enough to understand
			the risk involved in sharing your informations on internet.
		</div>	<br />
		 <button type="button" class="subsignup" id="subsignup" onclick="validatesignup()"> Click Here To Signup</button>
			
	  </form>	
      <div id="signuptriangle" style="cursor:pointer;" onclick="clearall()"> </div>  
	  <br /><br /><br /><br /><br />
	  <div class="signuperror" id="signuperror"></div> 
   </div> 
    <div class="signup" id="signin" style="top:230px;height:240px;font-size:16px;color:#dddddd; box-shadow: 0 0 9px 1px #999999;">
	     <form id="signinform" method="post" action="">
		 <div id="textboxcontainer"> Your Email Address <input type="text" id="emailin" name="emailin" class="signupbox" style="font-size:21px;" onfocus="fadein(1,'signuperror2')"/> </div>
		 <div id="textboxcontainer"> Your Password <input type="password" id="passwordin" name="passwordin" class="signupbox" style="font-size:21px;" onfocus="fadein(1,'signuperror2')"/> </div>
		  <br /><div id="prof2" style="font-family:tahoma; font-size:14px;color:#ff9450;" onclick="window.open('forgotpassword.php');"> Password Lost?</div><br />
		  <button type="button" class="subsignup" id="subsignup2" onclick="login()"> Click Here To Login</button>
		  </form>
		  <div id="signuptriangle" style="cursor:pointer;top:58px;" onclick="clearall()"> </div>  
		  <br /><br /><br /><br />
		  <div class="signuperror" id="signuperror2"></div>
	</div>	
	
	<div class="ahenswhistle" id="whistle">
		<div id="bdy" style="position:absolute;top:-375px;"> <span style="font-size:24px; font-weight:bold;"> Nobody listens to you?</span><br />
				<span style="font-size:20px;"> Blow your whistle at ahens! </span><br /><br />
			     <span style="font-family:tahoma;color:#eeeeee;">If everyday you wake up and keep wondering why did you wake up, for hours, this one may bring some hope. 
				 <br /><br />
				 Waking up early was never so much exciting.
				 Now, every day you get a chance to get featured at ahens homepage exclusively, with any one of your feelings.
				 Quote something and whistle it out at ahens homepage, if you are the first such creature of the day. You can
				 quote everyday and who quotes first, grabs the whistleblower position of that day. If you are confused, it simply
				 means, if you are dying (really?) to get featured at ahens homepage, you would have to blow the whistle first
					of all each day.
				<br /><br />
				 Simple! You can start blowing whistle from 17 May 2012, 00:00 IST.
				</span>
				</div>	 
				<div id="demo" style="visibility:hidden;font-family:arial;font-size:15px;">
				<span style="font-size:30px;font-family:georgia,verdana;font-style:italic;"><span style="font-size:50px;">&ldquo;</span>
				Design is something which erupts best from puzzled hearts<span style="font-size:50px;">&rdquo;</span></span>
				<br /><br /><div style="text-align:right;">
				   <span style="font-size:15px;font-family:tahoma;">-as said by <span style="font-weight:bold;"> Krishna Murti </span></span><br /><br />
				          <span style="font-style:italic;"> Dehradun Institute Of Technology, Dehradun </span><br />
						  <span style="font-family:tahoma;color:#cccccc;"> Going to Mussoorie today with whole ahens team</span>
				   </div>
				</div> 
				<br /><br />   
				 <div id="whistleintro" onmouseover="transitgendown(-375,10,'bdy')" onmouseout="transitgenup(0,-375,'bdy');fadein(1,'demo');" onclick="fadein(1,'bdy');fadeout(0,'demo');">Introducing ahens whistle </div>
		</div>
		<DIV id="note" class="notice" style="position:absolute;top:130px;left:830px;background-color:rgba(255,255,255,0.6);"> If design confuses you <br /> click here for default view.<br />
		 		 <DIV style="position:absolute; top:50px;left:-30px;width:0;height:0;border-top:30px solid transparent;border-bottom:30px solid transparent; border-right:30px solid rgba(255,255,255,0.3);"></DIV>
			<DIV style="position:absolute; top:50px;left:-29px;width:0;height:0;border-top:30px solid transparent;border-bottom:30px solid transparent; border-right:30px solid rgba(255,255,255,0.3);"></DIV>
		  </DIV>
</BODY> 
</HTML>
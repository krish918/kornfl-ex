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
		header("location:disconnected.html");
		exit;
		
	  }
	mysql_select_db("<db-name>", $con);
	
	session_start();
	$username='';
	
	$sql2 = mysql_query("SELECT * FROM corn_users");
	$sq = mysql_query("SELECT * FROM corn_users");
	while($row = mysql_fetch_array($sql2))
	{
		if(($row['Email']==$_SESSION['user'])||($row['Email']==$_COOKIE['user']))
			$username= $row['userName'];
	}
	if(isset($_POST['signout']))
		{
		   mysql_query("UPDATE corn_users SET lastIP = '$_SERVER[REMOTE_ADDR]', lastUserInfo = '$_SERVER[HTTP_USER_AGENT]' WHERE Email ='$_SESSION[user]'",$con);
			$current = time();
			$spent = $current - $_SESSION['time'];
			mysql_query("UPDATE corn_users SET timeSpent = timeSpent + '$spent'  WHERE Email ='$_SESSION[user]'",$con);
			mysql_query("UPDATE corn_users SET live = 0 WHERE Email = '$_SESSION[user]'", $con);
			session_destroy();
			setcookie("user","",time()-3600);
			header("Location:index.php");
		}
	if((isset($_SESSION['user']))||(isset($_COOKIE['user'])))
	  {
	     while($row3=mysql_fetch_array($sq))
		 {
			if(($row3['Email']==$_SESSION['user'])||($row3['Email']==$_COOKIE['user']))
			{
		       if($row3['campusJoined']!='')
						header("Location:home/index.php");
			}
		}	
     }			
	if(($_SESSION['user']=='')&&($_COOKIE['user']==''))	
		header("Location:index.php");
      $m=0;
      if(isset($_GET['requst']))
        {
          $to = "s3_krish@yahoo.co.in";
          $sub = "Campus request";
	  $message = "Request from: ".$_SESSION['user']." | campus name: ".$_GET['nam']." | Campus address:".$_GET['addr'];
          $message .=" | city: ".$_GET['city']." | State: ".$_GET['state'];
          if(mail($to,$sub,$message))
               $m=1;
         }
?>

<!DOCTYPE html PUBLIC  "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakes" id="welcome" lang="en">
<HEAD>
      <TITLE> <?php echo $username;?>: Welcome to kornflakes </TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="favicon.ico" />
       <LINK type="text/css" rel="stylesheet" href="korn_style.css" />
      
       <SCRIPT type="text/javascript" src="korn_script.js"></SCRIPT>
</HEAD>
<BODY class="kornContent">
   
 
    <DIV class="mainContainer" style="height:780px;">

           <DIV class="campusBlocks" style="top:20px;" onclick="showDialog('IITKGP', 'IIT Kharagpur')"><br /><br />IIT Kharagpur</DIV>
  	    <DIV class="campusBlocks" style="top:20px;left:360px;" onclick="showDialog('ISMD','ISM Dhanbad')"><br /><br />ISM Dhanbad</DIV>
        <DIV class="campusBlocks" style="top:20px;left:270px;" onclick="showDialog('IITK','IIT Kanpur')"><br /><br />IIT Kanpur</DIV>
            <DIV class="campusBlocks" style="top:20px;left:540px;" onclick="showDialog('ITBHU', 'IT BHU')"><br /><br />IT BHU</DIV>
        <DIV class="campusBlocks" style="top:20px;left:450px;" onclick="showDialog('IITD','IIT Delhi')"><br /><br />IIT Delhi</DIV>
             <DIV class="campusBlocks" style="top:20px;left:720px;" onclick="showDialog('BITP','BIT Patna')"><br /><br />BIT Patna</DIV>
        <DIV class="campusBlocks" style="top:20px;left:630px;" onclick="showDialog('BITM','BIT Mesra')"><br /><br />BIT Mesra</DIV>
                     
                     
            <DIV class="campusBlocks" style="top:130px;" onclick="showDialog('DBITD','DBIT Dehradun')"><br /><br />DBIT Dehradun</DIV>
  	    <DIV class="campusBlocks" style="top:130px;left:360px;" onclick="showDialog('GEU','Graphic Era University')"><br /><br />Graphic Era University </DIV>
        <DIV class="campusBlocks" style="top:130px;left:270px;" onclick="showDialog('DITD','DIT Dehradun')"><br /><br />DIT<br /> Dehradun</DIV>
            <DIV class="campusBlocks" style="top:130px;left:540px;" onclick="showDialog('IEMK','IEM Kolkata')"><br /><br />IEM Kolkata</DIV>
        <DIV class="campusBlocks" style="top:130px;left:450px;" onclick="showDialog('BITSIN','BIT Sindri')"><br /><br />BIT Sindri</DIV>
             <DIV class="campusBlocks" style="top:130px;left:720px;" onclick="showDialog('LPU','Lovely Professional University')"><br />Lovely Professional University</DIV>
        <DIV class="campusBlocks" style="top:130px;left:630px;" onclick="showDialog('DU','Delhi University')"><br /><br />Delhi University</DIV>
        
        
                <DIV class="campusBlocks" style="top:240px;" onclick="showDialog('BITDU','BIT Durg')"><br /><br />BIT Durg</DIV>
  	    <DIV class="campusBlocks" style="top:240px;left:360px;" onclick="showDialog('GBPU','GB Pant University')"><br /><br />GB Pant University</DIV>
        <DIV class="campusBlocks" style="top:240px;left:270px;" onclick="showDialog('IMSD','IMS Dehradun')"><br /><br />IMS Dehradun</DIV>
            <DIV class="campusBlocks" style="top:240px;left:540px;" onclick="showDialog('FITJE','FIITJEE Delhi')"><br /><br />FIITJEE <br />Delhi</DIV>
        <DIV class="campusBlocks" style="top:240px;left:450px;" onclick="showDialog('NIFTD','NIFT Delhi')"><br /><br />NIFT Delhi</DIV>
             <DIV class="campusBlocks" style="top:240px;left:720px;" onclick="showDialog('RNSIT','RNS Institute Of Technology')"><br /><br />RNSIT <br /> Bangalore</DIV>
        <DIV class="campusBlocks" style="top:240px;left:630px;" onclick="showDialog('NITD','NIT Durgapur')"><br /><br />NIT Durgapur</DIV>
        
        
                 
            <DIV class="campusBlocks" style="top:490px;" onclick="showDialog('AMITU','Amity University')"><br /><br />Amity University</DIV>
  	    <DIV class="campusBlocks" style="top:490px;left:360px;" onclick="showDialog('UITD','UIT Dehradun')"><br /><br />UIT dehradun</DIV>
        <DIV class="campusBlocks" style="top:490px;left:270px;" onclick="showDialog('UPESD','UPES Dehradun')"><br /><br />UPES Dehradun</DIV>
            <DIV class="campusBlocks" style="top:490px;left:540px;" onclick="showDialog('OPJIT','OPJIT Raigarh')"><br /><br />OPJIT Raigarh</DIV>
        <DIV class="campusBlocks" style="top:490px;left:450px;" onclick="showDialog('RCETB','RCET Bhilai')"><br /><br />RCET Bhilai</DIV>
             <DIV class="campusBlocks" style="top:490px;left:720px;" onclick="showDialog('NITP','NIT Patna')"><br /><br />NIT Patna</DIV>
        <DIV class="campusBlocks" style="top:490px;left:630px;" onclick="showDialog('NITW','NIT Warangal')"><br /><br />NIT Warangal</DIV>
        
        
        <DIV class="campusBlocks" style="top:605px;" onclick="showDialog('IPU','IP University')"><br /><br />IP University</DIV>
  	    <DIV class="campusBlocks" style="top:605px;left:360px;" onclick="showDialog('AU','Annamalai University')"><br /><br />Annamalai<br />University</DIV>
        <DIV class="campusBlocks" style="top:605px;left:270px;" onclick="showDialog('MITM','MIT Manipal')"><br /><br />MIT Manipal</DIV>
            <DIV class="campusBlocks" style="top:605px;left:540px;" onclick="showDialog('SSCET','SSCET Raipur')"><br /><br />SSCET<br /> Raipur</DIV>
        <DIV class="campusBlocks" style="top:605px;left:450px;" onclick="showDialog('HCAS','Holy Cross Convent School, Ambikapur')"><br /><br />Holy Cross Ambikapur</DIV>
             <DIV class="campusBlocks" style="top:605px;left:720px;" onclick="showDialog('BITMS','BITM Santiniketan')"><br /><br />BITM Santiniketan</DIV>
        <DIV class="campusBlocks" style="top:605px;left:630px;" onclick="showDialog('IITG','IIT Guwahati')"><br /><br />IIT Guwahati</DIV>
      <?php   
                if($m==1)
                { ?>
                   <span class="bottomDec">Your request has been registered. We will consider it soon. Please check your Email for response.</span>
                 <?php }
                 ?>
<SCRIPT type="text/javascript">
  function showcampReq()
   {
    document.getElementById("dbox2").style.visibility="visible";
    document.getElementById("cvr").style.visibility="visible";
   }
 </SCRIPT>    
               
    
	<DIV class="lowerBar" style="bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute</DIV>
              
	<DIV id="end" style="bottom:-90px;left:250px;height:40px;">
	  <br />AHENS &copy; 2012
	</DIV>
    </DIV>
       <DIV class="upLogin">
		         
      
			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
			  </DIV>
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
	
	<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	<FORM action="" method="POST">
	   <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="alert('Please select a campus first');">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="alert('Please select a campus first');">The Ahens Builders</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="alert('Please select a campus first');">Help</DIV>
	   <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out</Button>
	  </FORM>

	   </DIV>
    <DIV class="midPlate" style="top:480px;font-family:verdana;opacity:0.99;">
	<span style="position:absolute;left:430px;font-family:tahoma;font-size: 26px;font-style:bold;">
	Best feature at kornflakes&trade; is here you don't need to create your network.</span>
	<br /><br /><br />
	<DIV style="position:absolute;left:470px;">
	<label style="font-size:14px;">Find Your Campus Here </label><br /><br />
	<INPUT type="text" style="height:30px; width:350px;font-size:16px;" onkeyup="searchCampus(this.value)" onblur="document.getElementById('campRes').innerHTML='';" />
      <span id="campRes"></span>
		
	</DIV>
	<span id="loader" style="position:absolute; left:780px;top:75px;"></span>
	<DIV style="position:absolute;top:36px; left:870px;color:#dddddd; font-family:tahoma;font-size:14px;border-style:solid; border-width:0px 0px 0px 1px; border-color:#aaaaaa;padding:6px;">
	Search your campus & join it.<br /> The whole campus will be your friends.
	<br /> Kornflakes&trade; is a unique in all sense social network,<br /> which you rarely experience!
	</DIV>
    </DIV>
     
    <DIV class="glassSignup" style="position:absolute;top:190px;left:75px;width:330px;height:600px;line-height:85%;color:#aa6060;">
	Welcome!
	<br />
	<span style="font-family:'lucida sans unicode',tahoma;font-size:14px; text-align:left; color:black;">
	It seems you are using kornflakes for the first time or you haven't joined any campus.
	But no need to worry! So simple is getting started at kornflakes&trade; <br /><br />
	<ol>
		<li class="welcome">First thing first</li><br />
		Join your real campus.Real means the campus where you are actually living or once used to live.<br /><br />
		<li class="welcome">Second job to do</li><br />
		Its a bit lengthy but most essential one. Create your profile and make it the best you can. Yes, you guessed right.
		People will judge by standard of your profile.Here is step by step guide for creating profile.<br /><br />
		<li class="welcome">The last thing to do</li><br />
		Project your profile. By projecting it, you are putting your profile in your campus and opening it for reviews by your network.
		Here is our step-by-guide for projecting your profile.
	</ol>	
	</span>

   </DIV>

	<DIV class="cover" id="cvr"></DIV>
	 <DIV class="dialogueBox" id="dbox">
	 <DIV id="upBar">Join Campus</DIV><br />
	 <br /><br /><br />You are about to join this campus! <br />
	 <br />
	 <span id="cname" style="color:#9999ff;"></span><br />
	 <br />
	 <Form method="POST" action="home/index.php">
	 <input type="hidden" id="hidinpt" name="campName" />
	 <button type="submit" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">Join</BUTTON>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type="button" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hide()">Cancel</BUTTON>	
	</Form>
	</DIV>
        <DIV class="dialogueBox" id="dbox2" style="font-size:13px;">
	 <DIV id="upBar">Campus Request</DIV><br />
          <FORM action="" method="get">
	 <br /><br />Please enter the details of the campus you want to add. <br />
          <div style="position:absolute;top:80px;left:50px;text-align:right;">
          Name of the Campus: 
          <br /><br /><br />Address of the campus: 
          <br /><br />City: 
          <br /> <br />State: 
         </div>
		 <div style="position:absolute;top:80px;left:205px;text-align:left;">
		  <input type="text" name="nam" />
		  <br /><br />
		  <INPUT type="text" name="addr" />
		  <br /><br />
		  <INPUT type="text" name="city" />
		  <br /><br />
		  <input type="text" name="state" />
		 </div>
          <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
	 <button name ="requst" type="submit" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">Request</BUTTON>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button type="button" id="button" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hide()">Close</BUTTON>	
	</Form>
	</DIV>
  
 
</BODY>
</HTML>
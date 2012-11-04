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
      $con = mysql_connect("127.0.0.1","<db-username>","<db-password>");
	$conStone = mysql_connect("127.0.0.1:xxxx","<db-username>","<db-password>");
	if(!$con)
	  {
	header("location:http://kf.ahens.com/error/?conerror=1");
		exit;
	  }
	  if(!$conStone)
	  {
	header("location:http://kf.ahens.com/error/?conerror=1");
		exit;
	  }
	mysql_select_db("<db-name>", $con);
	mysql_select_db("<db-name>", $conStone);
	session_start();
	$username='';
	 $indiantime = time()+45000;
      $time =date("F j, Y | g:i a", $indiantime);
	$sql2 = mysql_query("SELECT * FROM corn_users",$con);
	while($row = mysql_fetch_array($sql2))
	{
		if(($row['Email']==$_SESSION['user'])||($row['Email']==$_COOKIE['user']))
		{
		     $saved = $row['profileSaved'];   // to get notification data from here only if profile is not saved
			if(($row['profileSaved'] == 1)&&(!isset($_GET['editPro'])))
					header("Location:profile.php");
			$username= $row['userName'];
			$uid = $row['userID'];
			if($row['campusJoined']=='')
			{
			if($_POST['campName']=='')
			     	{header("location:http://kf.ahens.com/error/noaccess/?access=denied"); exit;}	
			$sqls= "UPDATE corn_users SET campusJoined ='$_POST[campName]' WHERE userID='$row[userID]'";
			mysql_query($sqls,$con);
			$sqlc = "UPDATE campusdata SET networkCount = networkCount+1 WHERE campusCode ='$_POST[campName]'";
			if(mysql_query($sqlc,$con))
				mysql_query("INSERT INTO notification (userID, time) VALUES('$uid','$time')",$con); //notification sidebar
			}
			
		}
	}
	if($uid!=56)	
	mysql_query("INSERT INTO site_stat (userID, page_address,timestamp) VALUES ('$uid','$_SERVER[REQUEST_URI]','$time')",$con);
	$sql3 = mysql_query("SELECT * FROM corn_users",$con); 
	while($updateCamp=mysql_fetch_array($sql3))
	{
	    if(($updateCamp['Email']==$_SESSION['user'])||($updateCamp['Email']==$_COOKIE['user']))
		   $camp = $updateCamp['campusJoined'];
                 
	}	   
	if(isset($_GET['signout']))
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
	if(($_SESSION['user']=='')&&($_COOKIE['user']==""))	
	 {  header("location:http://kf.ahens.com/login/?loginrequired=1"); exit;}
      //notification info
   if($saved ==0)
	  {
	 $not=mysql_query("SELECT flakesID FROM flakes ORDER BY flakesID DESC", $con);
	$notify = mysql_fetch_array($not);
	$_SESSION['lastFlakes'] = $notify[0];	
	$not2=mysql_query("SELECT userID FROM corn_users WHERE campusJoined = '$campCode' ORDER BY userID DESC", $con);
	$notify2 = mysql_fetch_array($not2);
	$_SESSION['lastUser'] = $notify2[0];
	$_SESSION['camp'] = $campCode;
	$not3=mysql_query("SELECT rateID FROM ratinglist WHERE target = '$id' ORDER BY rateID DESC", $con);
	$notify3 = mysql_fetch_array($not3);
	$_SESSION['newRater'] = $notify3[0];
    $_SESSION['id'] = $id;
	$stone = mysql_fetch_row(mysql_query("SELECT stoneID FROM stone ORDER BY stoneID DESC", $conStone));
	$_SESSION['stoneNotif'] = $stone[0];
	$readdata = mysql_fetch_row(mysql_query("SELECT dataID FROM readerdata ORDER BY dataID DESC", $conStone));
	$_SESSION['readNotif'] = $readdata[0];
	}

	
?>

<!DOCTYPE html PUBLIC  "-//W3C//DTD html 4.01 transitional //EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML class="kornflakesProfile" id="editProfile" lang="en">
<HEAD>
      <TITLE> <?php echo $username;?></TITLE>
       <META name="content" description="kornflakes is aiming to create a huge campus network. Log in to your campus, create the best profile you can,  project your profile and speak whatever you can" />
       <LINK type="shortcut icon" href="../images/korn_ico.ico" />
       <LINK type="text/css" rel="stylesheet" href="http://kf.ahens.com/korn_style.css" />
       <SCRIPT type="text/javascript" src="http://kf.ahens.com/korn_script.js"></SCRIPT>
</HEAD>
<BODY class="kornContent" onload="startNotification(), updatenotif(), loginstatus()">
   
    <DIV class="mainContainer"  style="font-family:tahoma;font-size:13px;text-align:center; width:500px;padding-left:194px;padding-right:196px;">
	    <br /> <DIV id="head" style="font-family:tahoma;font-size:42px;">
         <?php echo $username;?>
		 <div style="color:#aa7070;font-size:15px;top:60px;left:194px;height:30px;width:500px;border-style:solid;border-width:0px 0px 1px 0px;border-color:black;">
		 <?php echo $_SESSION['user'];?></div>
		 </DIV>
		 <DIV class="DPbox" id="dpb" style="color:purple;font-size:13px;" onclick="showfiled()">
		 <?php
		   $pic =mysql_query("SELECT * FROM pictures WHERE userID = '$uid'",$con);
		   $getpic = mysql_fetch_array($pic);
		   if($getpic==NULL)
		 { ?>
		 <br /><br /> Upload your Picture Here<br /><br />Fake celebrity picture will be removed as identified
		 <?php  }
		   else
		    { ?><IMG src="<?php echo $getpic['picPath'].$getpic['picName'] ; ?>" width="140px" height="180px" />
			<?php }
			?>
		    
		 </DIV>
		 		 <SCRIPT type="text/javascript">
		 function showfiled()
		   {
		   
		     document.getElementById("cvr").style.visibility="visible";
		     document.getElementById("filed").style.visibility="visible";
		   }
		   function hidef()
		   {
		      document.getElementById("filed").style.visibility="hidden";
		      document.getElementById("cvr").style.visibility="hidden";
		    }  
		 </SCRIPT>
		 <FORM action="profile.php" method="post">

		  <DIV style="font-family:tahoma;font-size:11px;color:#aaaaaa;border-style:none;">
		  Your profile is one stop station for opening up to your campus network. You can let the campus know about you, prove your
		 wit, humour and intelligence by your cheesy answers and let people rate you.</DIV>
		 <br />
		 <?php 
		       $query = mysql_query("SELECT * FROM profiles WHERE email ='$_SESSION[user]' OR email ='$_COOKIE[user]'",$con);
               $getPro = mysql_fetch_array($query);
                if(($getPro['email']!='') && ($getPro!=null))
				  {
							$type = $getPro['profileType'];
							$town = $getPro['homeTown'];
							$course = $getPro['course'];
							$sub = $getPro['subject'];
							$year = $getPro['year'];
							$last = $getPro['lastInstitute'];
							$interest = $getPro['areaInterest'];
							$exprt = $getPro['fieldExpertise'];
							$learn = $getPro['currentLearning'];
							$read = $getPro['currentReading'];
							$place = $getPro['favoritePlace'];
							$pStat = $getPro['profileStatement'];
							$q = array($getPro['q1'],
										$getPro['q2'],
										$getPro['q3'],
										$getPro['q4'],
										$getPro['q5']);
							$dobDay = $getPro['dobDay'];
							$dobMonth = $getPro['dobMonth'];
							$dobYear = $getPro['dobYear'];
					}	
				
			
			?>
		
		<DIV id="basicInfo" class="proForm2" style="text-align:left;padding:6px;">
		 Profile Type: <SELECT name="type" selected="<?php echo $type;?>"><OPTION>Student</OPTION><OPTION>Instructor</OPTION></SELECT>
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hometown: <input class="simple" type="text" name="town" value="<?php echo $town;?>"/>
         <br /><br />
		 Course/Qualification: <SELECT name="course">
							<OPTION></OPTION>
							<OPTION value="Ph.D." <?php if($course=='Ph.D.') echo "SELECTED";?>>Ph.D.</OPTION>
							<OPTION value="MBA" <?php if($course=='MBA') echo "SELECTED";?>>MBA</OPTION>
							<OPTION value ="M.Tech." <?php if($course=='M.Tech.') echo "SELECTED";?>>M.Tech.</OPTION>
							<OPTION value ="M.Sc." <?php if($course=='M.Sc.') echo "SELECTED";?>>M.Sc.</OPTION>
							<OPTION value="MCA" <?php if($course=='MCA') echo "SELECTED";?>>MCA</OPTION>
							<OPTION value="M.Com." <?php if($course=='M.com.') echo "SELECTED";?>>M.Com.</OPTION>
							<OPTION value="B.Tech." <?php if($course=='B.Tech.') echo "SELECTED";?>>B.Tech.</OPTION>
							<OPTION value="B.Arch." <?php if($course=='B.Arch.') echo"SELECTED";?>>B.Arch.</OPTION>
							<OPTION value="B.Pharm." <?php if($course=='B.Pharm.') echo"SELECTED";?>>B.Pharm.</OPTION>
							<OPTION value="B.Sc." <?php if($course=='B.Sc.') echo "SELECTED";?>>B.Sc.</OPTION>
							<OPTION value="BCA" <?php if($course=='BCA') echo "SELECTED";?>>BCA</OPTION>
							<OPTION value="B.Com." <?php if($course=='B.Com.') echo "SELECTED";?>>B.Com.</OPTION>
							<OPTION value="B.A." <?php if($course=='B.A.') echo "SELECTED"; ?>>B.A.</OPTION>
					   </SELECT>
				&nbsp;&nbsp;Subject/Branch: <input class="simple" type="text" name="subject" value="<?php echo $sub; ?>"/><br /><br />
			Year: &nbsp;&nbsp;&nbsp;&nbsp;<SELECT name="courseYear" selected ="selected" value="<?php echo $year;?>">
							<OPTION></OPTION>
							<OPTION value="I Year" <?php if($year=='I Year') echo "SELECTED";?>>I Year</OPTION>
							<OPTION value="II Year" <?php if($year=='II Year') echo "SELECTED";?>>II Year</OPTION>
							<OPTION value="III Year" <?php if($year=='III Year') echo "SELECTED";?>>III Year</OPTION>
							<OPTION value="IV Year" <?php if($year=='IV Year') echo "SELECTED";?>>IV Year</OPTION>
							<OPTION value="V Year" <?php if($year=='V Year') echo "SELECTED";?>>V Year</OPTION>
					   </SELECT>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Of Birth: <SELECT name="day">
					<OPTION> Day </OPTION><OPTION <?php if(($dobDay!='')&&($dobDay!='Day')) echo "SELECTED";?>><?php echo $dobDay;?></OPTION>
				    <OPTION> 1 </OPTION><OPTION> 2 </OPTION><OPTION> 3 </OPTION><OPTION> 4 </OPTION><OPTION> 5 </OPTION><OPTION> 6 </OPTION>
					<OPTION> 7 </OPTION><OPTION> 8 </OPTION><OPTION> 9 </OPTION><OPTION> 10 </OPTION><OPTION> 11 </OPTION><OPTION> 12 </OPTION>
					<OPTION> 13 </OPTION><OPTION> 14 </OPTION><OPTION> 15 </OPTION><OPTION> 16 </OPTION><OPTION> 17 </OPTION><OPTION> 18 </OPTION>
					<OPTION> 19 </OPTION><OPTION> 20 </OPTION><OPTION> 21 </OPTION><OPTION> 22 </OPTION><OPTION> 23 </OPTION><OPTION> 24 </OPTION>
					<OPTION> 25 </OPTION><OPTION> 26 </OPTION><OPTION> 27 </OPTION><OPTION> 28 </OPTION><OPTION> 29 </OPTION><OPTION> 30 </OPTION>
					<OPTION> 31 </OPTION>
					</SELECT>
					<SELECT name="month">
					<OPTION> Month </OPTION><OPTION <?php if(($dobMonth!='')&&($dobMonth!='Month')) echo "SELECTED";?>><?php echo $dobMonth;?></OPTION>
					<OPTION> January </OPTION><OPTION> February </OPTION><OPTION> March </OPTION><OPTION> April </OPTION><OPTION> May </OPTION>
					<OPTION> June </OPTION><OPTION> July </OPTION><OPTION> August </OPTION><OPTION> September </OPTION><OPTION> October </OPTION>
					<OPTION> November </OPTION><OPTION> December </OPTION>
					</SELECT>
					<SELECT name="year"><OPTION> Year </OPTION><OPTION <?php if(($dobYear!='')&&($dobYear!='Month')) echo "SELECTED";?>><?php echo $dobYear;?></OPTION>
					<option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option>
					</SELECT>
			        </DIV>
					<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
					 <DIV style="width:500px; background-color:#dedede;text-align:left;padding:6px;"> 
						
						
						<Label style="font-family:verdana;font-size:15px;">Favorite Place in Campus : </label> <input class="simple" type="text" name="favPlace" value="<?php echo $place;?>" style="width:290px;"/>
						 </DIV>
						<br /><br />
				   <DIV style="width:500px; background-color:#dedede;text-align:left;padding:6px;"> 
						<Label style="font-family:verdana;font-size:15px;">Profile Statement : </label><input class="simple" type="text" name="stat" value="<?php echo $pStat;?>" style="width:347px;" />
					</DIV>	
						<span style="color:#aaaaaa;font-size: 11px;">Your profile statement is just one sentence which defines you. It should not be more than 140 characters.</span><br /><br />
						
					
					 <br /><br />
					 <DIV class="kTeasers" style="position:absolute;left:10px;width:815px;padding:6px;">
					
					  <?php
					$i =0;
					$teas = mysql_query("SELECT teaser, teaserID FROM kteasers WHERE active = 1",$con);
					  while($getTeaser = mysql_fetch_row($teas))
					  {
					   echo $getTeaser[0]; ?> <br /><br /><Input type="text" class="simple" name="q<?php echo $i+1; ?>" value="<?php echo $q[$i];?>" style="height:25px;width:450px;text-align:center;font-family:verdana;"/><br /><br />
					   <input type="hidden" value="<?php echo $getTeaser[1];?>" name="t<?php echo $i+1 ;?>" /> 
					   <?php
								$i++;
						}
					?>	<br /><br />
					   <!--  If time were money, then watches should be: <br /><br /><input type="text" class="simple" name="q2" value="<?php echo $q2;?>" style="height:25px;width:450px;text-align:center;font-family:verdana;"/><br /><br />
					  If you wakeup one day to know that world will be ending next day, what will you do? <br /><br /><input type="text" class="simple" name="q3" size="35" style="height:25px;width:450px;text-align:center;font-family:verdana;" value="<?php echo $q3;?>"/><br /><br />
					  Write one sentence to convince people that you are not dumb:<br /><br /> <input type="text" class="simple" name="q4" value="<?php echo $q4;?>" style="height:25px;width:450px;text-align:center;font-family:verdana;"/><br /><br />
					  If tears tasted like strawberries, then:<br /><br /><input type="text" class="simple" name="q5" value="<?php echo $q5;?>"  style="height:25px;width:450px;text-align:center;font-family:verdana;" /><br />
					  <span style="color:#aaaaaa;font-size: 11px;">Show your wit as soon as possible, beacuse these five teasers<br />are responsible for most of the ratings.
					  </span><br /><br />-->
					<DIV style="position:absolute; bottom:5px; right:0px;"><img src="http://kf.ahens.com/images/kTeasers2.jpg" alt="kTeasers logo" /></DIV>
					 
					 </DIV>  <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
					 <DIV class="proForm2" style="width:500px;text-align:center;padding-top:6px;">
					
				
					 Last Institute attended: <br /><input class="simple" type="text" size="40" name="last" value="<?php echo $last;?>"/><br /><br />
					 Area Of Interest:<br />
					 <input class="simple" type="text" size="40" name="intrst" value="<?php echo $interest;?>"/> <br /><br />
					 Field Of Expertise/Skills I Possess: <br /><input class="simple" type="text" size="40" name="exprt" value="<?php echo $exprt;?>"/>  <br /><br />
					 What am I learning right now: <br /><input class="simple" type="text" size="30" name="learning" value="<?php echo $learn;?>"/><br /><br />
					 What am I reading right now : <br /><input class="simple" type="text" size="30" name="reading" value="<?php echo $read;?>"/><br /><br />
					 </DIV><br /><br />
					
					 <DIV>
					 <br />
					 <?php if($saved==0)                 //for displaying update button only if profile have been saved
					   { ?>

					 <button type= "submit" name="project" id="button" style="width:170px;" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">SAVE & PROJECT</button>		
					 <?php } 
					     else
						   {?>
			          <button type= "submit" name="update" id="button" style="width:170px;" onmouseover="this.style.backgroundColor='#ccccdd';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">UPDATE PROFILE</button>
						 <?php } ?></DIV>
		        
		   </FORM>
		   <br /><br />
				<!--	<DIV class="lowerBar" style="position:absolute;bottom:-35px;left:0px;">about..................people...................ahens&reg;..................terms..................privacy..................help..................contribute<br /><br /><br /> </DIV> -->
        <DIV id="end" style="position:absolute;bottom:-90px;left:250px;height:40px;">
	     <br />AHENS &copy; 2012
	  </DIV>	
	</DIV>
	 <div class="bottombar" style="left:0px;width:1347px;"> 
     <div style="position:absolute; left: 30px;top: 2px;">
           <img src="http://kf.ahens.com/images/ahensfinalsmall.png" />
     </div>		   
    <div style="position:absolute; left:600px; top:10px;color:#656565;">
     ahens &copy; 2012
	 </div>
     <div style="position:absolute; left:1060px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/about.php';"> About </div>
	 <div style="position:absolute; left:1160px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/help.php';"> Help </div>
	 <div style="position:absolute; left:1260px; top:10px;" id="link" onclick="location.href='http://kf.ahens.com/home/ahens/privacy.php';"> Privacy </div>
  </div>
   
    <!--     <DIV class="upLogin" style="font-size:14px;">
				<span class="upperLink" style="left:10px;" onclick="location.href='search/';">Search Network</span>
				<span class="upperLink" style="left:160px;" onclick="location.href='../home/';">My Profile</span>
<span style="position:absolute;top:5px;left:305px;"><INPUT type="text" size="40" style="color:#989898;border:groove 1px #ff8080;text-align:center;font-family:'lucida sans unicode';" class="simple" id="qs" value="Quick Search" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $camp; ?>')"/></span>				
			
	          <DIV id="menuUser" onmouseover="menuOver()" onmouseout="menuOut()" onclick="showHideMenu()">
                  <?php echo $username;?>
                  </DIV>
            </DIV> -->
              <DIV id="searchbox" style="text-align:center;"></DIV>
			
	
<!--	<DIV class="menu" id="menu" onmouseover="menuOver()" onmouseout="menuOut()">
	<FORM action="index.php" method="GET">
	   <DIV class="menuin" style="position:absolute;top:10px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/settings.php';">Settings</DIV>
	   <DIV class="menuin" style="position:absolute;top:45px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/theahensbuilders.php';">The Ahens Builder</DIV>
	   <DIV class="menuin" style="position:absolute;top:80px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';" onclick="location.href='ahens/help.php';">Help</DIV>
	   <button name="signout" type="submit" class="menuin" style="position:absolute;top:115px;cursor:pointer;" onmouseover="this.style.backgroundColor='#dddddd';" onmousedown="this.style.backgroundColor='#aa5656';" onmouseup="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">Log Out</button>
	 
     </FORM>
	   </DIV>  -->
    <DIV class="midPlate" style="top:425px;">
	<span style="position:absolute;left:430px;font-family:tahoma;font-size: 26px;font-style:bold;">
	<?php 
	        $brow = mysql_query("SELECT * FROM campusdata",$con);
			while($r = mysql_fetch_array($brow))
			  {
				if($r['campusCode']==$camp)
				{
				  
			      echo $r['campusName'];
			       ?> </span><br /><span style="position:absolute;left:430px;top: 40px;color:#cccccc;">
				   <?php
			     if($r['Address1']!='')
			           echo $r['Address1'].","."<br />";
			       if($r['Address2']!='')
			             echo $r['Address2'].","."<br />";
			         if($r['City']!='')
			             echo $r['City'].","."<br />";
			           if($r['State']!='')
			                echo $r['State'].","."<br />";
			             if($r['Country']!='')
			                    echo $r['Country']; 
				   ?></span><span style="position:absolute;left:915px;font-size:14px;font-family:verdana;top:34px;border-style:solid;border-width:0px 0px 0px 1px;padding-left:6px; color:#bbbbbb;"><?php
				   echo $r['Established']." Established<br />";
				   ?><span style="font-size:26px;">
				   <?php
				   echo $r['networkCount']. "</span> People in network<br />";
				   ?><span style="font-size:26px;"><?php
				   echo $r['projectionCount']."</span> Projected profiles";
				   ?></span><?php
				}
			  }
			 ?>
	
    </DIV>
     <div class="deck">
		<div class="decklink" onclick="location.href='http://kf.ahens.com/home/search';">Search</div>
		<div class="decklink" style="left:270px;" onclick="location.href='http://kf.ahens.com/home/profile.php';">Profile</div>
		<div style="position:absolute;top:5px;left:490px;">
		  <input type="text" value="Enter a name to search" id="qs" class="qsearchtextbox" onfocus="clears(this.value)" onblur="get(this.value)" onkeyup="quicksearch(this.value,'<?php echo $campCode; ?>')"/>
		 </div> 
		 <div id="backProEff" onclick="document.getElementById('menunew').style.visibility='hidden';this.style.visibility='hidden';"> </div>
		 <div id="callmenu" style="position:absolute;top:1px;left:950px;cursor:pointer;"><img src="http://kf.ahens.com/images/profile.png" border=0 /></div> 
				   
				   <script>
					  var cal = document.getElementById("callmenu");
					  var shown=0;
					  cal.onclick = function()
								{
								wtimer(); 
								if(shown==0)
								 {
									document.getElementById('menunew').style.visibility='visible';
									document.getElementById('backProEff').style.visibility='visible';
									shown =1;
								  }
								 else
									{
									  document.getElementById('menunew').style.visibility='hidden';
									  document.getElementById('backProEff').style.visibility='hidden';
									  shown=0;
									}  
								};
								
					</script>			
		    <div class="menunew" id="menunew">
			  
				   <div style="position:absolute; top:-21px; right:40px;width:0;height:0; border-right:20px solid transparent;border-left:20px solid transparent;border-bottom:20px solid #aaaaaa;">
			       </div>	
				   <div style="position:absolute; top:-20px; right:40px;width:0;height:0; border-right:20px solid transparent;border-left:20px solid transparent;border-bottom:20px solid rgb(247,247,247);">
			       </div>
                   <div style="padding:8px;border-bottom:1px solid #aaaaaa;font-weight:bold;font-size:15px;"><?php echo $username; ?> </div>
					<div style="padding:8px;" id="menulnk" onclick="enlarge('timer',11,0)"> Whistle <div style="position:absolute;right:5px;font-style:italic;font-family:arial;font-size:11px;display:inline-block;;color:#777777;" id="timer"></div></div> 
					<div style="padding:8px;" id="menulnk" onclick="location.href='http://kf.ahens.com/home/ahens/theahensbuilders.php';">Credits</div>
					<div style="border-bottom:1px solid #cccccc;"></div> 
					<div style="padding:8px;" id="menulnk" onclick="location.href='http://kf.ahens.com/home/ahens/help.php';">Help</div>
					<div style="padding:8px;" id="menulnk" onclick="location.href='http://kf.ahens.com/home/ahens/settings.php';">Settings</div>
					<div style="border-bottom:1px solid #cccccc;"></div> <form action="" method="get">
                     <button style="padding:8px;border-style:none;background-color:transparent;width:190px;text-align:left;font-family:arial;" id="menulnk" type="submit" name="signout" >Log out</button></form>
					<div style="padding:0px 5px 5px 5px;font-size:9px;color:#606060;font-family:tahoma;">AHENS TRIVIA : The name 'ahens' was generated accidently, through a very simple program written in C !
						</div>
			</div> 
  </div>
 <DIV class="glassSignup2">
	<DIV class="sideLink2" id="sideLnk1" onmouseover="sidelinkover('sideLnk1')" onmouseout="sidelinkout('sideLnk1','Common Room')" onclick="location.href='users/commonroom.php';">
	Common Room
	</DIV>
    <DIV class="sideLink2" id="sideLnk2">
	<Form action="index.php" method ="get"><button id="ed" type="submit" name="editPro" style="font-size:15px;font-family:'tahoma';cursor:pointer;border-style:none;background-color:white;color:rgb(40,40,40);">Edit Profile &gt;</button></form>
	</DIV>
    <DIV class="sideLink2" id="sideLnk3" onmouseover="sidelinkover('sideLnk3')" onmouseout="sidelinkout('sideLnk3','Mirror')" onclick="location.href='mymirror.php';">
	Mirror
	</DIV>
	<DIV class="sideLink2" id="sideLnk4"  onmouseover="sidelinkover('sideLnk4')" onmouseout="sidelinkout('sideLnk4','Flakes&trade;')" onclick="location.href='flakes.php';">
	Flakes&trade;
	</DIV> 
	<DIV class="sideLink2" id="sideLnk5"  onmouseover="sidelinkover('sideLnk5')" onmouseout="sidelinkout('sideLnk5','Stone&trade;')" onclick="location.href='http://kf.ahens.com/home/stone';">
	Stone&trade;
	</DIV>
	<DIV style="font-weight:bold;" class="sideLink2" id="sideLnk6"  onmouseover="sidelinkover('sideLnk6')" onmouseout="sidelinkout('sideLnk6','The New Home')" onclick="location.href='http://kf.ahens.com/home/home.php';">
	 The New Home 
	</DIV><br />
	 <span style="font-size:15px;"> A new beautiful profile, soon will make your everyday an equal beauty. </span>

  
 
	
    </DIV> 
    
	<DIV class="cover" id="cvr"></DIV>
	
	 	 <DIV class="dialogueBox" id="filed">
	 <DIV id="upBar">Upload your image</DIV><br />
	<FORM id='d' target="get_data" method="POST" enctype="multipart/form-data" action="dpUpload.php"><br /><br />
	<div style="position:absolute;top:90px;left:190px;"><INPUT type="file" name="dp" accept="image/*" /></div><br /><br /><br /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<button name="preview" id="button" type="submit" onmouseover="this.style.backgroundColor='#ccccdd';document.getElementById('d').target='get_data';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';"> Preview </button>
	<iframe name="get_data" style="position:absolute;left:20px;top:80px;width:140px;height:180px;" marginheight="0px" marginwidth="0px" scrolling="no" frameborder=0></iframe>	
	
	 <DIV style="position:absolute;top:240px;left:202px;">
	<button type= "submit" id="button" name="save" onmouseover="this.style.backgroundColor='#ccccdd';document.getElementById('d').target='get_data';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';">Save</button>
	&nbsp;&nbsp;
	<button name="close" type= "submit" id="button" onmouseover="this.style.backgroundColor='#ccccdd';document.getElementById('d').target='_self';" onmouseout="this.style.backgroundColor='#dddddd';" onmousedown="this.style.borderStyle='ridge';" onmouseup="this.style.borderStyle='groove'; this.style.backgroundColor='#dddddd';" onclick="hidef()">Close</button>
	</DIV></FORM>
	 </DIV>
	 <DIV id="notif"></DIV> 
	 	 <DIV class="allnotification" id="shownotif" onclick="showallnotif(0,15)"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> All Notifications</li></DIV>
	 <DIV class="allnotification" style="visibility:hidden;" id="hidenotif" onclick="hidenotif()"> <li style="font-family:tahoma;font-size:14px;text-align:center;color:black;"> Hide All</li></DIV>
	 <div id="notiFrame"><Div id="content"></DIV> <div id="more" style="text-align:center;"></div></div>
</BODY>
</HTML>
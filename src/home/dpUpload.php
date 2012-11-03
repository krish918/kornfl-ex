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
		header("location:http://kf.ahens.com/error.html");
		exit;
	  }
	mysql_select_db("<db-name>", $con);
	session_start();
        $s2 = mysql_query("SELECT * FROM corn_users");
	if((isset($_SESSION['user']))||(isset($_COOKIE['user'])))
	  {
	     while($row3=mysql_fetch_array($s2))
		 {
			if(($row3['Email']==$_SESSION['user'])||($row3['Email']==$_COOKIE['user']))
			{
			    $uid=$row3['userID'];
			 }
	          }
	   }    
   	$target ="/home/content/88/9307588/html/kornflex/images/temp/".basename($_FILES['dp']['name']);  
   if(isset($_POST['close']))
      {
       @unlink($target);
       header("Location:index.php");
      }	    
 ?>
<HTML>
<BODY>
<?php
if($_FILES['dp']['name']=='')
  { 
  ?>
   <br /><br /><br />
    <DIV style="padding:2px;background-color:#ffcccc;border:solid 1px #ff9090;font-family:tahoma;font-size:13px;"> No picture chosen</DIV>
   <?php }

else if(($_FILES['dp']['type']!="image/gif") &&($_FILES['dp']['type']!="image/jpeg") && ($_FILES['dp']['type']!="image/bmp")&&($_FILES['dp']['type']!="image/pjpeg")&&($_FILES['dp']['type']!="image/png"))
  { ?> 
       <br /><br /><br />
    <DIV style="padding:2px;background-color:#ffcccc;border:solid 1px #ff9090;font-family:tahoma;font-size:13px;"> File type unsupported. Please upload only gif, jpg, bmp & pjpeg files.</DIV>
   <?php }
   
else if($_FILES['dp']['size']>600000)
   { ?>
       <br /><br /><br />
      <DIV style="padding:2px;background-color:#ffcccc;border:solid 1px #ff9090;font-family:tahoma;font-size:13px;"> Pictures greater than 600KB can't be uploaded. Please choose another picture.</DIV>
    <?php }


else if(isset($_POST['preview']))
{   
     $target ="/home/content/88/9307588/html/kornflex/images/temp/".basename($_FILES['dp']['name']);  
    move_uploaded_file($_FILES['dp']['tmp_name'],$target);
    {
 ?>
    <IMG src="http://kf.ahens.com/images/temp/<?php echo basename($_FILES['dp']['name']); ?>" width="140" height="180"/>
<?php   }

      }

else if(isset($_POST['save']))
   {
    $save ="/home/content/88/9307588/html/kornflex/home/users/displayPic/".basename($_FILES['dp']['name']);
    (move_uploaded_file($_FILES['dp']['tmp_name'],$save));
    $name = $_FILES['dp']['name'];
    $find= mysql_query("SELECT userID FROM pictures WHERE userID ='$uid'",$con);
    @unlink($target);
    if($name!='')
    {
    if(mysql_fetch_row($find)==NULL)
       $sql="INSERT INTO pictures (userID,picName) VALUES ('$uid', '$name')";
    else
       $sql ="UPDATE pictures SET picName ='$name' WHERE userID = '$uid'";
    if(mysql_query($sql,$con))
      { ?>
        <br /><br /><br />
      <DIV style="padding:2px;background-color:#ffcccc;border:solid 1px #ff9090;font-family:tahoma;font-size:13px;"> Your profile picture has been changed.</DIV>
      <?php }
    
    else
       echo "Some error ocurred! Please try later!"; 
     }
    }


 ?>
</BODY>
</HTML>
  
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
        echo "Some error occured at ahens servers! We will soon work it out. Please try later! ";
        exit;
       } 
    mysql_select_db("<db-name>",$con);
    $sql ="SELECT * FROM profiles WHERE campusCode ='$_GET[campusName]' AND projected = 1";
    $count =0;
    $county=0;
	$str = $_GET['queryString'];
    $exe = mysql_query($sql,$con);
	$id = array();
?>
<HTML>
 <HEAD>
   <LINK rel="stylesheet" type="text/css" href="../profile_style.css" />
   <style type="text/css">
      a.namLink
	   {
	    text-decoration:none;font-size:16px;font-weight:bold;color:#9090ff;
		 }
      a.namLink:hover
	      {
		  color: #90bb90;
		  }
	#divide
		  {
		   width:500px;
		   border-style:solid;
		   border-width:0px 0px 1px 0px;
		   border-color:#ccccff;
		   }
	#divid
		  {
		   width:261px;
		   border-style:solid;
		   border-width:0px 0px 1px 0px;
		   border-color:#ccccff;
		   }	   
   </style>		  
 </HEAD>
  <BODY>
   <?php
  if(($_GET['queryString']!='')&&($_GET['campusName']!=''))
    {
    $length = strlen($str);
      while($bring = mysql_fetch_array($exe))
	    {
		  if(strncasecmp($str,$bring['fullName'],$length)==0)
		   {
		    $id[$count]=$bring['userID'];
		    echo "<br /><div style='width:500px;text-align:left;font-family:tahoma;font-size:14px;'><a class='namLink' href='../users/showProfile.php?userID=".$id[$count]."'>".$bring['fullName'].",</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$bring['course']." ".$bring['year']."<br /><span style='font-family:verdana;font-style:italic;'>".$bring['subject']."</span></div><br /><div id='divide'></div>";
			$count++;
		   }
        }
        if($count==0)
        {
          echo "<br /><div style='width:500px;background-color:#ffcccc;border:solid 1px #ff9090;font-family:tahoma;font-size:13px;'><span style='font-weight:bold;'>Sorry!</span><br /><br />".$str." was not found at kornflakes.<br />If You know ".$str." outside kornflakes, you can invite him/her.<br /><br /></div>";  		
        }  
    }
 if($_GET['campCode']!='')
      {
        $as= mysql_query("SELECT * FROM profiles WHERE campusCode ='$_GET[campCode]' AND projected =1 ORDER BY fullName",$con);
         while($all = mysql_fetch_array($as))
             {
              echo "<br /><div style='width:500px;text-align:left;font-family:tahoma;font-size:14px;'><a class='namLink' href='../users/showProfile.php?userID=".$all['userID']."'>".$all['fullName'].",</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$all['course']." ".$all['year']."<br /><span style='font-family:verdana;font-style:italic;'>".$all['subject']."</span></div><br /><div id='divide'></div>"; 			 
	          $countA++;
              }
	      if($countA==0)
           {
             echo "<br /><div style='font-weight:bold;font-size:14px;text-align:center;color:#ff8080;'>SORRY!</div><div style='font-size:13px;text-align:center;'> No users found!</div><br />";  	   
            }
        }			
  if(($_GET['queryString']!='')&&($_GET['cname']!=''))
     {
     $len = strlen($str);
      $sql =mysql_query("SELECT * FROM profiles WHERE campusCode ='$_GET[cname]' AND projected = 1",$con);
      while($getRes=mysql_fetch_array($sql))
         {
            if(strncasecmp($str,$getRes['fullName'],$len)==0)
             {
                $id[$county]=$getRes['userID'];
		    ?>
		<div style="text-align:left;font-family:tahoma;font-size:13px;cursor:pointer;border-style:solid;border-width:0px 0px 1px 0px;border-color:#ccccff;" onmousedown="location.href='http://kf.ahens.com/home/users/showProfile.php?userID=<?php echo $id[$county]; ?>';" onmouseover="this.style.backgroundColor='#dddddd';" onmouseout="this.style.backgroundColor='#eeeeee';">
		<span style="font-weight:bold;color:#90bb90;"><?php
		echo $getRes['fullName']."</span>&nbsp;&nbsp;&nbsp;&nbsp;".$getRes['course']." ".$getRes['year'];?>
		<br />
		<span style='font-family:verdana;font-style:italic;'><?php
		echo $getRes['subject']; ?>
		</span></div> <?php
			$county++;
	       if($county==8)
	         {?>
	        <span style="cursor:pointer;font-size:12px;color:blue;" onmousedown="location.href='http://kf.ahens.com/home/search/';">Search More</span>
	       <?php 
	         break;
	         }		
              }
       
       } 
     
    
      if($county==0)
           echo "<br /><div style='font-weight:bold;font-size:14px;text-align:center;color:#ff8080;'>SORRY!</div><div style='font-size:13px;text-align:center;'> ".$str." was not found at Kornflakes</div><br />";  	   
     }
	$countA=0;
	
  ?> 
 </BODY>
 </HTML>  
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
		header("location:http://www.ahens.com/error.html");
		exit;
	     }
      mysql_select_db("<db-name>",$con);
      $indiantime = time() + 45000;
      $time = date("F j, Y | g:i a",$indiantime);
 
           
     
     if(($_GET['thread']!="") && ($_GET['from']!='') && ($_GET['to']!=''))
        {
        $threadRefine = htmlspecialchars($_GET['thread']);
        $threadRefine= trim($threadRefine);
        
        $sql1=mysql_query("SELECT * FROM corn_users",$con);
        while($row = mysql_fetch_array($sql1))
        {
          if($row['userID']==$_GET['from'])
              $name=$row['userName'];
        } 
        $posted =0;     
        $insertMirror="INSERT INTO mirror (threadSource,target,thread,time) VALUES ('$_GET[from]','$_GET[to]','".$threadRefine."','$time')";
        if(mysql_query($insertMirror,$con))
         {
          $count=0;
          $retrv1 = mysql_query("SELECT * FROM mirror ORDER BY threadID DESC", $con);
          while($re1 = mysql_fetch_array($retrv1))
          {
            if(($re1['threadSource']==$_GET['from'])&& ($re1['target']==$_GET['to']))
            {
             $threadID= $re1['threadID'];
            
           echo "<br /><DIV style='font-weight:bold;font-size:13px;border-style:solid;border-color:#ccddff;border-width:0px 0px 1px 0px;padding-bottom:10px;'>".$name." says : <br /><br /><span style='font-weight:normal;font-size:13px;'>".nl2br($re1['thread'])."<br /><br /></span><span style='font-weight:normal;font-size:11px;'>".$re1['time']."</span>";
           ?> | <span style="cursor:pointer;color:#ff9090;font-size:11px;font-weight:normal;" onclick="location.href='mirrorFetchUpdate.php?threadid=<?php echo $threadID; ?>&userid=<?php echo $re['target']; ?>';">Delete</span></DIV><?php
           $posted =1;
           $count++;
          }
        } 
        if($posted==0)
          echo "Some error Ocurred at ahens Server. Please try later!"; 
        if($count!=0)
          echo "<span style='color:green;font-weight:bold;'>Total ".$count." Posts returned!</span>";   
      }
      } 
	 
     if(($_GET['source']!='')&&($_GET['target']!=''))
       {
          $get = mysql_query("SELECT userName FROM corn_users WHERE userID = '$_GET[source]'");
          $get2 = mysql_fetch_row($get); 
          $name= $get2[0];
          $count=0;
          $retrv = mysql_query("SELECT * FROM mirror ORDER BY threadID DESC",$con);
          while($re = mysql_fetch_array($retrv))
             {
                if(($re['threadSource']==$_GET['source']) && ($re['target']==$_GET['target']))
                    {
                       $threadID= $re['threadID'];
					   $link_pattern = '/\b(http:\/\/)*(https:\/\/)*[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-][a-zA-Z0-9\-]+\.*[^\s][^\s]+/';
              			preg_match($link_pattern, $re['thread'], $matches);
		       			$replace="<a href='$matches[0]'>$matches[0]</a>";
              			$mod_threads=str_ireplace($matches[0], $replace, $re['thread']); 
                       echo "<br /><DIV style='font-weight:bold;font-size:13px;border-style:solid;border-color:#ccddff;border-width:0px 0px 1px 0px;padding-bottom:10px;'>".$name." says : <br /><br /><span style='font-weight:normal;font-size:13px;'>".nl2br($mod_threads)."<br /><br /></span><span style='font-weight:normal;font-size:11px;'>".$re['time']."</span>";
                        ?>  | <span style="cursor:pointer;color:#ff9090;font-size:11px;font-weight:normal;" onclick="location.href='mirrorFetchUpdate.php?threadid=<?php echo $threadID; ?>&userid=<?php echo $re['target']; ?>';">Delete</span></DIV><?php
                       $count++;
                    }
                   
              }
             
          if($count!=0)
             echo "<span style='color:green;font-weight:bold;'><br />Total ".$count." Posts returned!</span>";
                
       }
	   
           if($_GET['threadid']!='' && $_GET['userid']!='')
        {
           $u = $_GET['userid'];
           if(mysql_query("DELETE FROM mirror WHERE threadID= '$_GET[threadid]'",$con))
           header("Location:peerMirror.php?userid=$u&delete=1");
           else
             echo "Some error ocurred at ahens servers.";
            
        }   
       ?>      
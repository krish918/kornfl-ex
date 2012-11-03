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
 
 $conStone=mysql_connect("127.0.0.1:xxxx", "<db-username>", "<db-password>");
	  if(!$conStone)
	     {
		header("location:http://www.ahens.com/error.html");
		exit;
	     }
	mysql_select_db("<db-name>",$conStone);
	$sql = mysql_fetch_array(mysql_query("SELECT story, brief FROM stone WHERE stoneID = '$_GET[id]'",$conStone));
	 $story= str_split($sql['story'], 220);
	 echo $sql['brief']."<br /><DIV style='color:#909090;font-size:11px;'>".$story[0]." ...<DIV>";
?>	 
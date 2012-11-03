<?php
    $ref= "na";
	$ref= $_SERVER['HTTP_REFERER'];
	$message1 = "An error was encountered from user in DB connection possible at : ".$ref;
 $message_user = "Dear User,"."\n\n"."We are really sorry for inconvenience caused to you, due to error
	                     encountered by you recently."."\n\n"."We are looking into the matter. Please rest assured about
						the quick recovery of the system."."\n"."No harm has been caused to your data".
						"\n\n\n"."The AHENS team";
    if($_GET['conerror']==1)
	 { 
	   $message1 = "An error was encountered from user in DB connection possible at : ".$ref;
	   mail("s3_krish@yahoo.co.in", "ahens : Error in DB Connection", $message);
	 } 
	if($_GET['redundancyerror']==1)
	 { 
	   $message2 = "An error was caused during logging in." . "\n"."Error: Data redundancy"."\n"."User: ".$_GET['email']
	                 ."\n"."possible referer : ".$ref;
       mail("s3_krish@yahoo.co.in","ahens : Error in login", $message2);
	   mail($_GET['email'], "AHENS Regrets", $message_user);
	 } 
	if($_GET['error']==333)
     {
        $message2 = "An error was caused during logging in." . "\n"."Error: Can't SET lastlogin,logincount"."\n"."User: ".$_GET['email'] ."\n"."possible referer : ".$ref;  	 
	    mail("s3_krish@yahoo.co.in","ahens : Error in login", $message2);

	   mail($_GET['email'], "AHENS Regrets", $message_user);
	 }  
	 	if($_GET['error']==444)
     {
        $message3 = "An error was caused during logging in." . "\n"."Error: Can't Insert Session_data"."\n"."User: ".$_GET['email'] ."\n"."possible referer : ".$ref;  	 
	    mail("s3_krish@yahoo.co.in","ahens : Error in login", $message3);
	   mail($_GET['email'], "AHENS Regrets", $message_user);
	 }  
	if($_GET['error']==555)
     {
        $message4 = "An error was caused during sending recovery mail." . "\n"."Error: Can't send recovery mail"."\n"."User: ".$_GET['email'] ."\n"."possible referer : ".$ref;  	 
	    mail("s3_krish@yahoo.co.in","ahens : Error in sending mail", $message4);
	   mail($_GET['email'], "AHENS Regrets", $message_user);
	 } 
	if($_GET['error']==666)
     {
        $message4 = "An error was caused during inserting random String." . "\n"."Error: Can't insert random string in DB"."\n"."User: ".$_GET['email'] ."\n"."possible referer : ".$ref;  	 
	    mail("s3_krish@yahoo.co.in","ahens : Error in sending mail", $message4);
	   mail($_GET['email'], "AHENS Regrets", $message_user);
	 } 
?>
<!doctype HTML>
<html>
 <head>
   <title> Error </title>
   <meta name="description" content='Kornflex is a web utility by AHENS, which enhances and helps your
				campus life, in easy and exclusive ways.' />   
	<meta charset="utf-8" />			
   <link rel="shortcut icon" href="../favicon.ico"/> 
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_1366.css" />
   <link rel="stylesheet" type="text/css" href="http://kf.ahens.com/kornflex_design_all/kf_design_error_1366.css" />
   <script type="text/javascript" src="http://kf.ahens.com/script/kf_script.js"> </script>
   <script type="text/javascript">
	 var width=(window.innerWidth)?window.innerWidth:document.documentElement.offsetWidth;
	 if(width<1290)
	  {
	    var head = document.getElementsByTagName('head')[0];
		var link = document.createElement("link");
		link.type="text/css";
		link.rel="stylesheet";
		link.href="http://kf.ahens.com/kornflex_design_all/kf_design_1024.css";
		var link2 = document.createElement("link");
		link2.type="text/css";
		link2.rel="stylesheet";
		link2.href="http://kf.ahens.com/kornflex_design_all/kf_design_error_1024.css";
		head.appendChild(link);
		head.appendChild(link2);
	
		
	  }
	</script>  
 </head>
 <body class="kornflex_body" id="error_page">
    <div class="middle_panel" id="blue_panel">
	
	
	<div class="logo_container" id="kf_logo">
	    <a href="../index.php">  <img src="http://kf.ahens.com/kf_images_all/kornflex_new_small.jpg" alt="kornflex_logo" border=0 style="margin-top:10px;margin-left:10px;"/>
	    </a>
	   </div> 
	   
	   <div id="ahens_copyright">
		 All rights reserved | AHENS &copy; 2012
		  <br />
			  <span style="font-style:italic;font-size:12px;">  Application version 2.0.0.0</span>
	   </div>
	   <div class="dotted_borders">
	   
			<div id="border_left">
				</div>
			<div id="border_right">
				</div>
			<div id="border_top">
				</div>
			<div id="border_bottom">
				</div>
		</div>
		<div class="text_container" id="text">
		   <div class="error_head" id="you_shoulnt">
		      You should not see this.
		   </div>
		   <div class="error_content" id="msg_error">
		   <br />
		          <span style="font-size:25px;font-weight:bold;">
				    Sorry, for interrupting you!
				  </span>
                    <br /><br />
					Something has went wrong at Kornflex. But, you don't have to worry about it.
					Our engineers have been well informed about your problem. We will work it out 
					as soon as possible.
					<br /><br />
					<a class="std_kf_link" href="../"> Please step back</a>
					
					
					
			</div>
		
		</div>
	</div>
 
 </body>
 </html>
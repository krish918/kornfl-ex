var visflag=0;
var flag=0;	
	      
    function showdetail(id, detail)  {
	   var el = document.getElementById(id);
	   el.style.fontFamily="calibri";
	   el.style.fontSize="14px";
	   el.style.fontStyle ="italic";
	   el.innerHTML = detail;
	   }
	 function hidedetail(id, text)
			{ 
			  var el = document.getElementById(id);
	   el.style.fontSize="17px";
	   el.style.fontStyle ="normal";
	   el.style.fontFamily="cambria";
	   el.innerHTML = text;
	   }
	   function removeVal()  {
	     if(document.getElementById("search_bar").value=="Not for searching cattles and pets!")
		      document.getElementById("search_bar").value="";
			} 
		function bringVal()  {
            if(document.getElementById("search_bar").value.length==0)
                  document.getElementById("search_bar").value="Not for searching cattles and pets!";
					}
		function buttonBEffect() {
				var but = document.getElementById("find_him");
				but.style.borderWidth = "5px";
				 
				}
		function buttonDEffect() {
				var but = document.getElementById("find_him");
				but.style.borderWidth = "4px";
				}
		function perform_search() {
				var val = document.getElementById("search_bar").value;
				var res = document.getElementById("search_result");
				 
				if((val.length==0) || (val=="Not for searching cattles and pets!"))
				  {
				      
					  visflag=1;
					 document.getElementById("search_box").style.visibility="visible";
					 document.getElementById("exitt").style.visibility="hidden";
					 res.innerHTML = "We are not that much fool! Enter a name that resembles a human being!";
					 setTimeout(function() { hideRes() ; },4000);
					 }
				else
				   {
				     document.getElementById("search_box").style.visibility="visible";
				     document.getElementById("exitt").style.visibility="visible";
				    
					 visflag=1;
				     var http;
					 if(window.XMLHttpRequest)
					     http = new XMLHttpRequest();
					 else
                          http = new ActiveXobject("Msxml2.XMLHTTP");	
					 res.innerHTML = "<img src='http://kf.ahens.com/kf_images_all/319.gif' />"
                     http.onreadystatechange = function ()   {
									if(http.readyState == 4 && http.status==200)
									  {
									    res.innerHTML = http.responseText;
										}
									}
					http.open("get", "http://kf.ahens.com/home/search.php?searchString="+val, true);
					http.send();
				   
				   }
			}	
		
		function hideRes() {
									if(visflag==1)
									{
									document.getElementById("search_result").innerHTML ="";
									 
									document.getElementById("search_box").style.visibility="hidden";
									document.getElementById("exitt").style.visibility="hidden";
									visflag = 0;
									}
								}
	function showmoreOver () {
	  document.getElementById('showmore').style.backgroundColor='#FEE700';
	  document.getElementById('smalldown').style.borderTop='solid 15px #FEE700';
	  }
	 
	function showmoreOut () {
     document.getElementById('showmore').style.backgroundColor='#F9BF00';	
	   document.getElementById('smalldown').style.borderTop='solid 15px #F9BF00';
	   }

	function bringfeed(a,b) {
						
		                 var http;
						 var container= document.getElementById("feedBox");
						 var showmore = document.getElementById("showmore");
						 var dummy = document.getElementById("containerDummy");
						 if(window.XMLHttpRequest)
						   http = new XMLHttpRequest();
						 else  
							http = new ActiveXObject("Msxml2.XMLHTTP");
						 dummy.innerHTML ="Loading Streams ...<img src='http://kf.ahens.com/kf_images_all/319.gif' alt='loading'/>";
                         http.onreadystatechange = function () {
								if(http.readyState==4 && http.status==200)
								 {
								  if(http.responseText!=0)
								   {
								   container.innerHTML += http.responseText;
								   dummy.innerHTML = "";
								   showmore.innerHTML = "<div onclick='bringfeed("+(a+b)+",50)'> Show More </div><div class='arrow' id='smalldown'></div>";
								   }
								    else
									  {
									  container.innerHTML += "<br /><br /><strong> No older feeds in this stream! </strong>";
									  showmore.style.visibility="hidden";
									  dummy.innerHTML = "";
									  }
								 }
								}
						http.open("get","http://kf.ahens.com/home/fetchnotification.php?limita="+a+"&limitb="+b, true);
						http.send();
		
					}
				function message()
						{
							 
							  var notif;
						if(window.XMLHttpRequest)
							notif = new XMLHttpRequest();
						else
							notif = new ActiveXObject("Msxml2.XMLHTTP");
						notif.onreadystatechange = function()
								{
                       if(notif.readyState ==4 && notif.status ==200)
								{
							document.getElementById("newmessage").innerHTML = notif.responseText;
							if(notif.responseText!=0)
							document.getElementById("mail_icon").title = notif.responseText + " new message !";
							else
								document.getElementById("mail_icon").title = "No new message !";
								}
                        }
        notif.open("get", "http://kf.ahens.com/home/notification.php?newreq="+1, true);
        notif.send();	
        setTimeout("message()",9000);				
     }
function loginstatus()
	{
		 
	var login;
	if(window.XMLHttpRequest)
	   login = new XMLHttpRequest();
	else
	  login =new ActiveXObject("Msxml2.XMLHTTP");
	
	login.onreadystatechange=function()
		{
		
		if(login.readyState==4 && login.status==200)
		    {
			
			document.getElementById("newchat").innerHTML = login.responseText;
		    if(login.responseText==0) 
			   {
			    document.getElementById("chat_icon").title = "No person to chat!";
				}
			   else 
			   {
				 document.getElementById("chat_icon").title = "People Online "+login.responseText;
				 }
		   }
		}
	login.open("get", "http://kf.ahens.com/home/logincheck.php?countpeople", true);
	login.send();
	setTimeout("loginstatus()", 5000);
	}	 
		

function updatenotif_new()
	{
		 
		  var noti;
	 if(window.XMLHttpRequest)
                    noti = new XMLHttpRequest();
	else
	  noti = new ActiveXObject("Msxml2.XMLHTTP");
              noti.onreadystatechange = function()
                      {
                       if(noti.readyState ==4 && noti.status ==200)
		 {
		   if(noti.responseText!=0)
		     document.getElementById("newstream").innerHTML = noti.responseText;
			 
					}
                        }
        noti.open("get", "http://kf.ahens.com/home/updatenotification.php", true);
        noti.send();	
        setTimeout("updatenotif()",5000);			
              }		
              
function hover(id,id2,id3) {
	   document.getElementById(id).style.backgroundColor="#5901F1";
	   document.getElementById(id).style.border="solid 1px #3901FF";
	   document.getElementById(id).style.color="#FFFFFF";
	   slideAddress(id2,id3,0,-21);
	   }
	 function out(id,id2,id3) {
		  document.getElementById(id).style.backgroundColor="#F15901";
		  document.getElementById(id).style.border="solid 1px #FF3901";
		  document.getElementById(id).style.color="#000000";
		  hideAddress(id2,id3,30,0);
	 }	 
	 function slideAddress(id2,id3, i,j)
		{
		if(flag==0)
		{
		document.getElementById(id2).style.top = ""+i+"px";
		document.getElementById(id3).style.top = ""+j+"px";
		i+=3;j+=3;
		if(i==30) {flag =1;return; }
		setTimeout(function() { slideAddress(id2,id3,i,j); }, 15);
		}
		}
		function hideAddress(id2,id3,i,j)
		{
		if(flag==1)
		{
		document.getElementById(id2).style.top = ""+i+"px";
		document.getElementById(id3).style.top = ""+j+"px";
		i-=3;j-=3;
		if(i==3) {flag=0;return; }
		setTimeout(function() { hideAddress(id2,id3,i,j); }, 15);
		}
		}
	function hideDialogBox(i,j)
		{
		document.getElementById("cvr").style.opacity = ""+i+"";
		document.getElementById("subscribeApp").style.opacity = ""+j+"";
		if(i<0.0) {i=i-0.1}; j -= 0.2;
		if(j<0.0) { document.getElementById("cvr").style.visibility = "hidden";
		document.getElementById("subscribeApp").style.visibility = "hidden";
		document.getElementById("button_subs").style.visibility = "hidden";
		return;}
		setTimeout(function() { hideDialogBox(i,j); }, 1);
		
		}
	function subscribeApp(i,j)
		{
		document.getElementById("cvr").style.visibility = "visible";
		document.getElementById("subscribeApp").style.visibility = "visible";
		document.getElementById("cvr").style.opacity = ""+i+"";
		document.getElementById("subscribeApp").style.opacity = ""+j+"";
		if(i<0.50) {i=i+0.2}; j += 0.3;
		if(j>1.0) { return;}
		setTimeout(function() { subscribeApp(i,j); }, 1);
		
		}	
	function showlist(appID) {
        document.getElementById("cvr2").style.visibility = "visible";	
        document.getElementById("subscriberlist").style.visibility = "visible";	
		document.getElementById("subslist").innerHTML ="<div style='text-align:center;'><img src='http://kf.ahens.com/kf_images_all/319.gif' /></div>";
		var http;
					 if(window.XMLHttpRequest)
					     http = new XMLHttpRequest();
					 else
                          http = new ActiveXobject("Msxml2.XMLHTTP");	
				http.onreadystatechange = function() {
						if(http.readyState==4 && http.status==200)
						   document.getElementById("subslist").innerHTML = http.responseText;
						   }
				http.open("get","http://kf.ahens.com/home/apps/appcetra.php?subslist=1&appID="+appID,true);
				http.send();
				
		}
	function hideSubsList()
		{
		
		    document.getElementById("cvr2").style.visibility = "hidden";	
		    document.getElementById("subscriberlist").style.visibility = "hidden";	
			
			}
	function callAppSubsContent(appID)		{
	          checksubscription(appID);
	          oldSubscriptions(appID);
			  
			   document.getElementById("appid").value=appID;
				var http;
					 if(window.XMLHttpRequest)
					     http = new XMLHttpRequest();
					 else
                          http = new ActiveXobject("Msxml2.XMLHTTP");	        
				document.getElementById("welcome").innerHTML ="Please Wait ...";  
				http.onreadystatechange = function() { 
						if(http.readyState==4 && http.status==200)
						   document.getElementById("welcome").innerHTML = http.responseText;
						   
					}
				http.open("get","http://kf.ahens.com/home/apps/appcetra.php?appname=1&appID="+appID,true);
				http.send();				     
	}
	function oldSubscriptions(appID) {
				var http;
				
					 if(window.XMLHttpRequest)
					     http = new XMLHttpRequest();
					 else
                          http = new ActiveXobject("Msxml2.XMLHTTP");	
				http.onreadystatechange = function() {
						if(http.readyState==4 && http.status==200)
						   document.getElementById("oldSubs").innerHTML = http.responseText;
						   }
				http.open("get","http://kf.ahens.com/home/apps/appcetra.php?oldsubs=1&appID="+appID,true);
				http.send();		
	}
	function checksubscription(appID) {
	         var http;
					 if(window.XMLHttpRequest)
					     http = new XMLHttpRequest();
					 else
                          http = new ActiveXobject("Msxml2.XMLHTTP");	
                var button = document.getElementById("button_subs");
               
				http.onreadystatechange = function() {
						if(http.readyState==4 && http.status==200)
						  {
						  
						   if(http.responseText==1)
						   document.getElementById("button_subs").innerHTML = "<span style='color:#ff3f0a;font-weight:bold;'>You are already subscribed for this app. Start this app from My subscription panel in appcetra.</span>";
						    button.style.visibility="visible";
						  }
						}
				http.open("get","http://kf.ahens.com/home/apps/appcetra.php?checksubs=1&appID="+appID,true);
				http.send();
				
	 		}	
	var flag_get_subs_apps=0;		
	function load_subs_apps()
		{
		
		var output = document.getElementById("drop_down");
	    if(flag_get_subs_apps==1) { flag_get_subs_apps=0; slide_up_myapp(output,240);return; }
		slide_down_myapp(output,80);
		
		output.innerHTML = "<br /><div style='text-align:center;'><img src='http://kf.ahens.com/kf_images_all/319.gif' /></div>";
		var http;
		if(window.XMLHttpRequest)
			http = new XMLHttpRequest();
		else	
			http=new ActiveXObject("Msxml2.XMLHTTP");
		http.onreadystatechange = function () {
					if(http.readyState==4 && http.status==200)
						{
						output.innerHTML = http.responseText;
						flag_get_subs_apps =1;
						}
					}
			http.open("get","http://kf.ahens.com/home/apps/appcetra.php?getSubscribedApps="+1,true);
			http.send();
	}		
	function slide_down_myapp(id,i) {
				    
					id.style.visibility="visible";
					id.style.height= ""+i+"px";
					i +=20;
					if(i>240) { return; }
					setTimeout(function() { slide_down_myapp(id,i);},10);
					
				 
			}
		function slide_up_myapp(id,i) {
				    
				 
					id.style.height= ""+i+"px";
					i -=20;
					if(i==0) { id.style.visibility="hidden";return; }
					setTimeout(function() { slide_up_myapp(id,i);},10);
					
				 
			}		
	

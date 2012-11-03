function omitpass(val,text)
{
	if(document.getElementById(val).value==text)
                            {
	document.getElementById(val).value="";
	document.getElementById(val).type="Password";
	document.getElementById(val).style.color="black";
	      }
	document.getElementById(val).style.borderColor="orange";
}

function bringpass(val,val2)
{
	if(document.getElementById(val).value=="")
                            {
		document.getElementById(val).type="text";
		document.getElementById(val).style.color="gray";
		document.getElementById(val).value=val2;
	      }
	
	document.getElementById(val).style.borderColor="#777777";
                                      
}

function menuOver()
{
  document.getElementById("menuUser").style.backgroundColor="#dedeff";
  
  		document.getElementById("menu").style.visibility="visible";
  }
 function menuOut()
{
 document.getElementById("menuUser").style.backgroundColor="#ababde";
 		document.getElementById("menu").style.visibility="hidden";
}
function omitInpt(val, val2)
{
	if(document.getElementById(val).value==val2)
                            {
	document.getElementById(val).value="";
	document.getElementById(val).style.color="black";
	     }
	document.getElementById(val).style.borderColor="orange";
}
function bringInpt(val,val2)
{
	if(document.getElementById(val).value=="")
                            {
		document.getElementById(val).style.color="gray";
		document.getElementById(val).value=val2;
	      }
	document.getElementById(val).style.borderColor="#777777";
                                      
}
function showDialog(campus, cname)
{
	document.getElementById("cvr").style.visibility="visible";
	document.getElementById("dbox").style.visibility="visible";
	document.getElementById("hidinpt").value = campus;
	document.getElementById("cname").innerHTML = cname;
}
function hide()
{
                              	document.getElementById("dbox2").style.visibility="hidden";
		document.getElementById("cvr").style.visibility="hidden";
		document.getElementById("dbox").style.visibility="hidden";			
}
function hide1()
   {
             document.getElementById("cvr").style.visibility="hidden";
		document.getElementById("dbox").style.visibility="hidden"; 
                          document.getElementById("hidinfo").style.visibility="hidden"; 
                      document.getElementById("but").style.visibility="hidden"; 
                     document.getElementById("but3").style.visibility="hidden"; 
  }

  function hide2()
 {
                    document.getElementById("cvr").style.visibility="hidden";
		document.getElementById("dbox").style.visibility="hidden";
}   
function sideLinkover(val)
{
	document.getElementById(val).style.borderLeftWidth="6px";
	document.getElementById(val).style.borderRightWidth="6px";
	document.getElementById(val).style.marginLeft="-6px";
	document.getElementById(val).style.borderColor="purple";
	
	
	
}
function sideLinkdown(val)
{
	document.getElementById(val).style.borderLeftWidth="12px";
	document.getElementById(val).style.borderRightWidth="12px";
	document.getElementById(val).style.marginLeft="-12px";
}
function sideLinkup(val)
{
	document.getElementById(val).style.borderLeftWidth="0px";
	document.getElementById(val).style.borderRightWidth="0px";
	document.getElementById(val).style.marginLeft="0px";
	document.getElementById(val).style.borderColor="black";
	document.getElementById(val).style.backgroundColor="#eeeeee";
}
function passRecoverShow()
  {
      document.getElementById("cvr").style.visibility = "visible";
      document.getElementById("dbox").style.visibility = "visible";
      document.getElementById("but").style.visibility = "visible";
  }    

function rem(valu)
  {
         var msg = document.getElementById("msg");
         if(valu != "")  
             msg.innerHTML = "";
  }  
       function loadSearch(camp)
                         {
       				var searchString=document.getElementById("search").value;
       				var httpx;
    
       				if(window.XMLHttpRequest)
        			  httpx =new XMLHttpRequest();
       				else
          				httpx = new ActiveXObject("Msxml2.XMLHTTP");   
       				var loader=document.getElementById("load"); 
       				if(searchString=='')
         			 loader.innerHTML="";
       				else
       				{
          			loader.innerHTML ="<img src='../../images/ajax-loader.gif' />";
          			httpx.onreadystatechange = function ()
             			{
             			 if(httpx.readyState==4 && httpx.status==200)
             			 {
                                 loader.innerHTML="";
                 		 document.getElementById("searchResult").innerHTML = httpx.responseText;
                 		 }
             			}
          			httpx.open("get","http://kf.ahens.com/home/search/searchRobot.php?queryString="+searchString+"&campusName="+camp,true)
          			httpx.send();         
       				}
			 } 


     function bringw()
          {
              if(document.getElementById("flakes").value=="")
                {
                     document.getElementById("flakes").style.color="#aaaaaa";
                 document.getElementById("flakes").value="Start here, say something.";
                }
           }      
                 
         function areaBig()
          {
             document.getElementById("flakes").style.height = "110px";
             document.getElementById("flakes").style.color = "black";
             if(document.getElementById("flakes").value=="Start here, say something.")
                       document.getElementById("flakes").value="";
          }
         function textclear()
         {
         if(document.getElementById("about").value=="I am saying something about")
            document.getElementById("about").value="";
            document.getElementById("about").style.color="black";
         }    
         function bring()
         {
            if(document.getElementById("about").value=="")
              {
                 document.getElementById("about").style.color="#aaaaaa";
                 document.getElementById("about").value="I am saying something about";
              }      
         } 
    
        function loadFlakes()
         {
            var flakes = document.getElementById("flakes").value;
            var about = document.getElementById("about").value; 
            if((document.getElementById("flakes").value=="")||(document.getElementById("flakes").value=="Start here, say something."))
                   document.getElementById("msg").innerHTML = "<DIV Style='background-color:#ffdddd;border:solid 1px #ff9090;width:500px;'>Your Flakes is missing!</div>";
            else if((document.getElementById("about").value=="")||(document.getElementById("about").value=="I am saying something about"))
                   document.getElementById("msg").innerHTML = "<DIV Style='background-color:#ffdddd;border:solid 1px #ff9090;width:500px;'>Please write about what your Flakes exactly is!</div>";
             else
                {
                  document.getElementById("msg").innerHTML="<IMG src='http://kf.ahens.com/images/ajax-loader.gif' alt='loading' border=0 />";
                  var httpf;
                  if(window.XMLHttpRequest)
                     httpf = new XMLHttpRequest();
                  else
                     httpf = new ActiveXObject("Msxml2.XMLHTTP");
                   httpf.onreadystatechange= function()
                      {
                        if(httpf.readyState==4 && httpf.status==200)
                          {     
                               document.getElementById("msg").innerHTML="";
                              document.getElementById("flakesBody").innerHTML = httpf.responseText;
                          }
                       }
                    httpf.open("get","flakesLoader.php?flakes="+flakes+"&about="+about, true)
                    httpf.send();
                 }
            }   
            
           function clears(valu)
           {
            
                if(valu=="Enter a name to search")
                {
                document.getElementById("qs").value="";
                
                }
               
           }
          function get(val)
             {
	
               if(val=="")
                 {
                 document.getElementById("qs").value="Enter a name to search";
                 
               
                 }
          
                 document.getElementById("searchbox").style.visibility="hidden";  
             } 
         function quicksearch(string,name)
            {
            
             if(string.length!='')
             {
               document.getElementById("searchbox").style.visibility="visible";
               var httpp;
               if(window.XMLHttpRequest)
                 httpp = new XMLHttpRequest();
              else httpp = new ActiveXObject();
              document.getElementById("searchbox").style.visibility="visible";
              document.getElementById("searchbox").innerHTML = "<IMG src='http://kf.ahens.com/images/ajax-loader.gif' alt='loading' border=0 />";
              httpp.onreadystatechange=function()
               {
                 if(httpp.readyState==4 && httpp.status==200)
                  {
                   document.getElementById("searchbox").innerHTML=httpp.responseText;
                  }
               }
              httpp.open("get","http://kf.ahens.com/home/search/searchRobot.php?queryString="+string+"&cname="+name,true);
              httpp.send();
             } 
            }    
 function loadAll(string)
        {
        

          var l;
          if(window.XMLHttpRequest)
            l =new XMLHttpRequest();
         else
          l = new ActiveXObject("Msxml2.XMLHTTP");   
          var loader=document.getElementById("load"); 
          loader.innerHTML ="<img src='http://kf.ahens.com/images/ajax-loader.gif' />";
          l.onreadystatechange = function ()
             {
             	 if(l.readyState==4 && l.status==200)
               {
            loader.innerHTML="";
             document.getElementById("searchResult").innerHTML = l.responseText;
               }
            }
          l.open("get","http://kf.ahens.com/home/search/searchRobot.php?campCode="+string, true);
           l.send();         
         }
   
   function startNotification()
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
		     document.getElementById("notif").innerHTML = notif.responseText;
  	                 }
                        }
        notif.open("get", "http://kf.ahens.com/home/notification.php", true);
        notif.send();	
        setTimeout("startNotification()",15000);				
     }	
 function hidenotif()
             {
			 document.getElementById("notif").innerHTML="";
              }		
function searchCampus(query)
 	{
	 	var campload;
  		if(window.XMLHttpRequest)
			campload = new XMLHttpRequest();
		else
			campload = new ActiveXObject("Msxml2.XMLHTTP");
		document.getElementById("loader").innerHTML = "<img src='http://kf.ahens.com/images/ajax-loader.gif' />";
                               campload.onreadystatechange = function()
		{
			if(campload.readyState==4 && campload.status==200)
				{
				document.getElementById("loader").innerHTML="";
				document.getElementById("campRes").innerHTML=campload.responseText;
				}
		}
		campload.open("get", "searchcampus.php?searchterm="+query, true);
		campload.send();
	}			

    function showTeaserReq()
	{
	
  		document.getElementById("dbox3").style.visibility="visible";
	}
  function hideTeaser()
	{
	
		document.getElementById("dbox3").style.visibility="hidden";
	}               
function showallnotif(a, b)
	{
                               document.getElementById("hidenotif").style.visibility ="visible";
		document.getElementById("notiFrame").style.visibility ="visible";
		var frame=document.getElementById("content");
		var all;
		if(window.XMLHttpRequest)
		  all = new XMLHttpRequest();
		else
		  all = new ActiveXObject("Msxml2.XMLHTTP");
		document.getElementById("more").innerHTML = "<img src='http://kf.ahens.com/images/ajax-loader.gif' />";
		all.onreadystatechange=function()
		{
			if(all.readyState==4 && all.status==200)
			{
			if(all.responseText!=0)
			 {
		                  frame.innerHTML +=all.responseText;
			  document.getElementById("more").innerHTML = "<DIV onclick='showallnotif("+(a+b)+",15)' style='cursor:pointer;'> Show More</DIV> <br />";
			 }
			else
                                                  {
			  frame.innerHTML = "<br /><br /><br /><br /><span style='font-family:tahoma;font-size:14px;font-weight:bold;'>No more activities in your network</span>";
			 document.getElementById("more").innerHTML="";
			  }
			}
		}
		all.open("get", "http://kf.ahens.com/home/fetchnotification.php?limita="+a+"&limitb="+b, true);
		all.send();	     
			
	}
function hidenotif()
	{
	    document.getElementById("content").innerHTML ="";
	    document.getElementById("notiFrame").style.visibility ="hidden";
	   document.getElementById("shownotif").style.visibility ="visible";
	   document.getElementById("hidenotif").style.visibility ="hidden";
	}
function updatenotif()
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
		     document.getElementById("shownotif").innerHTML = "<li style='font-family:tahoma;font-size:14px;text-align:center;color:black;'> All Notifications ("+noti.responseText+")</li>";
			 
					}
                        }
        noti.open("get", "http://kf.ahens.com/home/updatenotification.php", true);
        noti.send();	
        setTimeout("updatenotif()",5000);			
              }
function sidelinkover(get)
	{
	document.getElementById(get).innerHTML += "<span style='font-size:13px;font-family:verdana;'> &gt; </span>";
	document.getElementById(get).style.color ="black";
	}
function sidelinkout(get,text)
	{
	document.getElementById(get).innerHTML = text;
	document.getElementById(get).style.color="rgb(120,120,120)";
	}

function bringstone(a,b)
	{
	var stone;
	if(window.XMLHttpRequest)
      		stone = new XMLHttpRequest();
                else
		stone = new ActiveXObject("Msxml2.XMLHTTP");
                document.getElementById("loader").innerHTML="<img src='http://kf.ahens.com/images/ajax-loader.gif' />";
	 stone.onreadystatechange = function()
	            {
	        if(stone.readyState==4 && stone.status==200)
		{
	             document.getElementById("loader").innerHTML="";	
		if(stone.responseText!=0)
	             document.getElementById("getstone").innerHTML += stone.responseText;
	             document.getElementById("getmore").innerHTML = "<DIV onclick='bringstone("+(a+b)+",15)' style='cursor:pointer;font-size:14px;text-align:center;background-color:#ddddff;'> Show More</DIV> <br />";
                             if(stone.responseText==0)
		document.getElementById("getmore").innerHTML="<span style='color:#ff9090;font-size:14px;'>No more stories found<br /><br />";
		}
	             }
	  stone.open("get", "bringstone.php?lima="+a+"&limb="+b, true);
	  stone.send();
	  }	
function stonesearch(a,b)
	{
	var stones;
	var next = document.getElementById("stonesearch").value;
                 var inh = document.getElementById("getstone");
               if(next.length!=0)
	{
	
	if(window.XMLHttpRequest)
      		stones = new XMLHttpRequest();
                else
		stones = new ActiveXObject("Msxml2.XMLHTTP");
                document.getElementById("loader").innerHTML="<img src='http://kf.ahens.com/images/ajax-loader.gif' />";
	
	 stones.onreadystatechange = function()
	      {
	        if(stones.readyState==4 && stones.status==200)
		{
	             document.getElementById("loader").innerHTML=" ";
	             document.getElementById("extra").innerHTML=" ";	
	             document.getElementById("getstone").innerHTML = stones.responseText;
	             document.getElementById("getmore").innerHTML = "<DIV onclick='stonesearch("+(a+b)+",15)' style='cursor:pointer;font-size:14px;text-align:center;background-color:#ddddff;'> Show Next</DIV> <br />";
		}
	        }
	  stones.open("get", "bringstone.php?search="+next+"&la="+a+"&lb="+b, true);
	  stones.send();
	}
	else
                   inh.innerHTML = "<DIV style='background-color:#ffcccc;border:1px solid #ff9090;padding:7px;font-size:14px;'> Please enter any search term.</DIV><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
           }

function loadcomment(val)
	{
	 var comment;
	if(window.XMLHttpRequest)
      		comment = new XMLHttpRequest();
                else
		comment = new ActiveXObject("Msxml2.XMLHTTP");
                comment.onreadystatechange = function()
		{
	  	  if(comment.readyState==4 && comment.status==200)
			{
			document.getElementById("comment").innerHTML = comment.responseText;
			
			}
		}
	comment.open("get", "comment.php?val="+val, true);
	comment.send(null);
	setTimeout("loadcomment('')",4000);
	}
function loadallcomnt(a,b)
	{
		 var comment;
	
	if(window.XMLHttpRequest)
      		comment = new XMLHttpRequest();
                else
		comment = new ActiveXObject("Msxml2.XMLHTTP");
                comment.onreadystatechange = function()
		{
	  	  if(comment.readyState==4 && comment.status==200)
			{
			document.getElementById("comment").innerHTML = comment.responseText;
			}
		}
	comment.open("get", "comment.php?la="+a+"&lb="+b, true);
	comment.send(null);
	}
	
function enter(val,ev)
	{
	var key = ev.keyCode? ev.keyCode:ev.charCode;
	if(key==13)
	 {
		document.getElementById("cmnt").value=" ";
		loadcomment(val);
	}
	}
function loadinf(a)
	{
	 var data;
	
	if(window.XMLHttpRequest)
      		data = new XMLHttpRequest();
                else
		data = new ActiveXObject("Msxml2.XMLHTTP");
                document.getElementById("loadspecial").style.visibility="visible";
	document.getElementById("loadspecial").innerHTML="<img src='http://kf.ahens.com/images/ajax-loader.gif' />";
                data.onreadystatechange = function()
		{
	  	  if(data.readyState==4 && data.status==200)
			{
			document.getElementById("loadspecial").innerHTML = data.responseText;
			}
		}
	data.open("get", "readerdata.php?info="+a, true);
	data.send();
	}
function showbrief(id, e)
	{
	
	e =e || window.event;
	var xpos;
	xpos = e.pageX;
	ypos=e.pageY;
	var data;
	if(window.XMLHttpRequest)
      		data = new XMLHttpRequest();
                else
		data = new ActiveXObject("Msxml2.XMLHTTP");
                
	document.getElementById("getbrief").style.top = (ypos-60)+"px" ;
	/*document.getElementById("getbrief").style.left = xpos+"px" ;*/
	document.getElementById("getbrief").style.visibility="visible";
	document.getElementById("cont").innerHTML = "<img src='http://kf.ahens.com/images/ajax-loader.gif' />";
                data.onreadystatechange = function()
		{
	  	  if(data.readyState==4 && data.status==200)
			{
			document.getElementById("cont").innerHTML = data.responseText;
			}
		}
	data.open("get", "briefer.php?id="+id, true);
	data.send();
	
	}

function hidebrief()
	{
  document.getElementById("getbrief").style.visibility="hidden";
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
			
			document.getElementById("chatbox").innerHTML = login.responseText;
			
		   }
		}
	login.open("get", "http://kf.ahens.com/home/logincheck.php?countpeople", true);
	login.send();
	setTimeout("loginstatus()", 5000);
	}
function onlinename()
	{
	document.getElementById("notiFrame2").style.visibility = "visible";
	var login2;
	
	if(window.XMLHttpRequest)
	   login2 = new XMLHttpRequest();
	else
	  login2 =new ActiveXObject("Msxml2.XMLHTTP");
	document.getElementById("notiFrame2").innerHTML = "Loading...";
	login2.onreadystatechange=function()
		{
		
		if(login2.readyState==4 && login2.status==200)
		    {
			
			document.getElementById("notiFrame2").innerHTML = login2.responseText;
			
		   }
		}
	login2.open("get", "http://kf.ahens.com/home/logincheck.php?getpeople", true);
	login2.send();

	}


function fadeout(n,ids)
	{
	var feat = document.getElementById(ids).style;
	feat.visibility ="visible";
                 feat.opacity =  ""+n+"";
	n = n+0.1;
                if(n >1) {return;}
	setTimeout(function() { fadeout(n,ids); } , 100);
	
	}



function fadein(no, id2)
	{
                    var feats = document.getElementById(id2).style;
                       feats.opacity = ""+no +"";
	       no = no-0.1;
	      if(no<0) {feats.visibility ="hidden";return; }
	      setTimeout(function() { fadein(no,id2); } , 100);
	     }
function checkentry()
	{
	           fadein(1, 'msg');
	            var code=document.getElementById("inpt1").value;
	            var uname=document.getElementById("inpt2").value;
	            if(code.length==15 && uname.length!=0 && uname!="Twitter Username")
		fadeout(0, 'button1');
	            else
		fadein(1, 'button1');
	}
function validnsub(code)
	{
	
	var c = document.getElementById("inpt1").value;
	var m = document.getElementById("msg");
	  if(c!=code) 
                      {
	         m.innerHTML = "Secret Code Incorrect!";
		fadeout(0,'msg');
	         
	      }
	else if(document.getElementById("inpt2").value.length!=0)
	{
	fadein(1, 'msg');
               	document.getElementById("twt").submit();
	}	
             }
function validatesignup()
	{
	var email = document.getElementById("email").value;
	var pass = document.getElementById("password").value;
	var name = document.getElementById("username").value;
	var msg = document.getElementById("signuperror");
	email = escape(email);
	pass = escape(pass);
	name = escape(name);
	if(email.length==0)
	  {
	   	msg.innerHTML = "We need your Email address badly!";
		msg.style.visibility = "visible";
		fadeout(0, 'signuperror');
		setTimeout(function(){ fadein(1, 'signuperror'); }, 5000);
	}
              else if(pass.length<6)
	{
		msg.innerHTML = "Your password should be at least 6 characters long!";
		msg.style.visibility = "visible";
		fadeout(0, 'signuperror');
		setTimeout(function(){ fadein(1, 'signuperror'); }, 5000);
	}
	else if(name.length==0)
	{
		msg.innerHTML = "We would like to know your name!";
		msg.style.visibility = "visible";
		fadeout(0, 'signuperror');
		setTimeout(function(){ fadein(1, 'signuperror'); }, 5000);
	}
	else
	 {
	   	
	var request;
	if(window.XMLHttpRequest)
	   request = new XMLHttpRequest();
	else
	  request =new ActiveXObject("Msxml2.XMLHTTP");
	msg.innerHTML = "<span style='color:#60dd60;'>Hold on, till we finish our part...</span>";
	msg.style.visibility = "visible";
	fadeout(0, 'signuperror');
	request.onreadystatechange=function()
		{
		
		if(request.readyState==4 && request.status==200)
		    {
			
			msg.innerHTML = request.responseText;
			msg.style.visibility = "visible";
			fadeout(0, 'signuperror');
		   }
		}
	request.open("get", "signup.php?email="+email+"&pass="+pass+"&name="+name, true);
	request.send();
	  }
	}
function sendmail(email)
	{
		var request2;
	if(window.XMLHttpRequest)
	   request2 = new XMLHttpRequest();
	else
	  request2 =new ActiveXObject("Msxml2.XMLHTTP");
	document.getElementById("signuperror").innerHTML = "<span style='color:#60dd60;'>Please, be patient while we resend the mail.....</span>";
	fadeout(0, 'signuperror');
	request2.onreadystatechange=function()
		{
		
		if(request2.readyState==4 && request2.status==200)
		    {
			
			document.getElementById("signuperror").innerHTML = request2.responseText;
			fadeout(0, 'signuperror');
		   }
		}
	request2.open("get", "signup.php?resend="+email, true);
	request2.send();
	}
function clearall()
	{
	document.getElementById("email").value='';
	document.getElementById("password").value='';
	document.getElementById("username").value='';
	document.getElementById("emailin").value='';
	document.getElementById("passwordin").value='';
	fadein(1,'signuperror');
	fadein(1,'signuperror2');
	}
function login()
	{
	var e = document.getElementById("emailin").value;
	var p =  document.getElementById("passwordin").value;
	e=escape(e);
	p =escape(p);
	var msg = document.getElementById("signuperror2");
	if(e.length==0)
	{
	  msg.innerHTML = "We are nothing without your Email address!";
	 	msg.style.visibility = "visible";
		fadeout(0, 'signuperror2');
		setTimeout(function(){ fadein(1, 'signuperror2'); }, 5000);
	}
	else if(p.length==0)
	{
	  msg.innerHTML = "We don't think you can proceed without the password!";
	 msg.style.visibility = "visible";
		fadeout(0, 'signuperror2');
		setTimeout(function(){ fadein(1, 'signuperror2'); }, 5000);
	}
	else if(p.length<6)
	{
		 msg.innerHTML = "Password doesn't seem nice to us!";
	 msg.style.visibility = "visible";
		fadeout(0, 'signuperror2');
		setTimeout(function(){ fadein(1, 'signuperror2'); }, 5000);
	}
	else
		{
				var request2;
	if(window.XMLHttpRequest)
	   request2 = new XMLHttpRequest();
	else
	  request2 =new ActiveXObject("Msxml2.XMLHTTP");
	msg.innerHTML = "<span style='color:#60dd60;'>Please be patient, till we crosscheck your credentials...</span>";
	fadeout(0, 'signuperror2');
	request2.onreadystatechange=function()
		{
		
		if(request2.readyState==4 && request2.status==200)
		    {
			
			msg.innerHTML = request2.responseText;
			fadeout(0, 'signuperror2');
			if(request2.responseText==1)
			{
			 msg.innerHTML ="<span style='color:#70ff70;'>Hello! Let us take you to our world. Patience & a good connection is all you need!</span>"; 
				fadeout(0, 'signuperror2');
			   document.getElementById("signinform").submit();
			}
		   }
		}
	request2.open("get", "signup.php?vemail="+e+"&vpass="+p, true);
	request2.send();
		
		} 
	}
function transit(n,start,stop,id)
	{
	fadein(1,'signin');fadein(1,'getfeatured');fadein(1, 'signuperror2');fadein(1, 'signuperror');fadein(1, 'whistle');fadein(1, 'button1');
	 var item = document.getElementById(id).style;
	item.top = ""+start+"px";
	start = start -20;
	item.visibility ="visible";
               if(n<1)
	{
                 item.opacity =  ""+n+"";
	n = n+0.2;
	}
	else  item.opacity="1";
	if(start<stop) {vibratevert(id,175,0);return;}
	setTimeout(function() { transit(n,start,stop,id); }, 1);
	}
function vibratevert(id, top,count)
	{
	count++;
	var item = document.getElementById(id).style;
	var button = document.getElementById("start");
                 button.onclick = function(){ 
			           clearTimeout(vibrator);
			           transit(0,400,175,'signup');	
				 };
	if(count>8)
	{
	    top=top+1;   
	   if(count>15) count=0;
	}
	else
		top=top-1;
	item.top = ""+top+"px";
	
	var vibrator = setTimeout(function() { vibratevert(id,top,count); }, 120);
	}
function transitup(n,start,stop,id)
	{
	fadein(1,'signup');fadein(1,'getfeatured');fadein(1, 'signuperror2');fadein(1, 'signuperror'); fadein(1, 'whistle'); fadein(1, 'button1');
	 var item = document.getElementById(id).style;
	item.top = ""+start+"px";
	start = start +35;
	item.visibility ="visible";
	
               if(n<1)
	{
                 item.opacity =  ""+n+"";
	n = n+0.1;
	}
	if(start>stop) {vibratehoriz(id,83,0);return;}
	
	setTimeout(function() { transitup(n,start,stop,id); }, 1);

	}
function vibratehoriz(id,left,count)
	{
	count++;
	var item = document.getElementById(id).style;
	var button = document.getElementById("resume");
                 button.onclick = function(){ 
			           clearTimeout(vibrator2);
			           transitup(0,-240,230,'signin');	
				 };
	if(count>8)
	{
	    left=left+1;   
	   if(count>15) count=0;
	}
	else
		left=left-1;
	item.left = ""+left+"px";
	
	var vibrator2 = setTimeout(function() { vibratehoriz(id,left,count); }, 120);
	}
	  
function transitgendown(start,stop,id)
	{
	
	var item = document.getElementById(id).style;
	item.visibility = "visible";
	item.opacity = "1";
	item.top = ""+start+"px";
	start = start +20; 
	if(start>stop) { document.getElementById("whistleintro").innerHTML +=". Click for a sample";  return;}
	
	setTimeout(function() { transitgendown(start,stop,id); }, 1);
	}
function transitgenup(start,stop,id)
	{
	var item = document.getElementById(id).style;
	item.top = ""+start+"px";
	start = start -20;
	if(start<stop) {document.getElementById("whistleintro").innerHTML ="Introducing ahens whistle"; return;}
	
	setTimeout(function() { transitgenup(start,stop,id); }, 1);
	}
function todefault()
	{
	fadein(1, 'signup'); fadein(1, 'signin'); fadein(1, 'getfeatured'); fadeout(0, 'whistle');
	}
function wtimer()
	{
	var timer; 
	if(window.XMLHttpRequest)
      		timer = new XMLHttpRequest();
                else
		timer = new ActiveXObject("Msxml2.XMLHTTP");
              
	 timer.onreadystatechange = function()
	            {
	        if(timer.readyState==4 && timer.status==200)
	                      document.getElementById("timer").innerHTML = timer.responseText;
	                  
	             }
	  timer.open("get", "http://www.ahens.com/confirm.php?timer", true);
	  timer.send();
	setTimeout("wtimer()", 200);
	}
var univ =0;
function enlarge(id,size,count)
	{
           var item = document.getElementById(id).style;
	   if(count>5)
	      {	size= size-1; if(count>9) count =0; }	
	   else size = size+1;
	   count++;
	   univ++; 
	   item.fontSize = ""+size+"px";
	  if(univ>40) {univ=0;item.fontSize = "11px"; return;}
	  var en=setTimeout(function(){ enlarge(id,size,count); }, 20);
	  
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
			function resetNotification(){
							var timer; 
						if(window.XMLHttpRequest)
      					timer = new XMLHttpRequest();
                			else
								timer = new ActiveXObject("Msxml2.XMLHTTP");
	  				timer.open("get", "http://kf.ahens.com/home/logincheck.php?reset=1", true);
	  				timer.send();
			}		
var delayFlag=1;	
	function logout_app_effect()
		{
		document.getElementById("apptease_logout").style.height="100px";
		document.getElementById("apptease_logout").innerHTML = ":(<br /><span style='font-size:11px;'>&nbsp;&nbsp;Logout</span><div class='logout_arrow'></div>";
	 
		}
	function logout_app_effect_out()
		{
		document.getElementById("apptease_logout").style.height="75px";
		document.getElementById("apptease_logout").innerHTML = ":)"+"<div class='logout_arrow'></div>";
		}
	function showHighScores()   {
		  document.getElementById("cvr2").style.visibility = "visible";
		  document.getElementById("subscriberlist2").style.visibility ="visible";
		  var scoreOut = document.getElementById("subslist");
		  scoreOut.innerHTML = "<div style='text-align:center;'><img src='http://kf.ahens.com/kf_images_all/319.gif' /></div>";
		   var http;
			if(window.XMLHttpRequest)
				http= new XMLHttpRequest()
		    else	
				http = new ActiveXObject("Msxml2.XMLHTTP");
			http.onreadystatechange = function () {
				if(http.readyState==4 && http.status == 200)
					{
					  scoreOut.innerHTML = http.responseText;
					  }
				}
			http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/history_engine.php?getScores="+1, true);
			http.send();
		 } 
function showFields()
			{
			  document.getElementById("choose_field").style.visibility ="visible";
			  }
function showLast()
			{
			  document.getElementById("choose_field").style.visibility ="hidden";
			  }
    var time_i;			  
	 function timer_start(i) {
				    
				    var timer = document.getElementById("apptease_timer");
					
					timer.innerHTML=i;
					time_i =i;
					
					var timer2= document.getElementById("time_count");
					if(i>9)
						timer2.innerHTML = i;
					else
						timer2.innerHTML="0"+i;
					i--;
					if(i==59) { timer2.style.color = "#F2B224"; }
					if(i==29) {timer2.style.color = "#C94B12"; }
					if(i==10) {timer2.style.color = "red"; }
					 
					if(i<0) { draw_no_escape(0);close_session(); return;}
					setTimeout(function () { timer_start(i); },1000);
				  }	
				  var r=136; var g =187; var rs = 186; var gs= 218;
		function great_timer_start(i)  {
				var great = document.getElementById("receding_bg");
				i--;
				great.style.width = ""+i+"px";
				great.style.backgroundColor = "rgb("+r+","+g+",78)";
				
				great.style.boxShadow = "inset 0 10px 7px rgb("+rs+","+gs+",150)";
				
				if(i%3==0)
				{
					r++;rs++;
					g--;gs--;
				}
			    if(i>time_i*4) i=time_i*4;
				
				if(i<0) { great.style.width="0px";return; }
				setTimeout("great_timer_start("+i+")", 250);
				
}		
var syncShown=0; var syncHidden=0;var alternate=0;
	function getQuestion(field,answer,questionID,time)   {
	      getQuestionHidden(field,answer,questionID,time);		
		 var board =  document.getElementById("display_app");
		 var board_temp = document.getElementById("temp_draw_board");
		 var board_temp2 = document.getElementById("temp_draw_board2");
		 	 /*  var test_c = document.getElementById("debug");
		 	   test_c.style.visibility = "visible";*/
		 document.getElementById("choose_field").style.visibility = "hidden";
         var pleasewait = document.getElementById("please_wait");
		 pleasewait.innerHTML ="<div style='position:absolute;width:565px;text-align:center;font-size:30px;font-weight:bold;font-family:cambria;color:#505050;'> Fasten your seat belts! <br /><br /><div style='text-align:center;'><img src='http://kf.ahens.com/kf_images_all/319.gif' /></div></div>";
	     
	    syncShown++;
	   
	      
	     if((syncShown-syncHidden)==1)
	     {  
	       board.innerHTML = board_temp.innerHTML;
	      }
	     else
	     { // alternate=1;
	        board.innerHTML = board_temp2.innerHTML;  }
	   /* test_c.innerHTML = "<strong>HTTP Request debugging =></strong><br />Highest level canvas state => "+syncShown+" | Middel Level canvas state => "+syncHidden;
	     if(alternate==1)
	       test_c.innerHTML += "<br /><strong>Lowest level canvas used. <br />HTTP REQUEST FREQUENCY => CRITICALLY HIGH</strong>";
	      else
	         test_c.innerHTML +="<br />HTTP REQUEST FREQUENCY => NORMAL";
	      alternate=0;   */
		document.getElementById("main_draw_board").style.visibility = "visible";
		document.getElementById("main_draw_board_behind").style.visibility = "visible";
		 var http;
		 if(window.XMLHttpRequest)
		   http = new XMLHttpRequest();
		  else 
			http = new ActiveXObject("Msxml2.XMLHTTP");
		 http.onreadystatechange = function () {    
                       if(http.readyState==4 && http.status == 200) 
					      {
						     syncHidden++;
						     board_temp.innerHTML = http.responseText;
							pleasewait.style.visibility = "hidden";
							if(answer==-2)
							 {
								getQuestion(field,-1,'','');
								great_timer_start(360);
								timer_start(90);
								
							  }
							
						      
						   }
				   
				   }
			 http.open("get", "http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?field="+field+"&answer="+answer+"&questionID="+questionID+"&time="+time_i, true);
			 http.send();
		     question_effect(200,0,-20);
		} 
		
			function getQuestionHidden(field,answer,questionID,time) {
				var board_temp3 = document.getElementById("temp_draw_board2");
				var http;
			    
				if(window.XMLHttpRequest) 
				      http = new XMLHttpRequest();
				else
					http = new ActiveXObject("Msxml2.XMLHTTP");
			    http.onreadystatechange = function () {
			    	
			    	if(http.readyState==4 && http.status==200){
			    			board_temp3.innerHTML = http.responseText;		
			    	}
				 }		      
				http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?field="+field+"&answer="+answer+"&questionID"+questionID+"&time="+time_i,true);
				http.send();
			 
			}
			
		 
	function question_effect(i,j,k) {
		var m; var choice;	
		var q = document.getElementById("question");
	    var c = document.getElementsByClassName("choices")[0];
	    var level = document.getElementById("level");
	    var qi=document.getElementById("question_info");
	    var choice = document.getElementById("choiceText");
	    for(m=0;m<5;m++)
	    {
	     choice = document.getElementsByClassName("choiceTex")[m];
	     if(choice.innerHTML.length>70) choice.style.fontSize="12px";
	     else if(choice.innerHTML.length>50)  choice.style.fontSize="14px";
	    } 
		 if(q.innerHTML.length>137) q.style.fontSize="17px";
		qi.style.right=""+k+"px";
		level.style.left = ""+k+"px";
		c.style.top = ""+i+"px";
		c.style.opacity=""+j+"";
		qi.style.opacity=""+j+"";
		level.style.opacity=""+j+"";
		c.style.visibility="visible";
		qi.style.visibility="visible";
		level.style.visibility="visible";
		k +=4;
		j +=0.2;
		i -=5;
		if(i<170) {qi.style.right="0px";level.style.left="0px";return;}
		setTimeout(function() { question_effect(i,j,k); }, 1);
		
		}
	function close_session()
			{
			document.getElementById("display_app").innerHTML= "";
			document.getElementById("temp_draw_board").innerHTML ="";
			document.getElementById("temp_draw_board2").innerHTML ="";
			document.getElementById("end").style.visibility="visible";
			document.getElementById("great_time").style.visibility="hidden";
		    
			document.getElementById("apptease_timer").innerHTML = "<div style='position:absolute;top:2px;left:2px;font-size:12px;font-weight:normal;'>I am going to sleep now. Don't wake me up again!</div>";
			document.getElementById("loader").innerHTML ="<img src='http://kf.ahens.com/kf_images_all/319.gif' />";
			
			var http;
			if(window.XMLHttpRequest)
				http = new XMLHttpRequest();
			else 
				http = new ActiveXObject("Msxml2.XMLHTTP");
			http.onreadystatechange = function() {
					if(http.readyState=4 && http.status==200)
					  {
					     
						 document.getElementById("loader").innerHTML ="";
						
						 loadscores(http.responseText);
					   }
					}
			http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?session_end="+1, true);
            http.send();
		 }	
	function loadscores(session) {
		var scorecard= document.getElementById("scorecard");
		scorecard.style.visibility ="visible";
		 document.getElementById("cvr2").style.visibility="visible";
	        loadquestions(session);
			loadattempt(session);
			loadpassed(session);
			loadcorrect(session);
			document.getElementById("session_no_disp").innerHTML = "Session <strong>"+session+"</strong>";
	       var score=document.getElementById("score_container");
                 var http;
			if(window.XMLHttpRequest)
				http = new XMLHttpRequest();
			else 
				http = new ActiveXObject("Msxml2.XMLHTTP");
			score.innerHTML = "<img src='http://kf.ahens.com/kf_images_all/319.gif' />";	
			http.onreadystatechange = function() {
					if(http.readyState=4 && http.status==200)
					  {
					  	if(http.responseText<-9)
					  	  score.style.fontSize = "75px";
					    score.innerHTML = http.responseText; 
					   }
					}
			http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?getscore="+1+"&session="+session, true);
            http.send();
}	
function loadquestions(session) {
	       var score=document.getElementById("res_container");
                 var http;
			if(window.XMLHttpRequest)
				http = new XMLHttpRequest();
			else 
				http = new ActiveXObject("Msxml2.XMLHTTP");
			score.innerHTML = "<img src='http://kf.ahens.com/kf_images_all/319.gif' />";	
			http.onreadystatechange = function() {
					if(http.readyState=4 && http.status==200)
					  {
					    score.innerHTML = http.responseText; 
					   }
					}
				http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?getquestion="+1+"&session="+session, true);
            http.send();
}	
function loadattempt(session) {
	       var score=document.getElementById("res_container1");
                 var http;
			if(window.XMLHttpRequest)
				http = new XMLHttpRequest();
			else 
				http = new ActiveXObject("Msxml2.XMLHTTP");
			score.innerHTML = "<img src='http://kf.ahens.com/kf_images_all/319.gif' />";	
			http.onreadystatechange = function() {
					if(http.readyState=4 && http.status==200)
					  {
					    score.innerHTML = http.responseText; 
					   }
					}
				http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?getattempt="+1+"&session="+session, true);
            http.send();
}	 
function loadcorrect(session) {
	       var score=document.getElementById("res_container2");
                 var http;
			if(window.XMLHttpRequest)
				http = new XMLHttpRequest();
			else 
				http = new ActiveXObject("Msxml2.XMLHTTP");
			score.innerHTML = "<img src='http://kf.ahens.com/kf_images_all/319.gif' />";	
			http.onreadystatechange = function() {
					if(http.readyState=4 && http.status==200)
					  {
					    score.innerHTML = http.responseText; 
					   }
					}
				http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?getcorrect="+1+"&session="+session, true);
            http.send();
}

function loadpassed(session) {
	       var score=document.getElementById("res_container3");
                 var http;
			if(window.XMLHttpRequest)
				http = new XMLHttpRequest();
			else 
				http = new ActiveXObject("Msxml2.XMLHTTP");
			score.innerHTML = "<img src='http://kf.ahens.com/kf_images_all/319.gif' />";	
			http.onreadystatechange = function() {
					if(http.readyState=4 && http.status==200)
					  {
					    score.innerHTML = http.responseText; 
					   }
					}
				http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/appengine.php?getpassed="+1+"&session="+session, true);
            http.send();
			
}
var flag=0;
function bring_session(a,b)  {
            var http;
			var output = document.getElementById("session_data_content");
			var image = document.getElementById("session_loader");
			var caller = document.getElementById("sessionWiseDataCaller");
			if(flag==1 && a==0) {
						output.innerHTML = "";
						caller.innerHTML = "Show History";flag=0;
						return;        			
			}
			if(window.XMLHttpRequest)
			   http = new XMLHttpRequest();
			  else
                 http = new ActiveXObject("Msxml2.XMLHTTP");
			image.innerHTML = "<div style='text-align:center;'><img src='http://kf.ahens.com/kf_images_all/319.gif' /></div>";
            http.onreadystatechange = function() {
								if(http.readyState==4 && http.status==200)
								{
								   image.innerHTML="";
								   output.innerHTML += http.responseText;
							       caller.innerHTML = "Hide History";
								   flag=1;
								}
			   }			
			http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/session_history.php?limit_a="+a+"&limit_b="+b,true);
			http.send();
			
}
function redraw_canvas () {
				var output = document.getElementById("session_data_content");
				output.innerHTML="";
				flag=0;
				bring_session(0,10);
				}
var stop=0; 
 function load_deep_detail(id,session) {
				var canvas = document.getElementById(id);
				var http;
				
				if(canvas.innerHTML!="") {canvas.innerHTML="";  canvas.style.padding="0px";return;}
				if(window.XMLHttpRequest)
				   http = new XMLHttpRequest();
				 else
					http = new ActiveXObject("Msxml2.XMLHTTP");
					 
				canvas.innerHTML = "<div style='text-align:center;'><img src='http://kf.ahens.com/kf_images_all/319.gif' /></div>";
				
				http.onreadystatechange = function() {  
				               if(http.readyState==4 && http.status==200)
							    { 
								  
								  canvas.style.padding="10px"
								  canvas.innerHTML = http.responseText;
								  
								}
						}			
				http.open("get","http://kf.ahens.com/home/apps/apptease/ver10/history_engine.php?sessionx="+session, true);
                http.send();				
				
				}
function showCorrectPeople(questionID)
		{
		 document.getElementById("cvr2").style.visibility = "visible";
		 document.getElementById("subscriberlist2").style.visibility = "visible";
		 document.getElementById("subslist").innerHTML = "<div style='text-align:center;'><img src='http://kf.ahens.com/kf_images_all/319.gif' /></div>";
		 var http;
          if(window.XMLHttpRequest)
				http = new XMLHttpRequest();
			else
				http = new ActiveXObject("Msxml2.XMLHTTP");
			http.onreadystatechange = function () { 
					if(http.readyState ==4 && http.status==200)
					 { 
					    document.getElementById("subslist").innerHTML = http.responseText;
						}
				  }		
		    http.open("get", "http://kf.ahens.com/home/apps/apptease/ver10/history_engine.php?showCorrectUsers="+1+"&questionID="+questionID,true);
			http.send();
		  
	}	  
	function hideSubsList2()   {
				
						 document.getElementById("cvr2").style.visibility = "Hidden";
		 document.getElementById("subscriberlist2").style.visibility = "hidden";
		 }
		  
    function noEscape(i)
		{
		  
			var up = document.getElementsByClassName("no_escape")[0];
			up.style.top = ""+i+"px";
			up.style.visibility="visible";
			i +=10;
			if(i==10) { noEscapeLeft(-150);return; }
			setTimeout(function() { noEscape(i); },5);
		}	
	   function noEscapeLeft(j)
		{
			var up = document.getElementsByClassName("no_escape")[1];
			up.style.left = ""+j+"px";
			up.style.visibility="visible";
			j +=10;
			if(j==10) { noEscapeRight(-130);return; }
			setTimeout(function() { noEscapeLeft(j); },10);
		}	
		function noEscapeRight(j)
		{
			var up = document.getElementsByClassName("no_escape")[2];
			up.style.right = ""+j+"px";
			up.style.visibility="visible";
			j +=10;
			if(j==10) { return; }
			setTimeout(function() { noEscapeRight(j); },10);
		}	
		var flag_esc=0;
		var flag_esc2=0;
		function showNoEsc(id)
			{
			if(flag_esc==0)
			{
			id.innerHTML ="You better try this on the options!";
			flag_esc=1;
			}
			else if(flag_esc==1)
			 {
			   id.innerHTML ="Uff, People are so in hurry these days. God Bless you!";
			   flag_esc=2;
			  }
			 else if(flag_esc==2)
				{
				id.innerHTML = "What's your problem? ";
				flag_esc=3;
				}
			else if(flag_esc==3)
				{
				id.innerHTML = "Why don't you just refresh this page or close this window?";
				flag_esc=4;
				}
			setTimeout(function () {id.innerHTML =""; },5000);
			}
function showNoEsc2(id)
			{
			if(flag_esc2==0)
			{
			id.innerHTML ="Checking whether it works or not? Happy! Now go try the problems.";
			flag_esc2=1;
			}
			else if(flag_esc2==1)
			 {
			   id.innerHTML ="Optimism has created so many mad scientists!";
			   flag_esc2=2;
			  }
			 else if(flag_esc2==2)
				{
				id.innerHTML = "You look so determined. We bow at such a hefty will power!";
				flag_esc2=3;
				}
			else if(flag_esc2==3)
				{
				id.innerHTML = "Ok, close it by refreshing. We can't stop such dedicated people.";
				flag_esc2=4;
				}
			setTimeout(function () {id.innerHTML =""; },5000);
			}
	function draw_no_escape(i)
			{
			var up = document.getElementsByClassName("no_escape")[0];
			up.style.top = ""+i+"px";
			i -=10;
			if(i==-110) { up.style.visibility="hidden";draw_noEscapeLeft(0);return; }
			setTimeout(function() { draw_no_escape(i); },5);
			
			}
	function draw_noEscapeLeft(i)
			{
			var up = document.getElementsByClassName("no_escape")[1];
			up.style.left = ""+i+"px";
			
			i -=10;
			if(i==-150) { up.style.visibility="hidden"; draw_noEscapeRight(0);return; }
			setTimeout(function() { draw_noEscapeLeft(i); },10);
			
			}
	function draw_noEscapeRight(i)
			{
			var up = document.getElementsByClassName("no_escape")[2];
			up.style.right = ""+i+"px";
			i -=10;
			if(i<=-130) { up.style.visibility="hidden"; return; }
			setTimeout(function() { draw_noEscapeRight(i); },10);
			
			}		
			
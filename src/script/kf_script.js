var collapse_state =1;
 var collapsable_elmnt; 
 var link,stop=0,timer;
 var pic,slide_state=0;
 	window.onkeyup = function(event) {
		  if(event.keyCode==27)
			    { hide_dialog();clearTimeout(timer);}
		  }
			
function collapse() {
        collapsable_elmnt   = document.getElementById("blue_panel");
		link = document.getElementById("collapse_link");
       if(collapse_state==1){
	       
	       start_expanding(0);
		   }
	   else {
	   
          start_collapsing(300);
		  }
      }
function start_expanding(i)  {
        i=i+15;
		collapsable_elmnt.style.top = ""+i+"px";
		
		
		if(i==300) {collapse_state=0; link.innerHTML="Hide Detail";return;}
		setTimeout(function () {start_expanding(i); }, 1);
		}
function start_collapsing(i)  {
			i=i-15;
			collapsable_elmnt.style.top =""+i+"px";
			
			if(i==0) {collapse_state=1;link.innerHTML="Learn More"; return;}
			setTimeout(function() {start_collapsing(i); }, 1);
			}
function slide(i)  {
        if(slide_state==0)
		{
			document.getElementById("we_closed").style.visibility="visible";
            pic = document.getElementById("kf_image_bg");
            document.getElementById("collapse_link2").innerHTML = "hide signup panel.";
			i=i-20;
			pic.style.top = ""+i+"px";
			if(i==-390) {slide_state=1; return;}
			setTimeout(function() { slide(i); },1);
		}
		else
		set_timer_return(-390);	
	}
			
	function set_timer_return(i)  {
		      document.getElementById("collapse_link2").innerHTML = "Register";
		      document.getElementById("reg_name").value="";
		      document.getElementById("reg_email").value="";
		      document.getElementById("reg_pwd").value="";
		      document.getElementById("reg_camp").value="";
		      document.getElementById("dummy_fn").style.visibility="visible";
		      document.getElementById("dummy_em").style.visibility ="visible";
		        document.getElementById("dummy_pwd").style.visibility ="visible";
		           document.getElementById("dummy_camp").style.visibility ="visible";
             i = i+20;     	
             pic.style.top = ""+i+"px";
			if(i==10) { document.getElementById("we_closed").style.visibility="hidden";slide_state=0; return;}
			setTimeout(function() { set_timer_return(i); },1);
			}
function intermed(i, id) {
           if(stop==0) {
             show_dialog(i,id);
				}
	       }	
				
function show_dialog(i, id) {
               stop=1;
				--i;
               var help = document.getElementById(id);
			   var help_mask = document.getElementById("behnd_dlg");
			   var msg = document.getElementById("msg_destroy");
			   var msg2 = document.getElementById("msg_destroy2");
			   msg.innerHTML ="Press Esc or this message will self destroy in "+i+" seconds!";
			   msg2.innerHTML ="Press Esc or this message will self destroy in "+i+" seconds!";
			   help.style.visibility = "visible";
			   help_mask.style.visibility = "visible";
			   if(i==-1) {hide_dialog();return;}
			   
				timer = setTimeout(function () { show_dialog(i, id); }, 1000);
				 
			   }
function hide_dialog() {
			   stop=0;
			   var help = document.getElementById("help_na");
			   var release =document.getElementById("release_na");
			   var help_mask = document.getElementById("behnd_dlg");
			   help.style.visibility = "hidden";
			   release.style.visibility = "hidden";
			   help_mask.style.visibility = "hidden";
			   
			   }
function clearinput(id1,id2){
	  
			   	var dummyTbox = document.getElementById(id2);
			   	var tbox = document.getElementById(id1);
		 		tbox.style.color ="#555";
			   	if(tbox.value.length!=0)
			   	    dummyTbox.style.visibility="hidden";
			   	  else
			   	      dummyTbox.style.visibility="visible";
			   }
		function highlight(id){
			    var target = document.getElementById("list_camp");
				target.style.visibility="hidden";
			  	var dummyTbox = document.getElementById(id);
			  	dummyTbox.style.color="#777";
		}	   
		function resetinput(id1,id2){
			var tbox = document.getElementById(id2);
			var dummyTbox = document.getElementById(id1);
			if(tbox.value.length==0)
			{
			dummyTbox.style.visibility = "visible";
			dummyTbox.style.color="#ccc";
			} 
		}	 
		var invalid_flag_name=0; var invalid_flag_email=0; var invalid_pwd=0;
		function validate_inpt(value,field){
			var validate_msg = document.getElementById("valid_msg");
			var name = document.getElementById("reg_name");
			var email = document.getElementById("reg_email");
			var pwd = document.getElementById("reg_pwd");
			if(value.length!=0)
			{
		    value= escape(value);
			fadeInMsg(validate_msg,0);
			validate_msg.innerHTML = "Please wait...<br />While we crosscheck your data";
			var http;
			if(window.XMLHttpRequest)
			 http = new XMLHttpRequest();
			else
			 http = new ActiveXObject("Msxml2.XMLHTTP");
			http.onreadystatechange = function () { 	
				if(http.readyState==4 && http.status==200){
					if(http.responseText==1)
					   {invalid_flag_name=1;
					   	validate_msg.innerHTML ="We are sure, no full name can be shorter than 4 characters.";
					   }
					 else if(http.responseText==2)
					   {invalid_flag_name=1;
					   	validate_msg.innerHTML ="Do you yourself think this can be a nice name!";
					   }
					   else if(http.responseText==3)
					     {invalid_flag_name=1;
					   	  validate_msg.innerHTML ="Capitalize the first letter of your name!";
					   	  }	
					   	else if(http.responseText==4)
					   		{invalid_flag_name=1;
					   		validate_msg.innerHTML ="Please tell us your last name!";
					   		}	
					   	  else if(http.responseText==6)
					   		{invalid_flag_name=1;
					   			validate_msg.innerHTML ="A name shouldn't contain so many capital letters.";
					   			}	
					   		else if(http.responseText==5)
					   			{invalid_flag_name=1;
					   				validate_msg.innerHTML ="Not more than one space is allowed between names.";
					   				}	
					   		  else if(http.responseText==7)
						   			{invalid_flag_email=1;
						   				validate_msg.innerHTML ="This could be address of your home, but not an Email.";
						   			}
						   		 else if(http.responseText ==8)
						   		 	{
						   		 		invalid_pwd=1;
						   		 		validate_msg.innerHTML = "Min. length of a password should be 6.";
						   		 	}	
						   		 else if(http.responseText==9)
						   		 	{
						   		 		invalid_flag_email=1;
						   		 		validate_msg.innerHTML = "This Email address is already registered.";
						   		 	}	
						   		 	else if(http.responseText==10)
					   					{
					   						invalid_flag_email=0;email.style.color = "green";fadeOutMsg(validate_msg,1);
					   						}
					   				else if(http.responseText==11)
					   					{
					   						invalid_flag_name=0;name.style.color = "green"; fadeOutMsg(validate_msg,1);
					   					}	
					   				else if(http.responseText==12)
					   					{
					   						invalid_pwd=0;pwd.style.color = "green"; fadeOutMsg(validate_msg,1);
					   					}		
					   	
								if(invalid_flag_name==1) name.style.color = "red";
			  					if(invalid_flag_email==1) email.style.color ="red";	
			  					if(invalid_pwd==1) pwd.style.color = "red";
				}
			}
		http.open("get","validate_form.php?entry="+value+"&field="+field, true);
		http.send();
		}
		else
			fadeOutMsg(validate_msg,1);
		
		}  
function bringcampus(val){
			var http;
			var target = document.getElementById("list_camp");
			if(val.length!=0)
			{
			target.style.visibility="visible";
			target.innerHTML ="Please wait...";
			if(window.XMLHttpRequest)
			 http = new XMLHttpRequest();
			else
			 http = new ActiveXObject("Msxml2.XMLHTTP");
			http.onreadystatechange = function () { 	
				if(http.readyState==4 && http.status==200){
					 
					target.innerHTML = http.responseText;
				}
			}
		http.open("get","validate_form.php?campusquery="+val, true);
		http.send();	
		}
		else 	target.style.visibility="hidden";
		
}		
		
function fadeInMsg(id,i){
	id.style.opacity = ""+i+"";
	id.style.visibility="visible";
	i += 0.1;
	if(i>1) {return;}
	setTimeout(function(){fadeInMsg(id,i); },1);
}
function fadeOutMsg(id,i){
	id.style.opacity = ""+i+"";

	i -= 0.1;
	if(i<0) {	id.style.visibility="hidden";return;}
	setTimeout(function(){fadeOutMsg(id,i); },1);
}
function addCampVal(hidden,campName){
	var camp=document.getElementById("reg_camp");
	var hid = document.getElementById("hidden_camp");
 
	if(hidden!=0 && campName!=0){
	camp.value = campName;
	hid.value= hidden;
	}
	else {
		camp.value="Anonymous Campus";
		hid.value="NOT";
	}
		var target = document.getElementById("list_camp");
			target.style.visibility="hidden";
	
}
 
 function submitdata()
 		{
 			var validate_msg = document.getElementById("valid_msg");
 			var name = document.getElementById("reg_name").value;
			var email = document.getElementById("reg_email").value;
			var pwd = document.getElementById("reg_pwd").value;
			var campus = document.getElementById("hidden_camp").value;
			var gender = document.getElementById("select_gender").value;
			name =escape(name);
			email =escape(email);
			pwd = escape(pwd);
			campus = escape(campus);
			gender = escape(gender);
			if(name.length==0 || email.length==0 || pwd.length==0)
			{
					fadeInMsg(validate_msg,0);
					validate_msg.innerHTML = "You are missing some items. Fill them first."
			}
			else if(invalid_flag_name==1)
					 {
					 	fadeInMsg(validate_msg,0);
						validate_msg.innerHTML = "First enter a proper name.";
					 } 
				else if(invalid_flag_email==1)
				     {
				     	fadeInMsg(validate_msg,0);
						validate_msg.innerHTML = "Please enter a valid email.";
				     }
				    else if(invalid_pwd==1)
				    	{
				    		fadeInMsg(validate_msg,0);
							validate_msg.innerHTML = "Please enter a valid password.";
				    	} 	 
				else
				{
					var http;
					var target = document.getElementById("kf_reg_button");
					 target.style.visibility="hidden";
					 var loader=document.getElementById("loader_submit");
					 loader.innerHTML ="<img src='http://kf.ahens.com/kf_images_all/loader.gif' />";
					 if(window.XMLHttpRequest)
						 http = new XMLHttpRequest();
					else
			 			http = new ActiveXObject("Msxml2.XMLHTTP");
					http.onreadystatechange = function () { 	
						if(http.readyState==4 && http.status==200){
							      loader.innerHTML ="";
							      name="";email="";campus="";pwd="";
							       target.style.visibility="visible";
				                   fadeInMsg(validate_msg,0);
				                   validate_msg.innerHTML =http.responseText;
							}
						}
					http.open("get","validate_form.php?submitdata=1&name="+name+"&email="+email+"&pwd="+pwd+"&campus="+campus+"&gender="+gender, true);
					http.send();	
				}	 
 		}
			   
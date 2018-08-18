$(document).ready(function(){
$('#signupBtn').on('click', function(){
		 var email = $("#email").val();
		 var name = $("#name").val();
		 var passwd = $("#password").val();
		 var number = $("#number").val();
		 if(email != 0)
    	 {
		    if(isValidEmailAddress(email) && ValidPhone(number))
		    {
		    	var params = 
		    	{
		    	 'name' : name,
		    	 'email' : email, 
		    	 'passwd' : passwd ,
		    	 'number': number,
		    	}; 
				$.ajax({
						url : './mess.php' ,
					    method : 'POST' ,
					    data : {
					        action : 'signup',
					        params : params,
					    },
					    success : function(data){
					    if(data=="right"){
						  	$('#form2')[0].reset();
							        alert("Вы зарегистрированы, теперь можете авторизоваться");	
							        signin();
						  }
						else{
						  	alert(data);
						  }	        		       
					    }
				});
			}
			else{
				alert('Пара email-телефон заполнены не корректно');
			}	
		 }
		 return false;
	});

$('#signinBtn').on('click', function(){
		 var email = $("#email1").val();
		 var passwd = $("#password1").val();
		 if(email != 0)
    	 {
		    if(isValidEmailAddress(email))
		    {
		    	var params = 
		    	{
		    	 'email' : email, 
		    	 'passwd' : passwd ,
		    	}; 
				$.ajax({
						url : './mess.php' ,
					    method : 'POST' ,
					    data : {
					        action : 'signin',
					        params : params,
					    },
					   
					   
				}).done(function( msg ) {
				  if(msg=="right"){
				  	$('#form2')[0].reset();
					        alert(msg);	
				  }
				  else{
				  	alert(msg);
				  }
				})

			}
			else{
				alert('Пара email заполнен не корректно');
			}	
		 }
		 return false;
	});
});


function showCom(){
	
	$.ajax({
	    url : './mess.php' ,
	    method : 'POST' ,
	    data : {
	        action : 'index',
	        	    },
	    success : function(comments){
	       		$("#content").html(comments);
	       		}
	});
}

function signup(){
	$("#signin").hide();
	$("#signup").slideToggle("fast");
}

function signin(){
	$("#signup").hide();
	$("#signin").slideToggle("fast");
		
		/*$.ajax({
		    url : './mess.php' ,
		    method : 'POST' ,
		    data : {
		        action : 'author',
		   	    },
		    success : function(comments){
		       		alert(comments);
		       		}
		});*/
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
    return pattern.test(emailAddress);
}

function ValidPhone(myPhone) {
    var re = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i;
    return re.test(myPhone);  
}  
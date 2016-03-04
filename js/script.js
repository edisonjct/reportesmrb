/*
Author: Pradeep Khodke
URL: http://www.codingcage.com/
*/

$('document').ready(function()
{ 
     /* validation */
	 $("#login-form").validate({
      rules:
	  {
			password: {
			required: true,
			},
			user_email: {
            required: true,
            email: true
            },
	   },
       messages:
	   {
            password:{
                      required: "Ingrese Contraseña"
                     },
            user_email: "Ingrese Su Usuario",
       },
	   submitHandler: submitForm	
       });  
	   /* validation */
	   
	   /* login submit */
	   function submitForm()
	   {		
			var data = $("#login-form").serialize();
				
			$.ajax({
				
			type : 'POST',
			url  : 'php/login_process.php',
			data : data,
			beforeSend: function()
			{	
				$("#error").fadeOut();
				$("#btn-login").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; Procesando ...');
			},
			success :  function(response)
			   {						
					if(response=="ok"){
										
						$("#btn-login").html('<img src="recursos/btn-ajax-loader.gif" /> &nbsp; Igresando ...');
						setTimeout(' window.location.href = "index.php"; ',4000);
					}
					else{
									
						$("#error").fadeIn(1000, function(){						
				$("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+response+' !</div>');
											$("#btn-login").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Ingresar');
									});
					}
			  }
			});
				return false;
		}
	   /* login submit */
});
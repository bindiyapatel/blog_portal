
$(document).ready(function(){

    // Input fields 
    var inputName = $("#name");
    var inputEmail = $("#email");
    var inputPassword = $("#password"); 

    var login = true;

    var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    function validateLogin(data)
    {   
        var valid = true;

        if(!login)
        {
            if(!inputEmail.val().match(mailformat))
            {
                $('#errorName').html('Name must have 5 characters');
                $('#errorName').show();
                valid = false;
            }
            else {
                $('#errorEmail').html('');
                $('#errorEmail').hide();
                valid = true;
            }    
        }
        
        if(!inputEmail.val().match(mailformat))
        {
            $('#errorEmail').html('Please enter valid email.');
            $('#errorEmail').show();
            valid = false;
        }
        else {
            $('#errorEmail').html('');
            $('#errorEmail').hide();
            valid = true;
        }

        if(inputPassword.val().trim().length < 8)
        {
            $('#errorPassword').html('Password must have atleast 8 character.');
            $('#errorPassword').show();
            valid = false;
        }
        else {
            $('#errorPassword').html('');
            $('#errorPassword').hide();
            valid =true;
        }

        return valid;
    }

    $("#btnLogin").click(function(){

        login = true;

        if(validateLogin())
        {
            let data = { 
                email: inputEmail.val().trim(), 
                password: inputPassword.val().trim(), 
                login: login
            };

            $.post("auth.php", data,
            function(data,status){
                let response = JSON.parse(data);
                if(response.status)
                {   
                    localStorage.setItem("loggedIn", true);
                    localStorage.setItem("user_id", response.user.user_id);

                    setTimeout(() => {
                        window.location = "index.php";
                    }, 2000);
                    $('#responseAlert').removeClass("alert-danger");
                    $('#responseAlert').addClass("alert-success");
                    $('#responseAlert').html(`<strong>${response.message}</strong>`);
                    $('#responseAlert').show();
                    inputUsername.val('');
                    inputPassword.val('');
                }
                else {
                    $('#responseAlert').removeClass("alert-success");
                    $('#responseAlert').addClass("alert-danger");
                    $('#responseAlert').html(`<strong>${response.message}</strong>`);
                    $('#responseAlert').show();
                }
            });

        } 
    });

    
    $("#btnSignup").click(function(){

        login = false;

        if(validateLogin())
        {
            let data = { 
                name: inputName.val().trim(),
                email: inputEmail.val().trim(), 
                password: inputPassword.val().trim(), 
                register: true
            };

            $.post("auth.php", data,
            function(data,status){
                let response = JSON.parse(data);
                if(response.status)
                {   
                    setTimeout(() => {
                        inputName.val('');
                        inputUsername.val('');
                        inputPassword.val('');
                        window.location = "login.php";
                    }, 2000);

                    $('#responseAlert').removeClass("alert-danger");
                    $('#responseAlert').addClass("alert-success");
                    $('#responseAlert').html(`<strong>${response.message}</strong>`);
                    $('#responseAlert').show();
                  
                    
                }
                else {
                    $('#responseAlert').removeClass("alert-success");
                    $('#responseAlert').addClass("alert-danger");
                    $('#responseAlert').html(`<strong>${response.message}</strong>`);
                    $('#responseAlert').show();
                }
            });

        } 
    });


});
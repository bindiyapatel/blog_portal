<?php 
    include_once "header.php";
?>

<div class="container-fluid" id="loginForm">
   <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"></div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
            <h2 class="text-center text-success"><strong>  Register </strong> </h2>
            
            <div class="alert alert-dismissible" id="responseAlert" style="display:none;">
                <!-- <strong>Success!</strong> Indicates a successful or positive action. -->
            </div>

            <div class="form-group">
                <label class="text-info">Name: </label>
                <input type="text" class="form-control" id="name" name="name" />
                <div class="text-danger" id="errorName" style="display:none;"></div>
            </div>

            <div class="form-group">
                <label class="text-info">email: </label>
                <input type="email" class="form-control" id="email" name="email" />
                <div class="text-danger" id="errorEmail" style="display:none;"></div>
            </div>
            <div class="form-group">
                <label class="text-info">Password: </label>
                <input type="password" class="form-control" id="password" name="password" />
                <div class="text-danger" id="errorPassword" style="display:none;"></div>
            </div>
            <div class="form-group">
               <button type="button" class="btn btn-success" id="btnSignup" name="btnLogin">Register</egbutton>
            </div>
            <h4 class="text-center"> Already have account ? <a  class="text" href="login.php"> Login</a> </h4>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12"></div>
    </div>
</div>
<?php include_once('config.php') ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= SITEKEY ?>"></script>
    <script>
      grecaptcha.ready(() => {
        grecaptcha.execute("<?= SITEKEY ?>", { action : "register" }).then(taken => document.querySelector("#recaptchaResponse"). valuw = token).catch(error => console.error(error));
      });
    </script>

<script src="auth.js"></script>

<?php
    include_once "footer.php"
?>
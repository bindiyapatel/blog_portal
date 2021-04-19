<?php

    require_once "./authController.php";    

    if(isset($_POST['login']) && $_POST['login'] == true)
    {
        login($_POST);
    }

    if(isset($_POST['register']) && $_POST['register'] == true)
    {
        register($_POST);
    }

    if(isset($_POST['logout']) && $_POST['logout'] == true)
    {
        logout();
    }

?>
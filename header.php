<?php 
  session_start();
?>
<html>
    <head>
        <title> Creative Blogs</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="bootstrap.min.css">
        <script src="jquery.min.js"></script>
        <script src="bootstrap.min.js"></script>
        <script src="popper.min.js"></script>

        <style>
        body {
          min-height: 400px;
          margin-bottom: 100px;
          clear: both;
        }
        .footer {
          position: fixed;
          left: 0;
          bottom: 0;
          /* margin-top: 20px; */
          width: 100%;
          background-color: red;
          color: white;
          text-align: center;
        }
        .disable {
          /* cursor: not-allowed */
          pointer-events: none;
        }
        </style>      
    </head>
    <body>

  <div class="row">
    <div class="text-center">
      <h1>Creative Blog Portal</h1>
      <p>
        Express your ideas and thoughts here.
        <p class="logout">
          <?php
            if(isset($_SESSION['isLoggedIn']) )
            {
          ?>   
            <a href="javascript:void()"  class="btn btn-danger" id="logoutButton">Logout</a>
          <?php
            }
          ?>
        </p>
      </p> 
    </div>    
  </div>

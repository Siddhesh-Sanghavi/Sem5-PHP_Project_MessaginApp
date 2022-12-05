<?php
  session_start();
  print_r($_SESSION);
  print_r($_COOKIE);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Website Name</title>
  </head>
  <body>
    <div id="index_navbar">
      <h1>Landing Page</h1>
      <div>
        <button id="index_LoginForm">Login Form</button>
      </div>
      <div>
        <button id="index_SignUpForm">SignUp</button>
      </div>
    </div>
    <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script> 
    <script type="text/javascript">
      $("#index_LoginForm").click(function(){
        window.open("http://trail-com.stackstaging.com/Project/LoginForm.php");
      })
      $("#index_SignUpForm").click(function(){
        window.open("http://trail-com.stackstaging.com/Project/SignUpForm.php");
      })
    </script>
  </body>
</html>
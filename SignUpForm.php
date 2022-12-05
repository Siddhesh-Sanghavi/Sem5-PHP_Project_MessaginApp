<?php
    session_start();
    if(array_key_exists('email',$_POST) and !array_key_exists('otp',$_POST) and !array_key_exists('submit_code',$_POST)){
        if($_POST['code']==true)
        {
            $emailTo=$_POST['email'];
            $subject="One-Time Password";
            $otp = random_int(100000, 999999);
            $body="Your One-Time Sign Up Password is : ".$otp;
            $headers="From: siddapps21@gmail.com";
            if(mail($emailTo,$subject,$body,$headers))
            {
                //print_r($_POST);
                $_SESSION['otp']=$otp;
                echo('OTP sent to your email address , please hek Spam incase not visible in inbox');
                //echo "alert(".$_SESSION['otp'].")";
                exit();
            }
            else
            {
                echo "OTP Not Sent";
                exit();
            }
        }
    }
    //print_r($_POST);
    //print_r($_SESSION);
    if(array_key_exists('checkotp',$_POST) and !array_key_exists('submit_code',$_POST)){
        if($_POST['otp_code']==true)
        {
            if($_POST['checkotp']==$_SESSION['otp'])
            {
                echo("Email Verified!");
                $_SESSION['otp']="";
                $_SESSION['verification']=true;
                exit();
            }
            else
            {
                echo("Incorret OTP please try again!");
                exit();
            }
        }
        else
        {
            echo "Not Sent";
        }
    }
    if(array_key_exists('submit_code',$_POST))
    {
        #print_r($_POST);
        //echo "Submission in progress";
        $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
        if(mysqli_connect_error())
        {
            #To check if the connection was successful
            die("There was an error connecting to the database, please try again");
        }
        else
        {
            //echo "Connection Successful";
            $query="INSERT INTO `user_data`(`first_name`,`last_name`,`date_of_birth`,`mobile_number`,`email_address`,`username`,`password`) VALUES('".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['DOB']."','".$_POST['mobnum']."','".$_POST['email']."','".$_POST['username']."','".md5($_POST['password'])."')";
            if(mysqli_query($link,$query))
            {
                echo "Sign Up Successful, Please Sign in using the entered credentials";
                //print_r($result);
                $query="SELECT `id` FROM `user_data` WHERE `username`='".$_POST['username']."'";
                //echo($query);exit();
                if($result=mysqli_query($link,$query))
                {
                    echo " got user id ";
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    print_r($row." ");
                    $query="INSERT INTO `user_last_login`(`uid`) VALUES(".$row['id'].")";
                    if(mysqli_query($link,$query))
                    {
                        echo "Both added";
                    }
                    else
                    {
                        echo "Both insertion successful";
                    }

                }
                else
                {
                    echo "Couldn't get user id";exit();
                }
            }
            else
            {
                echo "Couldn't Sign in due to some technical error please try again later";
            }

        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Sign up!</title>
</head>
<body>
    <div id="SignUp_NavBar">
        <div id="SignUp_titlename"><a href="http://trail-com.stackstaging.com/Project/">Name</a></div>
    </div>
    <div id="SignUpForm">
        <div id="SignUpHeader">
            <h1>Sign Up Form</h1>
        </div>
        <div id="SignUpFormBody">
            <form>
                <div id="PersonalSection">

                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname"><br>

                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname"><br>

                    <label for="DOB">Date of Birth</label>
                    <input type="date" id="DOB" name="DOB"><br>

                    <label for="mobnum">Contact Number</label>
                    <input type="text" id="mobnum" name="mobnum"><br>

                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email"><br><br>

                    <button id="getotp">Get OTP</button><br><br>

                    <div id="otp_sent_confirmataion"></div>

                    <label for="email_otp">Email OTP</label><br>
                    <input type="text" id="email_otp" name="email_otp" placeholder="******"><br>  
                    
                    <button id="checkotp">Check OTP</button><br><br>

                    <div id="otp_validation"></div>
                </div>

                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username"><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br>

                <label for="re-password">Renter Password:</label><br>
                <input type="password" id="re-password" name="re-password"><br><br>
            </form>
        </div>
        <div id="LoginFormFooter">
            <div>
                <button id="SignUpForm_LoginForm"><a target="_self" href="http://trail-com.stackstaging.com/Project/LoginForm.php">Login</a></button>
            </div>            
            <div>
                <button id="SignUpForm_submit" disabled="true">Submit</button>
            </div>
        </div>
        <div id="messages">

        </div>
    </div>
    <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script type="text/javascript">
        
        $("#getotp").click(function(e){
            e.preventDefault();
            if($("#firstname").val()!="")
            {
                if($("lastname").val()!="")
                {
                    if($("#DOB").val()!="")
                    {
                        //alert($("#mobnum").val());
                        if(phoneValidate($("#mobnum").val())==true)
                        {
                            if(isEmail($("#email").val())==true)
                            {
                                //alert("OK");
                                //alert($("#email").val());
                                $.ajax({
                                    method:'post',
                                    data:{email: $("#email").val(),code : true},
                                    success: function(response){
                                        $("#otp_sent_confirmataion").html('<p>'+response+'</p>');
                                    }
                                });
                            }
                            else
                            {
                                //alert("Not OK");
                                $("#messages").html("<p>Email Address is not correct</p>");
                            }
                        }   
                        else
                        {
                            //alert("Not OK");
                            $("#messages").html("<p>Mobile Number is not correct</p>");
                        }
                    }
                    else
                    {
                        //alert("Not OK");
                        $("#messages").html("<p>Date of Birth is Mandatory</p>");
                    }
                }   
                else
                {
                    //alert("Not OK");
                    $("#messages").html("<p>Last Name is Mandatory</p>");
                }
            }
            else
            {
                //alert("Not OK");
                $("#messages").html("<p>First Name is Mandatory</p>");
            }
        });
        $("#checkotp").click(function(e){
            e.preventDefault();
            if($("#email_otp").val()!="" && $.isNumeric($("#email_otp").val())){
                //alert("OK"+$("#email_otp").val());
                $.ajax({
                    method:'post',
                    data:{checkotp:$("#email_otp").val(),otp_code:true},
                    success: function(response){
                        $("#otp_validation").html('<p>'+response+'</p>');
                        $("#SignUpForm_submit").attr("disabled",false);
                    }
                });
            }
            else
            {
                alert("Not OK");
            }

        })
        $("#SignUpForm_submit").click(function(e){
            e.preventDefault();
            if($("#firstname").val()!="")
            {
                if($("lastname").val()!="")
                {
                    if($("#DOB").val()!="")
                    {
                        //alert($("#mobnum").val());
                        if(phoneValidate($("#mobnum").val())==true)
                        {
                            if(isEmail($("#email").val())==true)
                            {
                                if($("#username").val()!="")
                                {
                                    if($("#password").val()!="")
                                    {
                                        if($("#re-password").val()!="")
                                        {
                                            if($("#password").val()==$("#re-password").val())
                                            {
                                                //alert("Sign Up successful!");
                                                $.ajax({
                                                    method:'post',
                                                    data:{
                                                        firstname: $("#firstname").val(),
                                                        lastname: $("#lastname").val(),
                                                        DOB: $("#DOB").val(),
                                                        mobnum: $("#mobnum").val(),
                                                        email: $("#email").val(),
                                                        username: $("#username").val(),
                                                        password: $("#password").val(),
                                                        submit_code:true
                                                    },
                                                    success: function(response){
                                                        alert(response);
                                                        $("#messages").html("<p>Successffully Signed Up redirecting you to the Login Page</p>");
                                                        setTimeout(function(){
                                                            window.open("http://trail-com.stackstaging.com/Project/LoginForm.php","_self")
                                                            },3000
                                                        );
                                                    },
                                                    error: function(response){
                                                        //alert(response);
                                                        $("#messages").html("<p>Sign Up Unsuccessful try again</p>");
                                                        setTimeout(function(){
                                                            window.open("http://trail-com.stackstaging.com/Project/SignUpForm.php","_self")
                                                            },3000
                                                        );
                                                    }
                                                });
                                            }
                                            else
                                            {
                                                $("#messages").html("<p>Both passwords are not matching correctly</p>");
                                                $("#password").html(" ");
                                                $("#re-password").html(" ");
                                            }
                                        }
                                        else
                                        {
                                            $("#messages").html("<p>Please re-enter the password correctly</p>");
                                        }
                                    }
                                    else
                                    {
                                        $("#messages").html("<p>This password cannot be accepted</p>");
                                    }
                                }
                                else
                                {
                                    $("#messages").html("<p>This username cannot be accepted</p>");
                                }
                            }
                            else
                            {
                                //alert("Not OK");
                                $("#messages").html("<p>Email Address is not correct</p>");
                            }
                        }   
                        else
                        {
                            //alert("Not OK");
                            $("#messages").html("<p>Mobile Number is not correct</p>");
                        }
                    }
                    else
                    {
                        //alert("Not OK");
                        $("#messages").html("<p>Date of Birth is Mandatory</p>");
                    }
                }   
                else
                {
                    //alert("Not OK");
                    $("#messages").html("<p>Last Name is Mandatory</p>");
                }
            }
            else
            {
                //alert("Not OK");
                $("#messages").html("<p>First Name is Mandatory</p>");
            }
        })
        function phoneValidate(num){
            var mobile=num;
            var pattern = /^\d{10}$/;

            if (pattern.test(mobile)) 
            {
                return true;
            } 
            else
            {
                return false;
            }
        };
        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        };
    </script>
</body>
</html>
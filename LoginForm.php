<?php
    session_start();
    //print_r($_POST);
    ob_start();
    if(array_key_exists('login',$_POST) and array_key_exists('remember',$_POST))
    {
        if(array_key_exists('id',$_SESSION))
        {
            //print_r($_SESSION['id']);
            if($_POST['remember']==true)
            {
                if(array_key_exists('id',$_COOKIE))
                {
                    setcookie('id',"-1",time()-60*60*7);
                    setcookie('id',$_SESSION['id'],time()+60*60*7);
                    echo "Successfull";
                    $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
                    if(mysqli_connect_error())
                    {
                        die("There was an error connecting to the database, please try again");
                        echo "Unable to Connect to the Database";
                        exit();            
                    }
                    else
                    {
                        $date = date('Y-m-d H:i:s');
                        //echo($date);
                        $query=("UPDATE `user_last_login` SET `last_login`=now() WHERE `uid`=".$_SESSION['id']);
                        //echo($query);
                        if(mysqli_query($link,$query))
                        {
                            //echo "Updated login time";
                            exit();
                        }
                        else
                        {
                            //echo "Unable to Update login time";
                            exit();
                        }

                    }
                }
                else
                {
                    setcookie('id',$_SESSION['id'],time()+60*60*7);
                    //echo($_COOKIE['id']);
                    echo "Successfull";
                    $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
                    if(mysqli_connect_error())
                    {
                        die("There was an error connecting to the database, please try again");
                        echo "Unable to Connect to the Database";
                        exit();            
                    }
                    else
                    {
                        //$date = date('Y-m-d H:i:s');
                        //echo($date);
                        $query=("UPDATE `user_last_login` SET `last_login`=now() WHERE `uid`=".$_SESSION['id']);
                        //echo($query);
                        if(mysqli_query($link,$query))
                        {
                            //echo "Updated login time";
                            exit();
                        }
                        else
                        {
                            //echo "Unable to Update login time";
                            exit();
                        }

                    }
                }

            }
            else
            {
                echo "Successfull";exit();
            }

        }
        else
        {
            echo("Error in logging in please try again later!");
            exit();
        }

    }
    if(array_key_exists('getotp',$_POST))
    {
        $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
        if(mysqli_connect_error())
        {
            die("There was an error connecting to the database, please try again");
            echo "Unable to Connect to the Database";
            exit();            
        }
        else
        {
            $query="SELECT * FROM `user_data` WHERE `username` = '".$_POST['username']."'";
            if($result=mysqli_query($link,$query))
            {
                $rowcount=mysqli_num_rows($result);
                //echo "Entered ".$rowcount;
                if($rowcount==1)
                {
                    //echo "Entered";
                    $row=mysqli_fetch_array($result);
                    if($row['password']==md5($_POST['password']))
                    {
                        //echo "Entered";
                        $_SESSION['id']=$row['id'];
                        //echo $_SESSION['id'];
                        $emailTo=$row['email_address'];
                        $subject="One-Time Password";
                        $otp = random_int(100000, 999999);
                        $body="Your One-Time Login Password is : ".$otp;
                        $headers="From: siddapps21@gmail.com";
                        if(mail($emailTo,$subject,$body,$headers))
                        {
                            //print_r($_POST);
                            $_SESSION['otp']=$otp;
                            echo('OTP sent to your email address , please check Spam incase not visible in inbox');
                            //echo "alert(".$_SESSION['otp'].")";
                            exit();
                        }
                        else
                        {
                            echo "OTP Not Sent";
                            exit();
                        }
                    }
                    else
                    {
                        echo "Incorrect Password";
                        exit();
                    }
                }
                else
                {
                    echo "Unable to Find any user with this username2";
                    exit();
                }
            }
            else
            {
                echo "Unable to Find any user with this username";
                exit();
            }
        }
    }
    if(array_key_exists('check_otp',$_POST))
    {
        //echo ("Entered");exit();
        if($_POST['otp']==$_SESSION['otp'])
        {
            $_SESSION['otp']="";
            echo "Verification Complete, Click on Submit to Continue";
            exit();
        }
        else
        {
            echo "Incorrect OTP, please try again!";
            exit();
        }
    }
         
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login!</title>
</head>
<body>
    <div id="Login_NavBar">
        <div id="Login_titlename"><a href="http://trail-com.stackstaging.com/Project/">Name</a></div>
    </div>
    <div id="LoginForm">
        <div id="LoginFormHeader">
            <h1>Login</h1>
        </div>
        <div id="LoginFormBody">
            <form>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username"><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br><br>
                <button id="getotp">Get OTP</button><br><br>
                <label for="otp">OTP</label><br>
                <input type="text" id="otp" name="otp" placeholder="******"><br>
                <button id="checkotp">Check OTP</button><br><br>
                <input type="checkbox" id="remembercookie" name="remembercookie" value="1">
                <label for="remembercookie"> Remember Me</label><br>
            </form>
        </div>
        <div id="LoginFormFooter">
            <div>
                <button id="LoginForm_submit" disabled="true">Submit</button>
            </div>
            <div>
                <button id="LoginForm_SignUpForm"><a target="_self" href="http://trail-com.stackstaging.com/Project/SignUpForm.php">SignUp</a></button>
            </div><br>
            <div id="messages"></div>
        </div>

    </div>
    <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $("#getotp").click(function(e){
            e.preventDefault();
            if($("#username").val()!="")
            {
                if($("#password").val()!="")
                {
                    $.ajax({
                        method: 'post',
                        data:
                        {
                            username:$("#username").val(),
                            password:$("#password").val(),
                            getotp: true
                        },
                        success: function(response){
                            if($.trim(response) == null || $.trim(response) == undefined)
                            {
                                //No code here
                            }
                            else
                            {
                                $("#messages").html("<p>"+response+"</p>");
                            }
                            
                        }
                    });
                }
                else
                {
                    $("#messages").html("<p>Password field is mandatory</p>");
                }
            }
            else
            {
                $("#messages").html("<p>Username field is mandatory</p>");
            }
        })
        $("#checkotp").click(function(e){
            e.preventDefault();
            if($("#username").val()!="")
            {
                if($("#password").val()!="")
                {
                    if($("#otp").val()!="" && $.isNumeric($("#otp").val()))
                    {
                        $.ajax({
                            method: 'post',
                            data: {otp:$("#otp").val(),check_otp:true},
                            success: function(response){
                                if(response=='Verification Complete, Click on Submit to Continue'){
                                    $("#LoginForm_submit").attr("disabled",false);
                                    $("#messages").html(response);
                                }
                                else
                                {
                                    $("#messages").html(response);
                                }     
                            }
                        })
                    }
                    else
                    {
                        $("#messages").html("<p>Incorrect OTP, please try again!</p>");
                        $("#otp").html(" ");
                    }
                }
                else
                {
                    $("#messages").html("<p>Password field is mandatory</p>");
                }
            }
            else
            {
                $("#messages").html("<p>Username field is mandatory</p>");
            }
        })
        $("#LoginForm_submit").click(function(e){
            e.preventDefault();
            //alert($("#remembercookie").val());
            if($('#remembercookie').is(':checked'))
            {
                    $.ajax({
                    method: 'post',
                    data:{
                        login:true,
                        remember:true,
                    },
                    success: function(response){
                        if(response=='Successfull')
                        {
                            //alert(response+"1");
                            window.open("http://trail-com.stackstaging.com/Project/user_dashboard.php");
                        }
                        else
                        {
                            alert(response);
                        }
                    }
                });
            }
            else
            {
                $.ajax({
                    method: 'post',
                    data:{
                        login:true,
                        remember:false
                    },
                    success: function(response){
                        if(response=='Successfull')
                        {
                            //alert(response+"2");
                            window.open("http://trail-com.stackstaging.com/Project/user_dashboard.php");
                        }
                        else
                        {
                            alert(response);
                        }
                    }
                });
            }
        })
    </script>
</body>
</html>
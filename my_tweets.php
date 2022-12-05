<?php
    session_start();
    ob_start();
    if((array_key_exists('id',$_COOKIE) and $_COOKIE['id']!='-1') OR (array_key_exists('id',$_SESSION) and $_SESSION['id']!='-1'))
    {
        returnid();
        if(array_key_exists('mid',$_POST))
        {
            //print_r($_POST);
            //echo("Inside array ".$_POST['mid']);
            $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
            if(mysqli_connect_error())
            {
            #To check if the connection was successful
            die("There was an error connecting to the database, please try again");
            }
            else
            {
                //echo(is_numeric($_SESSION['id']));
                $query="DELETE FROM `message_data` WHERE `id`=".$_POST['mid']." AND `uid`=".$_SESSION['id'];
                if(mysqli_query($link,$query))
                {
                    echo("Deleted Successfully");exit();
                }
                else
                {
                    echo("Deleted UnSuccessfully");exit();
                }
            }
        }
        $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
        if(mysqli_connect_error())
        {
          #To check if the connection was successful
          die("There was an error connecting to the database, please try again");
        }
        else
        {
          //echo(is_numeric($_SESSION['id']));
          $query="SELECT * FROM `message_data` WHERE `uid`=".$_SESSION['id'];
          if($result=mysqli_query($link,$query))
          {
            //$_SESSION['mydata']=$result;
            $data="<div id='message'>";
            while($row=mysqli_fetch_array($result))
            {
              $data=$data."<div class=\"message_container\"><div class=\"message_body\">".$row['message']."</div><div class=\"time-stamp\">".$row['upload_time']."</div><button onclick=\"myFunction(".$row['id'].")\">delete</button></div>";
            }
            $data=$data."</div>";
            //echo($data);exit();
            
          }
          else
          {
            //echo "Couldn't query my messages"; exit();
          }
        }
        if(array_key_exists('logout',$_POST))
        {
            if(array_key_exists('id',$_COOKIE))
            {
                //echo "in cookies";
                setcookie('id',"-1",time()-60*60*7);
                session_destroy();
                echo "Logout_successful";exit();
            }
            else
            {
                session_destroy();
                //echo "Not in cookies";
                echo "Logout_successful";exit();
            }
            //setcookie('id',time()-60*60*7);
        }
    }
    else
    {
        header("Location: http://trail-com.stackstaging.com/Project/");
        ob_end_flush();
    }
    function returnid() {
        if(array_key_exists('id',$_SESSION) and !array_key_exists('id',$_COOKIE))
        {
          $_SESSION['id']=$_SESSION['id'];
        }
        elseif(array_key_exists('id',$_COOKIE)){
          $_SESSION['id']=$_COOKIE['id'];
        }
      }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>MyPings</title>
    <style type="text/css">
        .message_container{
            margin: 20px;
            width: 400px;
            box-sizing:border-box;
            padding: 20px;
            background-color: #333;
            color: #fff;
            display: block;
            text-decoration: none;
        }
    </style>
    </head>
    <body>
        <div id="mytweets_navbar">
            <!-- Button trigger modal -->
            <button id="back_to_dashboard"><-Dashboard</button>
            <button id="dashboard_logout">Logout</button>
        </div>
        <div id="mytweets_body">
            <?php echo $data;  ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- jQuery first, then Bootstrap JS. -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $("#dashboard_logout").click(function(e){
                e.preventDefault();
                $.ajax({
                    method: 'post',
                    data:{logout:true},
                    success:function(response){
                    //alert(response);
                    if(response=="Logout_successful")
                    {
                        //alert(response);
                        window.open("http://trail-com.stackstaging.com/Project/","_self");
                    }
                    else
                    {
                        alert("Error in logging out");
                    }
                    }
                });
            })
            $("#back_to_dashboard").click(function(e){
                e.preventDefault();
                window.open("http://trail-com.stackstaging.com/Project/user_dashboard.php");
            })
            function myFunction(id)
            {
                //alert("Inside function "+id);
                $.ajax({
                    type : "POST",  
                    url  : "http://trail-com.stackstaging.com/Project/my_tweets.php", 
                    data : { mid:id},
                    success: function(response){  
                        //alert(response);
                        if(response=='Deleted Successfully')
                        {
                            window.open("http://trail-com.stackstaging.com/Project/my_tweets.php","_self");
                        }
                        else
                        {
                            alert(response);
                        }
                    }
                });
            }
        </script>
    </body>
</html>
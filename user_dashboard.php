<?php
  session_start();
  ob_start();
  //print_r($_COOKIE);
  //print_r($_SESSION);
  if((array_key_exists('id',$_COOKIE) and $_COOKIE['id']!='-1') OR (array_key_exists('id',$_SESSION) and $_SESSION['id']!='-1'))
  {
    returnid();
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
    if(array_key_exists('data_input',$_POST))
    {
      //echo($_POST['share_message']);exit();
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
          #To check if the connection was successful
          die("There was an error connecting to the database, please try again");
      }
      else
      {
        $query="INSERT INTO `message_data`(`uid`,`message`)VALUES('".$_SESSION['id']."','".$_POST['share_message']."')";
        if(mysqli_query($link,$query))
        {
          echo "Message added successfully";
          exit();
        }
        else
        {
          echo "Couldn't add message";
          exit();
        }
      }
    }
    if(array_key_exists('addfollowerbar_footer_search',$_POST))
    {
      //print_r($_POST);exit();
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
        die("There was an error connecting to the database, please try again");
      }
      else
      {
        //echo("Connected to db");exit();
        $query="SELECT * FROM `user_data` WHERE `username`='".$_POST['followername']."'";
        if($result=mysqli_query($link,$query))
        {
          //print_r($result);exit();
          $row = mysqli_fetch_array($result, MYSQLI_NUM);
          //print_r($row);exit();
          if($row!="")
          {
            //print_r($row);exit();
            echo('true');exit();
          }
          else
          {
            echo('false');exit();
          }
        }
        else
        {
          echo("Unable to query");
        }
      }
    }
    if(array_key_exists('addfollowerbar_footer_add',$_POST))
    {
      //print_r($_POST);//exit();
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
        die("There was an error connecting to the database, please try again");
      }
      else
      {
        //echo("Connected to db");//exit();
        $query="SELECT * FROM `user_data` WHERE `username`='".$_POST['followername']."'";
        if($result=mysqli_query($link,$query))
        {
          //print_r($result);//exit();
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          //print_r($row);//;exit();
          if($row!="")
          {
            //print_r($row);//exit();
            //echo('true');//exit();
            $query="SELECT * FROM `user_following` WHERE `user`=".$_SESSION['id']." AND `following`=".$row['id'];
            $result=mysqli_query($link,$query);
            //print_r($result);
            if(mysqli_num_rows($result)==0)
            {
              //echo("Can be added");//exit();
              //echo($_SESSION['id']);
              //print_r($row);
              //echo($row['id']);
              $query="INSERT INTO `user_following`(`user`,`following`) VALUES (".$_SESSION['id'].",".$row['id'].")";
              if(mysqli_query($link,$query))
              {
                echo "true";exit();
              }
              else
              {
                echo "false";exit();
              }
            }
            else
            {
              echo("Already added to your followers list");exit();
            }
          }
          else
          {
            echo('false');exit();
          }
        }
        else
        {
          echo("Unable to query");exit();
        }
      }
    }
    if(array_key_exists('getuserlist',$_POST))
    {
      //print_r($_POST);//exit();
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
        die("There was an error connecting to the database, please try again");
      }
      else
      {
        //echo($_SESSION['id']."  ");
        $query="SELECT `first_name`,`last_name`,`username`,`id` FROM `user_data` WHERE `id` IN (SELECT `following` FROM `user_following` WHERE `user`=".$_SESSION['id'].")";
        //echo($query);
        if($result=mysqli_query($link,$query))
        {
          $numrows=mysqli_num_rows($result);
          //print_r($result);
          //echo($numrows);
          if($numrows>0)
          {
            //echo("IN");
            $user_table="<table class=\"table table-striped\"><thead><tr><th>Firstname</th><th>Lastname</th><th>Username</th><th>Action</th></tr></thead>";
            $user_table=$user_table."<tbody>";
            while($row = $result -> fetch_assoc())
            {
              $user_table=$user_table."<tr><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['username']."</td><td><button onclick='Removeuser(".$row['id'].")'>Remove</button></td></tr>";
            }
            $user_table=$user_table."</tbody>";
            $user_table=$user_table."</table>";
            echo($user_table);exit();
          }
          else
          {
            echo("No user list");exit();
          }
        }
        else
        {
          echo("Unable to get output from db");exit();
        }
      }
    }
    if(array_key_exists('rid',$_POST))
    {
      //print_r($_POST);
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
        die("There was an error connecting to the database, please try again");
      }
      else
      {
        $query="DELETE FROM `user_following` WHERE `user`=".$_SESSION['id']." AND `following`=".$_POST['rid'];
        //echo($query);
        if(mysqli_query($link,$query))
        {
          echo("Successful");exit();
        }
        else
        {
          echo("Unsucessful");exit();
        }
      }
    }
    if(array_key_exists('shareonmytimeline',$_POST))
    {
      //print_r($_POST);//exit();
      if($_SESSION['id']!=$_POST['uid'])
      {
        //echo("Working");//exit();
        $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
        if(mysqli_connect_error())
        {
          die("There was an error connecting to the database, please try again");
        }
        else
        {
          $query="SELECT * FROM `request_data` WHERE `message_id`=".$_POST['pid']." AND `asker_id`=".$_SESSION['id']." AND `acceptor_id`=".$_POST['uid'];
          //echo($query);
          if($result=mysqli_query($link,$query))
          {
            //echo("Successful");//exit();
            $numrows=mysqli_num_rows($result);
            //print_r($result);
            //echo($numrows);
            if($numrows==0)
            {
              //echo("Workning12");exit();
              $query="INSERT INTO `request_data`(`message_id`,`asker_id`,`acceptor_id`)VALUES(".$_POST['pid'].",".$_SESSION['id'].",".$_POST['uid'].")";
              if(mysqli_query($link,$query))
              {
                echo("Successfull");exit();
              }
              else
              {
                echo("Unsucessful data entry into db");exit();
              }
            }
            elseif($numrows==1)
            {
              echo("Already sent");exit();
            }
          }
          else
          {
            echo("Unsucessful query execution");exit();
          }
        }
      }
      else
      {
        echo("Aleady on your timeline");exit();
      }
    }
    if(array_key_exists('getrequestlist',$_POST))
    {
      //print_r($_POST);//exit();
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
        die("There was an error connecting to the database, please try again");
      }
      else
      {
        $query="SELECT `message_data`.`message`,`message_data`.`id` AS `mid`,`user_data`.`id` AS `aid`,`user_data`.`first_name`,`user_data`.`last_name`,`user_data`.`username` FROM `request_data` JOIN `user_data` ON `request_data`.`asker_id`=`user_data`.`id` JOIN `message_data` on `request_data`.`message_id`=`message_data`.`id` WHERE `request_data`.`acceptor_id`=".$_SESSION['id'];
        if($result=mysqli_query($link,$query))
        {
          //echo("Successful");//exit();
          $numrows=mysqli_num_rows($result);
          //print_r($result);
          //echo($numrows);
          if($numrows>0)
          {
            //echo("IN");
            $request_table="<table class=\"table table-striped\"><thead><tr><th>Asked By</th><th>Message</th><th>Action</th></tr></thead>";
            $request_table=$request_table."<tbody>";
            while($row = $result -> fetch_assoc())
            {
              $request_table=$request_table."<tr><td>".$row['username']."@".$row['first_name'].$row['last_name']."</td><td>".substr($row['message'],0,15)."........"."</td><td><button onclick='Acceptrequest(".$row['mid'].",".$row['aid'].")'>Accept</button> <button onclick='Rejectrequest(".$row['mid'].",".$row['aid'].")'>Reject</button></td></tr>";
            }
            $request_table=$request_table."</tbody>";
            $request_table=$request_table."</table>";
            echo($request_table);exit();
          }
          else
          {
            echo("No requests");exit();
          }
        }
        else
        {
          echo("Unsucessful data retrieval");exit();
        }
      }
    }
    if(array_key_exists('acceptrequest',$_POST))
    {
      //print_r($_POST);
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
        die("There was an error connecting to the database, please try again");
      }
      else
      {
        $query="SELECT `message` FROM `message_data` WHERE `id`=".$_POST['mid'];
        #echo($query);
        if($result=mysqli_query($link,$query))
        {
          //echo("Successful");//exit();
          $numrows=mysqli_num_rows($result);
          //print_r($result);
          //echo($numrows);
          if($numrows==1)
          {
            $row = $result -> fetch_assoc();
            print_r($row);
            $query="INSERT INTO `message_data`(`uid`,`message`) VALUES (".$_POST['request_id'].",'".$row['message']."')";
            if(mysqli_query($link,$query))
            {
              $query="DELETE FROM `request_data` WHERE `message_id`=".$_POST['mid']." AND `asker_id`=".$_POST['request_id']." AND `acceptor_id`=".$_SESSION['id'];
              if(mysqli_query($link,$query))
              {
                echo("Successfull");exit();
              }
              else
              {
                echo("Unable to delete entry from the request table");exit();
              }
            }
            else
            {
              echo("Unable to add entry into the message table");exit();
            }
          }
          else
          {
            echo("Ouput from message table isn't as expected");exit();
          }
        }
        else
        {
          echo("Unable to fetch data from message table");exit();
        }
      }
    }
    if(array_key_exists('deleterequest',$_POST))
    {
      //print_r($_POST);
      $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
      if(mysqli_connect_error())
      {
        die("There was an error connecting to the database, please try again");
      }
      else
      {
        $query="DELETE FROM `request_data` WHERE `message_id`=".$_POST['mid']." AND `asker_id`=".$_POST['request_id']." AND `acceptor_id`=".$_SESSION['id'];
        if(mysqli_query($link,$query))
        {
          echo("Successfull");exit();
        }
        else
        {
          echo("Unable to delete entry from the request table");exit();
        }
      }
    }
    $link=mysqli_connect("sdb-51.hosting.stackcp.net","twitter-3530303345d6","ef3sswlltb","twitter-3530303345d6");
    if(mysqli_connect_error())
    {
      die("There was an error connecting to the database, please try again");
    }
    else
    {
      $query="SELECT `message_data`.`id` AS `mid`,`message_data`.`message`,`message_data`.`upload_time`,`user_data`.`first_name`,`user_data`.`last_name`,`user_data`.`username`,`user_data`.`id` FROM `message_data` JOIN `user_data` ON `message_data`.`uid`=`user_data`.`id` WHERE `uid` IN (SELECT `following` FROM `user_following` WHERE `user`=".$_SESSION['id'].") ORDER BY `upload_time` DESC;";
      echo($query);
      if($result=mysqli_query($link,$query))
      {
        //echo("Successful");exit();
        $numrows=mysqli_num_rows($result);
        //print_r($result);
        //echo($numrows);
        if($numrows>0)
        {
            $dashboard_messages="<div id='message'>";
            //echo("IN");
            while($row = $result -> fetch_assoc())
            {
              $dashboard_messages=$dashboard_messages."<div class=\"message_container\" ><div class=\"message_header\">".$row['username']." @".$row['first_name'].$row['last_name']."  ~ </div><div class=\"message_body\">".$row['message']."</div><div class=\"time-stamp\">".$row['upload_time']."</div><button onclick=\"shareonmytimeline(".$row['mid'].",".$row['id'].")\">Share on my Timeline</button></div>";
            }
            $dashboard_messages=$dashboard_messages."</div>";
        }
        else
        {
          $dashboard_messages="No messages yet";
        }
      }
      else
      {
        echo("Unsucessful01");exit();
      }
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
  <title>Dashboard</title>
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
  <div id="dashboard_navbar">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" id="showallmytweets">My Messages</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adduserModal">Add user</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userMoal" id="getuserlist">User list</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Share it!</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg" id="pedingrequests">Requests</button>
    <button type="button" class="btn btn-primary" id="dashboard_logout">Logout</button>
  </div>
  <div id="dashboard_body">
    <?php echo($dashboard_messages); ?>
  </div>

  <!--Request Modal Body-->
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel3">Request Bar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="request_modal_body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Add user modal body -->
  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="adduserModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel2">Followers Bar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="addfollowerbar">
            <div id="addfollowerbar_header">
              <h5>Add Follower</h5>
            </div>
            <div id="addfollowerbar_body">
              <label for="addfollower">Type the username:</label><br>
              <input type="text" id="addfollower" name="addfollower"><br>
              <div id="addfollowerbar_body_message"></div>
            </div>
            <div id="addfollowerbar_footer">
              <button id="addfollowerbar_footer_search">Search</button>
              <button id="addfollowerbar_footer_Add" disabled="true">Add</button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!--Get user Modal-->
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="userMoal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel2">Followers Bar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="user_list_modal_body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="text_area">Message:</label><br>
          <textarea id="text_area" name="text_area" rows="4" cols="50" placeholder="Share it with your ones"></textarea><br>
          <div id="message"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="share_message">Share</button>
        </div>
      </div>
    </div>
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
    $("#share_message").click(function(e){
      e.preventDefault();
      //alert("Inside share_message jquery");
      if($("#text_area").val()!="")
      {
        //alert($("#text_area").val())
        $.ajax({
          method:'post',
          data:{share_message:$("#text_area").val(),data_input:true},
          success: function(response){
            alert(response);
          }
        });
      }
      else
      {
        $("#message").html("<p>Add some message</p>");
      }
    })
    $("#showallmytweets").click(function(e){
      e.preventDefault();
      window.open("http://trail-com.stackstaging.com/Project/my_tweets.php");
    })
    $("#addfollowerbar_footer_search").click(function(e){
      //alert("In JQ");
      if($("#addfollower").val()!="")
      {
        //alert("HI");
        $.ajax({
          method:'post',
          data:{followername:$("#addfollower").val(),addfollowerbar_footer_search:true},
          success:function(response){
            //alert(response);
            if(response=='true')
            {
              $("#addfollowerbar_body_message").html('<p>User found</p>');
              $("#addfollowerbar_footer_Add").attr("disabled",false);
            }
            else
            {
              $("#addfollowerbar_body_message").html("<p>Invalid username</p>");
            }
          }
        });
      }
      else
      {
        $("#addfollowerbar_body_message").html("<p>Please enter a username</p>");
      }
    })
    $("#addfollowerbar_footer_Add").click(function(e){
      e.preventDefault();
      if($("#addfollower").val()!="")
      {
        //alert("Hi");
        $.ajax({
          method:'post',
          data:{followername:$("#addfollower").val(),addfollowerbar_footer_add:true},
          success:function(response){
            alert(response);
            location.reload(true);
          }
        });
      }
    })
    $("#getuserlist").click(function(e){
      //alert("Inside JQ");
      $.ajax({
        method:'post',
        data:{getuserlist:true},
        success:function(response){
          //alert(response);
          $("#user_list_modal_body").html(response);
        }
      })
    })
    function Removeuser(id){
      //alert(id);
      $.ajax({
        type : "POST",  
        url  : "http://trail-com.stackstaging.com/Project/user_dashboard.php", 
        data : { rid:id},
        success: function(response){  
          //alert(response);
          if(response=='Successful')
          {
            window.open("http://trail-com.stackstaging.com/Project/user_dashboard.php","_self");
          }
        }
      });
    }
    function shareonmytimeline(pid,id){
      //alert(pid+"xyz");
      $.ajax({
        type : "POST",  
        url  : "http://trail-com.stackstaging.com/Project/user_dashboard.php", 
        data : {uid:id,pid:pid,shareonmytimeline:true},
        success: function(response){  
          if(response=='Successfull')
          {
            echo("Request sent");
            window.open("http://trail-com.stackstaging.com/Project/user_dashboard.php","_self");
          }
          else
          {
            alert(response);
          }
        }
      });
    }
    $("#pedingrequests").click(function(e){
      e.preventDefault();
      //alert("Inside JQ");
      $.ajax({
        method:'post',
        data:{getrequestlist:true},
        success:function(response){
          //alert(response);
          $("#request_modal_body").html(response);
        }
      })
    })
    function Acceptrequest(mid,request_id){
      alert(mid+","+request_id);
      $.ajax({
        type : "POST",  
        url  : "http://trail-com.stackstaging.com/Project/user_dashboard.php", 
        data : {mid:mid,request_id:request_id,acceptrequest:true},
        success: function(response){  
          if(response=='Successfull')
          {
            alert(response);
            window.open("http://trail-com.stackstaging.com/Project/user_dashboard.php","_self");
          }
          else
          {
            alert(response);
          }
        }
      });
    }
    function Rejectrequest(mid,request_id){
      alert(mid+","+request_id);
      $.ajax({
        type : "POST",  
        url  : "http://trail-com.stackstaging.com/Project/user_dashboard.php", 
        data : {mid:mid,request_id:request_id,deleterequest:true},
        success: function(response){  
          if(response=='Successfull')
          {
            alert(response);
            window.open("http://trail-com.stackstaging.com/Project/user_dashboard.php","_self");
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
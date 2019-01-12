<?php
require 'db.php';

  // auth.php for IO smart plug
  session_start();

  if(@$_POST["login"] == "true"){
    // login flow
     $email = $_POST['email'];
     $pass = $_POST['password'];
    $userdata = $db->query("SELECT * FROM users WHERE email='$email'")->fetch_all();
    // check if account account_exist
    if(sizeof($userdata) > 0){
      if(password_verify($pass,$userdata[0][4])){
        $_SESSION["logged_in"] = true;
        $_SESSION["id"] = $userdata[0][0];
        $_SESSION["name"] = $userdata[0][2];
        $_SESSION["cdevice"] = $userdata[0][3];
        $_SESSION['email'] = $email;
        header("Location: index.php");
     }else{
       $_SESSION["error"] = "Login failed, check email or password!";
       header("Location: login.php?login");
     }

    }else{
      $_SESSION["error"] = "Account does not exist!";
      header("Location: login.php?login");
    }
  }

  if(@$_POST["signup"]=="true"){
    // signup flow
    @$devicekey = $_POST['device'];
    @$email = $_POST['email'];
    @$fullname = $_POST['fname'];
    @$password = $_POST['password'];
      // check for XSS recheck device if it exists...
      $kchk = $db->query("SELECT * FROM devices WHERE keyx='$devicekey' AND owner='none'")->fetch_all();
      if($kchk && sizeof($kchk) > 0){
        // if key is not owned -> proceed to saving user account.
        $checkCredentials = $db->query("SELECT * FROM users WHERE email='$email'")->fetch_all();
        if(sizeof($checkCredentials) == 0){
          $spass = password_hash($password,PASSWORD_BCRYPT);
          $saveuser = $db->query("INSERT INTO users (email,name,current_device,password) VALUES ('$email','$fullname','$devicekey','$spass')");
            if($saveuser){
              $getOwner = $db->query("SELECT * FROM users WHERE email='$email'");
                  if($getOwner){
                    $getownerId = $getOwner->fetch_all()[0][0];
                     $owndevice = $db->query("UPDATE devices SET owner='$getownerId' WHERE keyx='$devicekey'");
                     if($owndevice){
                       $createLog = $db->query("INSERT INTO data_logs (device_key) VALUES ('$devicekey')");
                       $_SESSION['id'] = $getownerId;
                       $_SESSION['logged_in'] = true;
                       $_SESSION['name'] = $fullname;
                       $_SESSION['cdevice'] = $devicekey;
                       $_SESSION['email'] = $email;
                       header("location: index.php");
                     }
                  }
            }else{
              // cannot create account
              $_SESSION['serror'] = "Account cannot be created, try again later!";
              header("location: login.php?signup");
            }
        }else{
          $_SESSION['serror'] = "The email you provided is in use by another user";
          header("location: login.php?signup");
        }
      }else{
      // if owned, send error to user.
        $_SESSION['serror'] = "Device key is invalid account cannot be created!";
        header("location: login.php?signup");
      }
  }

  // logout
  if(isset($_GET["logout"])){
    session_destroy();
    header("Location: login.php?login");
  }

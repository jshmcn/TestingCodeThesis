<?php
require 'db.php';
session_start();

?>
<html>
<head>
  <link rel="icon" href="./img/icon.png">
  <meta name="theme-color" content="rgb(6, 24, 115)">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <link rel="stylesheet" href="./css/blk.css">
  <link rel="stylesheet" href="./css/bs4.css">
  <link rel="stylesheet" href="./css/font-awesome.css">
  <link rel="stylesheet" href="./css/io.css">
</head>
<body class="bg-dark">
  <div id="app">
  <?php
   // for login
    if(isset($_GET['login'])) :
  ?>
    <center>
      <title>IO | Log In</title>
       <div class="container bg-light rounded login-card p-3">
         <form action="auth.php" method="POST">
           <img src="./img/io.png" width="70px" class="mt-3">
           <p class="text-muted">Login to your Account!</p>
           <hr>
           <p class="text-danger">
             <?php
              if(isset($_SESSION["error"])){
               echo '<i class="fa fa-times"></i> '.@$_SESSION["error"];
              }
             ?>
           </p>
           <input name="login" value="true" class="d-none">
           <input id="login" name="email" type="email" placeholder="Email" class="form-control mb-2" required>
           <input type="password" name="password" placeholder="Password" class="form-control" required>
           <button class="btn btn-success btn-round mt-3" type="submit">Login<i class="fa fa-arrow-right ml-2"></i></button>
           <p class="text-muted my-2">No account yet? <a href="login.php?signup">create one here.</a></p>
         </form>
       </div>
    </center>
    <script>
       document.getElementById("login").focus();
    </script>
  <?php
  endif;
  ?>

  <?php
    if(isset($_GET['signup'])) :
  ?>
  <center>
    <title>IO | Sign Up</title>
     <div class="container bg-light rounded login-card p-3 mt-5">
       <form action="auth.php" method="POST">
         <img src="./img/io.png" width="70px" class="mt-3">
         <p class="text-primary">Sign up to create an account!</p>
         <hr>
         <p class="text-danger">
           <?php
            if(isset($_SESSION["serror"])){
             echo '<i class="fa fa-times"></i> '.@$_SESSION["serror"];
            }
           ?>
         </p>
         <input name="signup" value="true" class="d-none">
         <p class="text-info" id="keystate">Enter your device key to continue</p>
         <input id="login" name="device" style="text-transform: uppercase" onkeyup="checkKey(event);" type="text" placeholder="Device Key" class="form-control mb-2 text-primary bg-light text-center keyx" spellcheck="false" required>

         <input  name="email" type="email" placeholder="Email" class="form-control mb-2 si" required disabled>
         <input name="fname" type="text" placeholder="Full Name" class="form-control mb-2  si" required disabled>
         <input type="password" name="password" id="pass1" onkeyup="checkpassword();" placeholder="Password" class="form-control mb-2 si" required disabled>
         <input type="password" name="cpassword" id="pass2" onkeyup="checkpassword();" placeholder="Confirm Password" class="form-control si" required disabled>
         <button class="btn btn-info btn-round mt-3 si" id="sbtn" type="submit" disabled>Sign up<i class="fa fa-arrow-right ml-2"></i></button>
         <p class="text-muted my-2">Already have an account <a href="login.php?login">login here.</a></p>
       </form>
     </div>
  </center>
  <script>
     document.getElementById("login").focus();
  </script>
  <?php
  endif;
  ?>
</div>
</body>
<script src="./js/axios.js"></script>
<script src="./js/swal.js"></script>
<script>

   function checkKey(event){
     if(event.keyCode === 13){
     var key = document.getElementById("login").value;
       axios.get("io.php?chkKey="+key)
        .then(res=>{
          if(res.data.status == "OK"){
            document.getElementById("keystate").innerHTML = `<span class="text-success"><i class="fa fa-check"></i> ${res.data.message}</span>`;
            var zone  = document.getElementsByClassName("si");
            for(var i=0; i < zone.length; i++){
               zone[i].disabled = false;
            }
          }else{
            document.getElementById("keystate").innerHTML = `<span class="text-danger"><i class="fa fa-times"></i> ${res.data.message}</span>`;
            var zone  = document.getElementsByClassName("si");
            for(var i=0; i < zone.length; i++){
               zone[i].disabled = true;
            }
         }

        }).catch(err=>{
        console.log(err);
        })
    }
   }
 function checkpassword() {
    var pass = document.getElementById("pass1");
    var cpass = document.getElementById("pass2");
    var submitBtn = document.getElementById("sbtn");
    if(pass.value && cpass.value){
       if(pass.value !== cpass.value){
          document.getElementById('keystate').innerHTML = `<span class="text-danger">Passwords do not match!</span>`
          submitBtn.disabled = true;
       }else{
         document.getElementById('keystate').innerHTML = `<span class="text-success">Passwords matched!</span>`
         submitBtn.disabled = false;
       }
     }
 }


</script>
</html>

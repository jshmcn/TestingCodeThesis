<?php
header("Access-Control-Allow-Origin: *");
require "db.php";
session_start();

// Meralco kilo Watt hour constant
$eCharge = 9.9766;
$_SESSION['echarge'] = $eCharge;
$Months = array(
   'Delta',"01"=>'January',"02"=>'February',"03"=>'March',"04"=>'April',"05"=>'May',"06"=>'June',"07"=>'July',"08"=>'August',"09"=>'September',"10"=>'October',"11"=>'November',"12"=>'December'
);


//check if key is valid for sign up
if(isset($_GET["chkKey"])){
  $keytoCheck = $_GET["chkKey"];
  $kchk = $db->query("SELECT * FROM devices WHERE keyx='$keytoCheck' AND owner='none'")->fetch_all();
  if($kchk && sizeof($kchk) > 0){
    $res = array(
      "status" => "OK",
      "message" => "Key is valid"
    );
    echo json_encode($res);
  }else{
    $res = array(
      "status" => "ERROR",
      "message" => "Key is invalid"
    );
    echo json_encode($res);
  }
}

// io.php (io smart plug api)
if(isset($_POST["send"])){
  $key = @$_POST["key"];
  $voltage = @$_POST["voltage"];
  $current = @$_POST["current"];
  $power = (int)$current*(int)$voltage;
    // check if key is activated.
    $keyCheck = $db->query("SELECT * FROM devices WHERE keyx='$key'");
      if(sizeof($keyCheck) == 0 || $keyCheck == false){
         // register device
         $d1 = $db->query("INSERT INTO devices(device_name,keyx,owner) VALUES ('-','$key','none')");
         if($d1){
           echo "Registered a device";
         }else{
           echo $db->error;
         }
      }else{
        // check if owned?;
        // parse data already :)

       $saveReading = $db->query("UPDATE data_logs SET live_power_consumption='$power',total_power_consumption=total_power_consumption+$power,".$Months[date('m')]."=".$Months[date('m')]."+$power,uptime=uptime+2,total_electric_bill=".$Months[date('m')]." WHERE device_key='$key'");
       if($saveReading){
         echo $voltage;
       }else {
         echo  "Cannot be saved";
       }
      }
}

// change price Limit
if(isset($_GET['setLimit'])){
  $value = $_GET['value'];
  $device = $_GET['device'];
  $changeLimit = $db->query("UPDATE data_logs SET price_limit='$value' WHERE device_key='$device'");
}

// get dashboard stats
if(isset($_GET['getStats'])){
  // check if logged include 'file';
  if($_SESSION["logged_in"] == true){
    $deviceId = $_GET['device'];
    $d1 = $db->query("SELECT price_limit,".$Months[date('m')].",uptime,live_power_consumption FROM data_logs WHERE device_key='$deviceId'")->fetch_All();
    echo json_encode($d1);
  }else{
    echo "bad request";
  }
}



if(isset($_GET['listDevices'])){
  $user = $_SESSION['id'];
   if(isset($user)){
     $deviceList  = $db->query("SELECT device_name,keyx FROM devices WHERE owner='$user'")->fetch_All();
        echo json_encode($deviceList);
   }else{
     echo "Bad request";
   }
}

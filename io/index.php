<?php
  // silence is golden
  require 'db.php';
  session_start();
  if($_SESSION["logged_in"] == false){
    header("Location: login.php?login");
  }

  $Months = array(
  	 'Delta',"01"=>'January',"02"=>'February',"03"=>'March',"04"=>'April',"05"=>'May',"06"=>'June',"07"=>'July',"08"=>'August',"09"=>'September',"10"=>'October',"11"=>'November',"12"=>'December'
  );

  $SMonths = array(
  	'January','February','March','April','May','June','July','August', 'September', 'October', 'November', 'December'
  );

  $current_device_key = $_SESSION['cdevice'];

  $device = $_SESSION['cdevice'];
  $graph = $db->query("SELECT January,February,March,April,May,June,July,August,September,October,November,December FROM data_logs WHERE device_key='$device'")->fetch_All();



  $cMonth = $Months[date("m")];
  $cDate = date("m-d-Y");


 echo "
    <script>
        var cMonthx = '".$cMonth."';
        var limit = '"..530."';
        var email = '".$_SESSION['email']."';
        var username = '".$_SESSION['name']."';
        var cdevice = '".$_SESSION['cdevice']."';
        var cc = 9.9766;
        var pread = ".json_encode($graph[0]).".map(x=>parseInt(x));
        var priceRead = ".json_encode($graph[0]).".map(x=>parseInt(x));
    </script>
 ";

?>
<html>
<head>
  <link rel="icon" href="./img/icon.png">
  <meta name="theme-color" content="rgb(6,24, 115)">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <link rel="stylesheet" href="./css/bs4.css">
  <link rel="stylesheet" href="./css/blk.css">
  <link rel="stylesheet" href="./css/io.css">
  <link rel="stylesheet" href="./css/font-awesome.css">
  <title>IO</title>
</head>
<body>
  <center>
    <img src="./img/io.png" class="logo">
  </center>

  <div id="dashboard" class="page mx-2">

  	 <div class="row mt-3 mx-2">

  	 	 <div class="col-md-4 mb-4">
  	        <div class="valueCard p-3">
  	        	 <p class="text-muted valueHeader"><span class="btn btn-sm btn-info"><i class="fa fa-money"></i></span> Consumption Limit</p>
  	        	 <button class="live-tag badge badge-success">Live</button>
  	        	 <hr>
  	        	 <center>
  	        	 	<h1 class="limitVal">
                        <span class="peso">&#x20b1;</span> <span id="limitx">...</span>
                     </h1>
  	        	 	<hr>
  	        	 	  <center>
  	        	 	  	 <strong class="text-info"><i class="fa fa-bar-chart"></i> Data Readings</strong>
  	        	 	  </center>
  	        	 	<hr>
                    <ul class="p-0" style="list-style:none;text-align:left">
                        <li class="text-dark"><strong>Total Electric Bill:</strong> <strong class="text-info">&#x20b1; <span id="tbill">-</span></strong></li>
                        <li class="text-dark"><strong>Monthly Power consumption(KWph):</strong> <strong class="text-info" id="tpower">-</strong></li>
                        <li class="text-dark"><strong>Live Power Consumption (KWph):</strong> <strong class="text-info" id="lpower">-</strong></li>
                        <li class="text-dark"><strong>Date: <span class="text-info"><?php echo $cDate; ?><span></strong></li>
                        <li class="text-dark"><strong>Month: <span class="text-info"><?php echo $cMonth; ?></span></strong></li>


                    </ul>
                    <hr>
  	        	 	<button class="btn btn-success btn-round mt-2 mbx" onclick="changeValue()"><strong>Change Limit Value</strong></button>
  	        	 </center>
  	        </div>
  	 	 </div>

  	 	  <div class="col-md-6 mb-4">
  	        <div class="valueCard p-3">
  	        	 <p class="text-muted valueHeader"><span class="btn btn-sm btn-info"><i class="fa fa-bar-chart"></i></span> Monthly Power Consumption</p>
  	        	 <button class="live-tag badge badge-info hide-sm" onclick="window.location.reload()">
                    <i class="fa fa-refresh"></i> Click to Refresh</button>
  	        	 <hr>
                     <div id="container" style="width: 100%;" class="hide-sm">
                        <canvas id="canvas"></canvas>
                     </div>

  <div class="mobile-graph">
    <table class="table table-bordered table-striped">
    <thead>
      <tr class="text-dark">
        <th scope="col" class="text-info">MONTH</th>
        <th scope="col" class="text-info">POWER Consumption</th>
          <th scope="col" class="text-info">ELECTRIC BILL, (Philippine Pesos)</th>
      </tr>
    </thead>
    <tbody>
      <tr class="text-dark">
        <th scope="row">January</th>
        <th scope="row">10 Kwph</th>
        <th scope="row">200 pesos</th>
      </tr>
    </tbody>
  </table>
</div>

                     <center>
                        <button class="btn btn-primary btn-round mt-2 mby" onclick="viewProfile();"><i class="fa fa-user-circle mr-1"></i> Account</button>
                        <button class="btn btn-info btn-round mt-2 mby mx-2" onclick="viewDevices();"><i class="fa fa-bars mr-1"></i> Device details</button>
                        <button class="btn btn-info btn-round mt-2 mby" onclick="viewCharge();"><i class="fa fa-bolt mr-1"></i> Power Charge</button>
                     </center>
            </div>
  	 	 </div>

  	 	  <div class="col-md-2 mb-4">

  	        <div class="valueCard p-3">
  	        	 <p class="text-muted valueHeader"><span class="btn btn-sm btn-info"><i class="fa fa-bolt"></i></span> Sockets</p>
  	        	 <hr>
  	        	 <div class="socket-div bg-white pt-2">
  	   <button class="btn btn-nuetral socket py-3"><i class="fa fa-plug"></i> Socket 1</button>
       <button class="btn btn-nuetral socket py-3"><i class="fa fa-plug"></i> Socket 2</button>
  	   <button class="btn btn-nuetral socket py-3"><i class="fa fa-plug"></i> Socket 3</button>
  	   <button class="btn btn-nuetral socket py-3"><i class="fa fa-plug"></i> Socket 4</button>

       <hr>
  	        	 	 <button class="btn btn-warning p-3 mt-2"><i class="fa fa-microphone fa-3x"></i></button>
  	        	 </div>
  	        </div>
  	 	 </div>



  	 </div>

  <div>


</body>

<script src="./js/chart.js"></script>
<script src="./js/swal.js"></script>
<script src="./js/axios.js"></script>
<script src="./js/io.js"></script>

</html>

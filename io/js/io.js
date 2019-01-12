// io.js

var cLimit;
var priceCharge = 9.9766;

function changeValue(){
	swal({
      html: `
        <div class="">
           <h2 class="text-dark"><i class="fa fa-money text-info"></i> Change Price Limit</h2>
           <center>
               <input type="number" id="limitPrice" class="form-control text-muted text-center limit" placeholder="Price limit" value="${cLimit}">
               <p class="py-2 text-dark small">Set the price limit for your electric bill, you will be notified if 50% of that limit price is reached.</p>
           </center>
        </div>
     `,
     showCancelButton: true
    }).then((res)=>{
        if(res){
            var newValue = document.getElementById("limitPrice").value;
						if(newValue != 0){
            axios.get(`io.php?setLimit=change&value=${newValue}&device=${cdevice}`)
            .then(()=>{
              swal({
              	title: "Success",
              	text: "Changed Price Limit!",
              	showConfirmButton: true,
              	type: 'success',
              });
            });
					}else{
						swal("Sorry","you can not set price limit to zero","info");
					}
        }
    })
}

// update dashboard
setInterval(()=>{
  axios.get("io.php?getStats=get&device="+cdevice)
    .then((res)=>{
     document.getElementById("limitx").innerHTML = (parseFloat(res.data[0][0])).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');  // 12,345.67
		 document.getElementById("lpower").innerHTML = `${res.data[0][3]/1000} KW`;
		 document.getElementById("tpower").innerHTML = `${res.data[0][1]/1000} KW`;
		 document.getElementById("tbill").innerHTML = ((parseInt(res.data[0][1])/1000)/(res.data[0][2]/3600).toFixed(4)*cc).toFixed(4);
    // 50% of the price limit
		 var plimit = (((parseInt(res.data[0][1])/1000)/(res.data[0][2]/3600).toFixed(4)*9.9766).toFixed(4)/(parseFloat(res.data[0][0]))*100).toFixed(0);
		 if(plimit >= 50){
			 notification();
		 }

	})
},2000);





'use strict';

window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

(function(global) {
	var Months = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	];

	var COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	var Samples = global.Samples || (global.Samples = {});
	var Color = global.Color;

	Samples.utils = {
		// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
		srand: function(seed) {
			this._seed = seed;
		},

		rand: function(min, max) {
			var seed = this._seed;
			min = min === undefined ? 0 : min;
			max = max === undefined ? 1 : max;
			this._seed = (seed * 9301 + 49297) % 233280;
			return min + (this._seed / 233280) * (max - min);
		},

		numbers: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 1;
			var from = cfg.from || [];
			var count = cfg.count || 8;
			var decimals = cfg.decimals || 8;
			var continuity = cfg.continuity || 1;
			var dfactor = Math.pow(10, decimals) || 0;
			var data = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = (from[i] || 0) + this.rand(min, max);
				if (this.rand() <= continuity) {
					data.push(Math.round(dfactor * value) / dfactor);
				} else {
					data.push(null);
				}
			}

			return data;
		},

		labels: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 100;
			var count = cfg.count || 8;
			var step = (max - min) / count;
			var decimals = cfg.decimals || 8;
			var dfactor = Math.pow(10, decimals) || 0;
			var prefix = cfg.prefix || '';
			var values = [];
			var i;

			for (i = min; i < max; i += step) {
				values.push(prefix + Math.round(dfactor * i) / dfactor);
			}

			return values;
		},

		months: function(config) {
			var cfg = config || {};
			var count = cfg.count || 12;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = Months[Math.ceil(i) % 12];
				values.push(value.substring(0, section));
			}

			return values;
		},

		color: function(index) {
			return COLORS[index % COLORS.length];
		},

		transparentize: function(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}
	};

	// DEPRECATED
	window.randomScalingFactor = function() {
		return Math.round(Samples.utils.rand(-100, 100));
	};

	// INITIALIZATION

	Samples.utils.srand(Date.now());


}(this));


var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			datasets: [{
				label: 'Power consuption (KWph)',
				backgroundColor: color(window.chartColors.purple).alpha(0.5).rgbString(),
				borderColor: window.chartColors.purple,
				borderWidth: 1,
				data: pread
			},

			{
				label: 'Bill (Philippine Peso)',
				backgroundColor: color(window.chartColors.yellow).alpha(0.5).rgbString(),
				borderColor: window.chartColors.yellow,
				borderWidth: 1,
				data: priceRead
			},

			 ]

		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: ''
					}
				}
			});

		};


function viewProfile(){
	swal({
		html: `<h3 class="text-dark"><i class="fa fa-user-circle mr-1 text-info"></i>Profile</h3>
    <hr>
		<h3 class="text-info">${username}<br>
     <span class="small text-muted">${email}</span>
		</h3>
		<div class="account-toolbar py-1 px-0">
		  <!-- <button class="btn btn-small btn-round btn-sm" id="upass">Update Password</button> -->
			 <button class="btn btn-danger btn-small btn-round btn-sm" id="lg"><i class="fa fa-circle mr-1"></i> Log out</button>
		</div>
		`
	})

	document.getElementById("lg").addEventListener("click", function(){
	  logout();
	});

	document.getElementById("upass").addEventListener("click",()=>{
		changePassword();
	});

}

function changePassword(){
	swal({
	  title: 'Change Password',
		html: `<div class="mx-3">
		   <p class="py-1 text-muted small">Chang your password by completing the form below</p>
		   <input type="text" id="opass" class="form-control text-dark" placeholder="Old password">
			 <br>
			 <input type="text" id="npass" class="form-control text-dark" placeholder="New password">
   </div>
		`
	})
}

function logout(){
	swal("Logging out...");swal.showLoading();
	setTimeout(()=>{
		location.href="auth.php?logout";
	},1500);
}

function viewDevices() {

	axios.get("io.php?listDevices")
	.then(res=>{
			var dlist = "";
			res.data.forEach(device=>{
				dlist += `
				<li class="text-dark">
				 ${device[0]} | <small class="text-info">${device[1]}</small>
				 <button id="editName" class="btn btn-sm float-right d-none"><i class="fa fa-edit mr-1"></i> Edit Name</button>
				<li>
				`
			});
			swal({
					html:`<h3 class="text-dark"><i class="fa fa-bars mr-1 text-info"></i> Device details</h3>
					 <ul style="list-style:none" class="p-0">
					  ${dlist}
					 </ul>
					`,
				})
	})
}


function editdeviceName(){
	swal({
		title: 'Edit device name:',
	  html: `
      <div class="mx-5">
       <input class="form-control text-dark" style="font-weight:500!important" placeholder="Device Name" id="dname">
       <p class="text-muted"> You can change the device name by clic</p>
			</div>
		`
	});
}

function viewCharge() {
	swal({
		title: 'Power charge',
		type: 'info',
		text: `${priceCharge} Pesos per KiloWatt hour`
	})
}

function notification(message){
	if(window.Notification && Notification.permission !== "denied") {
	Notification.requestPermission(function(status) {  // status is "granted", if accepted by user
		var n = new Notification('Electric Bill reminder!', {
			body: 'You have surpassed the price limit of 200 pesos!',
			icon: './img/icon.png'
		});
	});
}
}

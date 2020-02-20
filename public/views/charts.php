<html>
<head>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" media="screen"/>
</head>
<body>
	<!-- <div style="height: 50%; width:80%;float:left;">
		<canvas id="myChart">
		</canvas>
	</div> -->
	<!-- <div style="height: 40%; width:60%;float:left;">
		<canvas id="dayChart">
		</canvas>
	</div> -->
	<!-- <div style="height: 40%; width:60%;">
		<canvas id="hourChart">
		</canvas>
	</div> -->
	<!-- <div style="height: 40%; width:60%;">
		<canvas id="itemPerDateChart">
		</canvas>
	</div>-->
	<div style="height: 40%; width:60%;">
		<canvas id="brandPerDayChart">
		</canvas>
	</div>
	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
	<script>
		Chart.plugins.unregister(ChartDataLabels);
		var menu = [
			"Nasi Orak Arik",
			"Nasi Kornet",
			"Nasi Sarden",
			"Nasi Magelangan",
			"Nasi Ayam Asam Manis",
			"Nasi Ayam Sambal Bawang",
			"Boston Classic",
			"Boston Classic Dark",
			"Fried or Steam Mantao",
			"Siomay Chicken",
			"Siomay Shrimp",
			"Siomay Kepiting",
			"Hakau Chicken",
			"Hakau Shrimp",
			"Siomay Kepiting",
			"Ceker",
			"Steamed Pao Cilembu",
			"Pao Telur Asin",
			"Fried Seafood Tofu",
			"Enoki Cabe Garam",
			"Lumpia Udang Kulit Tahu",
			"Pangsit Udang",
			"Fried Dimsum Chicken Shrimp"
		];

		var date = [
			"2020-02-03",
			"2020-02-04",
			"2020-02-05",
			"2020-02-06",
			"2020-02-07",
			"2020-02-08",
			"2020-02-09",
			"2020-02-10",
			"2020-02-11",
			"2020-02-12",
			"2020-02-13",
			"2020-02-14",
			"2020-02-15",
		];

		var hour = [
			"08.00 - 09.00",
			"09.00 - 10.00",
			"10.00 - 11.00",
			"11.00 - 12.00",
			"12.00 - 13.00",
			"13.00 - 14.00",
			"14.00 - 15.00",
			"15.00 - 16.00",
			"16.00 - 17.00",
			"17.00 - 18.00",
			"18.00 - 19.00",
			"19.00 - 20.00",
			"20.00 - 21.00",
			"21.00 - 22.00",
		];

		var orderPerDate = [
			8,17,13,10,4,4,6,10,15,11,9,1,2
		];

		var orderPerHour = [
			0,2,10,12,5,9,8,12,11,11,19,10,1,0
		];
		var itemPerDate = [
			20,47,29,138,141,12,35,27,11,0,0
		];


		var w1_offline = [
			4,
			13,
			0,
			12,
			74,
			83,
			46,
			125,
			0,
			2,
			2,
			1,
			1,
			5,
			1,
			0,
			0,
			1,
			0,
			0,
			5,
			0,
			0
		];
		var w2_offline = [
			5,
			8,
			6,
			11,
			25,
			65,
			47,
			46,
			1,
			2,
			0,
			0,
			0,
			1,
			0,
			1,
			0,
			1,
			0,
			1,
			1,
			0,
			1
		];
		var listColor = [];
		listColor['red'] = 'rgba(255, 99, 132, 0.2)';
		listColor['blue'] = 'rgba(54, 162, 235, 0.2)';
		var arrColor = [];

		for (var key in listColor) {
			var array = []
			for (var i = 0 ; i < menu.length ; i++) {
				array.push(listColor[key])
			}
			arrColor[key] = array
		}
		var ctx = $("#myChart");
		var myChart = new Chart(ctx, {
						plugins: [ChartDataLabels],
					    type: 'horizontalBar',
					    data: {
					        labels: menu,
					        datasets: [{
					            label: '1st Week of February (1 - 7 February)',
					            data: w1_offline,
					            backgroundColor: listColor['red'],
					            borderColor: listColor['red'],
					            borderWidth: 1
					        }, {
					        	label: '2nd Week of February (8 - 14 February)',
					        	data: w2_offline,
					        	backgroundColor: listColor['blue'],
					        	borderColor: listColor['blue'],
					        	borderWidth: 1
					        }]
					    },
					    options: {
					        scales: {
					            yAxes: [{
					                ticks: {
					                    beginAtZero: true
					                }
					            }]
					        }
					    }
					});

		var dayCtx = $("#dayChart");
		var myLineChart = new Chart(dayCtx, {
							    type: 'line',
							    data: {
							    	labels: date,
							    	datasets: [{
							    		label: 'Transaction By Day',
							    		data: orderPerDate,
							    		backgroundColor: listColor['blue'],
							    		borderColor: listColor['blue']
							    	}]
							    },
							    options: {}
							});



		var hourCtx = $("#hourChart");
		var myLineChart = new Chart(hourCtx, {
							    type: 'line',
							    data: {
							    	labels: hour,
							    	datasets: [{
							    		label: 'Transaction By Hour',
							    		data: orderPerHour,
							    		backgroundColor: listColor['blue'],
							    		borderColor: listColor['blue']
							    	}]
							    },
							    options: {}
							});

		var itemPerDateCtx = $("#itemPerDateChart");
		var myLineChart = new Chart(itemPerDateCtx, {
							    type: 'line',
							    data: {
							    	labels: date,
							    	datasets: [{
							    		label: 'Item Per Date',
							    		data: itemPerDate,
							    		borderColor: listColor['blue'],
							    		fill: false
							    	}]
							    },
							    options: {}
							});


		var brandPerDayCtx = $("#brandPerDayCtx");
		var myLineChart = new Chart(brandPerDayCtx, {
								type: 'line',
							    data: {
							    	labels: date,
							    	datasets: [{
							    		label: 'Item Per Date',
							    		data: itemPerDate,
							    		borderColor: listColor['blue'],
							    		fill: false
							    	}]
							    },
							    options: {}
							});
	</script>
</body>	
</html>
<?php
// Database connection parameters
$db_host = 'localhost';  // MySQL server host
$db_user = 'root';   // MySQL username
$db_pass = '';   // MySQL password
$db_name = 'meteo';   // Database name

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name); 

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Your existing code for $tabMois and $tabJours arrays remains unchanged

// Example of a query using MySQLi
// Note: Adjust the query according to your specific needs
$result = $conn->query("SELECT * FROM meteo WHERE dt = '20240512'");

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: ". $row["id"]. " - Name: ". $row["name"]. " ". $row["email"]. "<br>";
    }
} else {
    echo "0 results";
}
// $conn->close();

	$tabMois=array(1=>"Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
	$tabJours=array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
	
?>
<html>
<head>
	<title>Meteo</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
	<style>
	@import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
	body {background:linear-gradient(#6DCFF6,#A155A0);background-attachment: fixed;font-family: 'Open Sans', sans-serif;}
	.info {width:350px;background-color:rgba(255, 255, 2555, .5);color:white;padding:10px;border-radius:20px;margin-top: 10px;margin-bottom: 10px;font-size: 1.2em;height:168px;}
	.infoChart {width:350px;background-color:rgba(255, 255, 2555, .5);color:white;padding:10px;border-radius:20px;margin-top: 10px;margin-bottom: 10px;font-size: 1.4em;height:207px;}
	.temp {margin-left:14px;margin-top:10px;width: 100px;float: left;height: 103px;padding-top:10px;border: 1px solid white;border-radius:10px;font-size:1.9em;background: url("images/temp.png");background-size: 40px;background-repeat: no-repeat;background-position: bottom left;background-color: #6DCFF6;}
	.pression {margin-top:10px;width: 100px;float: left;height: 103px;padding-top:10px;border: 1px solid white;margin-left:8px;border-radius:10px;font-size:1.9em;background: url("images/pression.png");background-size: 40px;background-repeat: no-repeat;background-position: bottom left;background-color: #6DCFF6;}
	.humidite {margin-top:10px;width: 100px;float: left;height: 103px;padding-top:10px;border: 1px solid white;margin-left:8px;border-radius:10px;font-size:1.9em;background: url("images/humidite.png");background-size: 40px;background-repeat: no-repeat;background-position: bottom left;background-color: #6DCFF6;}
	.tempmini {margin-left:14px;margin-top:10px;width: 100px;float: left;height: 103px;padding-top:10px;border: 1px solid white;border-radius:10px;font-size:1.9em;background: url("images/temp.png");background-size: 40px;background-repeat: no-repeat;background-position: bottom left;background-color: #6D6DF6;}
	.tempmaxi {margin-top:10px;widwth: 100px;float: left;height: 103px;padding-top:10px;border: 1px solid white;margin-left:8px;border-radius:10px;font-size:1.9em;background: url("images/temp.png");background-size: 40px;background-repeat: no-repeat;background-position: bottom left;background-color: #F66D6D;}
	.tempmoy {margin-top:10px;width: 100px;float: left;height: 103px;padding-top:10px;border: 1px solid white;margin-left:8px;border-radius:10px;font-size:1.9em;background: url("images/temp.png");background-size: 40px;background-repeat: no-repeat;background-position: bottom left;background-color: #F6CF6D;}
	</style>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
		google.charts.load('current', {packages: ['corechart', 'line']});
		google.charts.setOnLoadCallback(drawTemp);
		google.charts.setOnLoadCallback(drawTemp14);
		google.charts.setOnLoadCallback(drawHumidite);
		google.charts.setOnLoadCallback(drawPression);
		google.charts.setOnLoadCallback(drawRosee);
		google.charts.setOnLoadCallback(drawPluvio);
		google.charts.setOnLoadCallback(drawPluvioJJ);
		
		function drawTemp() {

		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'DT');
		  data.addColumn('number', 'Temp');

		  data.addRows([<?php
		  for ($i=71;$i>=0;$i--) {
			$dt=date("YmdHi",mktime(date("H")-$i,59,0,date("m"),date("d"),date("Y")));
			$r=$conn->query("SELECT * FROM meteo WHERE (dt || hh) <= '$dt' ORDER BY dt DESC ,hh DESC limit 1");
			$d = $r->fetchArray();
			if (!isset($d['temp'])) {$d['temp']=0;}
			if ($i!=71) {echo ',';}
			$ladate=substr($dt,0,8).' '.substr($dt,8,2).':00';
			echo "['$ladate',".$d['temp']."]";
		  }
		  ?>]);

		  var options = {
			legend: { position: 'none' },
			backgroundColor: 'none',
			width:350,
			chartArea: {left: 20,top: 10,width: 300,height: 150},
			gridlines: {color: "#FFFFFF"},
			baselineColor: '#FFFFFF',
			hAxis: {textPosition: 'none'},
			vAxis: {textStyle:{color: '#FFFFFF',fontSize: 14}},
			series: { 0: { color: '#A155A0' }}			
		  };

		  var chart = new google.visualization.LineChart(document.getElementById('chart_temp'));

		  chart.draw(data, options);
		}
		function drawTemp14() {

		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'DT');
		  data.addColumn('number', 'MM');

		  data.addRows([<?php
		  for ($i=30;$i>=0;$i--) {
			$tick=mktime(0,0,0,date("m"),date("d")-$i,date("Y"));
			$dt=date("Ymd",$tick);
			$r=$conn->query("select dt, hh, temp from meteo where (hh like '14%' or hh like '15%') and dt='$dt'");
			$d = $r->fetchArray();
			if (!isset($d['temp'])) {$d['temp']=0;}
			if ($i!=30) {echo ',';}
			$ladate=substr($dt,0,8);
			echo "['$ladate',".$d['temp']."]";
		  }
		  ?>]);

		  var options = {
			legend: { position: 'none' },
			backgroundColor: 'none',
			width:350,
			bar: {groupWidth: "100%"},
			chartArea: {left: 40,top: 10,width: 300,height: 150},
			gridlines: {color: "#FFFFFF"},
			baselineColor: '#FFFFFF',
			hAxis: {textPosition: 'none'},
			vAxis: {textStyle:{color: '#FFFFFF',fontSize: 14}},
			series: { 0: { color: '#6D6DF6' }}			
		  };

		  var chart = new google.visualization.ColumnChart(document.getElementById('chart_temp14'));

		  chart.draw(data, options);
		}
		function drawPression() {

		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'DT');
		  data.addColumn('number', 'Pression');

		  data.addRows([<?php
		  for ($i=71;$i>=0;$i--) {
			$dt=date("YmdHi",mktime(date("H")-$i,59,0,date("m"),date("d"),date("Y")));
			$r=$conn->query("SELECT * FROM meteo WHERE (dt || hh) < '$dt' ORDER BY dt DESC ,hh DESC limit 1");
			$d = $r->fetchArray();
			if (!isset($d['pression'])) {$d['pression']=0;}
			if ($i!=71) {echo ',';}
			$ladate=substr($dt,0,8).' '.substr($dt,8,2).':'.substr($dt,10,2);
			echo "['$ladate',".$d['pression']."]";
		  }
		  ?>]);

		  var options = {
			legend: { position: 'none' },
			backgroundColor: 'none',
			width:350,
			chartArea: {left: 40,top: 10,width: 300,height: 150},
			gridlines: {color: "#FFFFFF"},
			baselineColor: '#FFFFFF',
			hAxis: {textPosition: 'none'},
			vAxis: {textStyle:{color: '#FFFFFF',fontSize: 14}},
			series: { 0: { color: '#A155A0' }}			
		  };

		  var chart = new google.visualization.LineChart(document.getElementById('chart_pression'));

		  chart.draw(data, options);
		}
		function drawHumidite() {

		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'DT');
		  data.addColumn('number', 'Humidite');

		  data.addRows([<?php
		  for ($i=71;$i>=0;$i--) {
			$dt=date("YmdHi",mktime(date("H")-$i,59,0,date("m"),date("d"),date("Y")));
			$r=$conn->query("SELECT * FROM meteo WHERE (dt || hh) < '$dt' ORDER BY dt DESC ,hh DESC limit 1");
			$d = $r->fetchArray();
			if (!isset($d['humidite'])) {$d['humidite']=0;}
			if ($i!=71) {echo ',';}
			$ladate=substr($dt,0,8).' '.substr($dt,8,2).':'.substr($dt,10,2);
			echo "['$ladate',".$d['humidite']."]";
		  }
		  ?>]);

		  var options = {
			legend: { position: 'none' },
			backgroundColor: 'none',
			width:350,
			chartArea: {left: 30,top: 10,width: 300,height: 150},
			gridlines: {color: "#FFFFFF"},
			baselineColor: '#FFFFFF',
			hAxis: {textPosition: 'none'},
			vAxis: {textStyle:{color: '#FFFFFF',fontSize: 14}},
			series: { 0: { color: '#A155A0' }}			
		  };

		  var chart = new google.visualization.LineChart(document.getElementById('chart_humidite'));

		  chart.draw(data, options);
		}
		function drawRosee() {

		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'DT');
		  data.addColumn('number', 'Rosee');

		  data.addRows([<?php
		  for ($i=71;$i>=0;$i--) {
			$dt=date("YmdHi",mktime(date("H")-$i,59,0,date("m"),date("d"),date("Y")));
			$r=$conn->query("SELECT * FROM meteo WHERE (dt || hh) < '$dt' ORDER BY dt DESC ,hh DESC limit 1");
			$d = $r->fetchArray();
			if (!isset($d['rosee'])) {$d['rosee']=0;}
			if ($i!=71) {echo ',';}
			$ladate=substr($dt,0,8).' '.substr($dt,8,2).':'.substr($dt,10,2);
			echo "['$ladate',".$d['rosee']."]";
		  }
		  ?>]);

		  var options = {
			legend: { position: 'none' },
			backgroundColor: 'none',
			width:350,
			chartArea: {left: 20,top: 10,width: 300,height: 150},
			gridlines: {color: "#FFFFFF"},
			baselineColor: '#FFFFFF',
			hAxis: {textPosition: 'none'},
			vAxis: {textStyle:{color: '#FFFFFF',fontSize: 14}},
			series: { 0: { color: '#A155A0' }}			
		  };

		  var chart = new google.visualization.LineChart(document.getElementById('chart_rosee'));

		  chart.draw(data, options);
		}
		function drawPluvio() {

		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'DT');
		  data.addColumn('number', 'MM');

		  data.addRows([<?php
		  for ($i=71;$i>=0;$i--) {
			$tick=mktime(date("H")-$i,0,0,date("m"),date("d"),date("Y"));
			$dt=date("Ymd",$tick);
			$hh=date("H",$tick);
			$r=$conn->query("SELECT * FROM pluviometre WHERE dt='$dt' and hh='$hh'");
			$d = $r->fetchArray();
			if (!isset($d['qte'])) {$d['qte']=0;}
			$d['qte']=round($d['qte']*0.2794,1);
			if ($i!=71) {echo ',';}
			$ladate=substr($dt,0,8).' '.$hh.':00';
			echo "['$ladate',".$d['qte']."]";
		  }
		  ?>]);

		  var options = {
			legend: { position: 'none' },
			backgroundColor: 'none',
			width:350,
			bar: {groupWidth: "100%"},
			chartArea: {left: 40,top: 10,width: 300,height: 150},
			gridlines: {color: "#FFFFFF"},
			baselineColor: '#FFFFFF',
			hAxis: {textPosition: 'none'},
			vAxis: {textStyle:{color: '#FFFFFF',fontSize: 14}},
			series: { 0: { color: '#6D6DF6' }}			
		  };

		  var chart = new google.visualization.ColumnChart(document.getElementById('chart_pluvio'));

		  chart.draw(data, options);
		}
		function drawPluvioJJ() {

		  var data = new google.visualization.DataTable();
		  data.addColumn('string', 'DT');
		  data.addColumn('number', 'MM');

		  data.addRows([<?php
		  for ($i=30;$i>=0;$i--) {
			$tick=mktime(0,0,0,date("m"),date("d")-$i,date("Y"));
			$dt=date("Ymd",$tick);
			$r=$conn->query("SELECT sum(qte) as qte FROM pluviometre WHERE dt='$dt' group by dt");
			$d = $r->fetchArray();
			if (!isset($d['qte'])) {$d['qte']=0;}
			$d['qte']=round($d['qte']*0.2794,1);
			if ($i!=30) {echo ',';}
			$ladate=substr($dt,0,8);
			echo "['$ladate',".$d['qte']."]";
		  }
		  ?>]);

		  var options = {
			legend: { position: 'none' },
			backgroundColor: 'none',
			width:350,
			bar: {groupWidth: "100%"},
			chartArea: {left: 40,top: 10,width: 300,height: 150},
			gridlines: {color: "#FFFFFF"},
			baselineColor: '#FFFFFF',
			hAxis: {textPosition: 'none'},
			vAxis: {textStyle:{color: '#FFFFFF',fontSize: 14}},
			series: { 0: { color: '#6D6DF6' }}			
		  };

		  var chart = new google.visualization.ColumnChart(document.getElementById('chart_pluviojj'));

		  chart.draw(data, options);
		}
	</script>
</head>
<body>

<iframe src="https://www.meteoblue.com/en/weather/widget/three/le-poin%c3%a7onnet_france_3002626?geoloc=fixed&nocurrent=0&noforecast=0&days=4&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&layout=dark"  frameborder="0" scrolling="NO" allowtransparency="true" sandbox="allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox" style="width: 350px; height: 510px"></iframe>
<?php
	$r=$conn->query('SELECT * FROM meteo order by dt DESC, hh DESC limit 1');
	$d = $r->fetchArray();
	$ts=mktime(substr($d['hh'],0,2),substr($d['hh'],2,2),0,substr($d['dt'],4,2),substr($d['dt'],6,2),substr($d['dt'],0,4));
	$ladate=$tabJours[date("w",$ts)].' '.(substr($d['dt'],6,2)+0).' '.$tabMois[substr($d['dt'],4,2)+0].' à '.date("H:i",$ts);
?>

<div class="info">
	<?php echo $ladate;?><br>
	<div class="temp"><?php echo strtr($d['temp'],array("."=>","));?><br><span style="font-size:0.6em;position: absolute;">&nbsp; °C</span></div>
	<div class="pression"><?php echo $d['pression'];?><br><span style="font-size:0.6em;position: absolute;">hPa</span></div>
	<div class="humidite"><?php echo $d['humidite'];?><br><span style="font-size:0.6em;position: absolute;">&nbsp; %</span></div>
</div>
<div class="info">
	<?php echo date("Y");?><br>
	<?php $r=$conn->query("SELECT MIN(temp) as mini, MAX(temp) as maxi, round(AVG(temp),1) as moy FROM meteo WHERE dt LIKE '".date("Y")."%'");$d = $r->fetchArray();?>
	<div class="tempmini"><?php echo strtr($d['mini'],array("."=>","));?><br><span style="font-size:0.6em;position: absolute;">&nbsp; °C</span></div>
	<div class="tempmaxi"><?php echo strtr($d['maxi'],array("."=>","));?><br><span style="font-size:0.6em;position: absolute;">&nbsp; °C</span></div>
	<div class="tempmoy"><?php echo strtr($d['moy'],array("."=>","));?><br><span style="font-size:0.6em;position: absolute;">&nbsp; °C</span></div>
</div>
<div class="infoChart">
	Températures<br>
	<div id="chart_temp"></div>
</div>
<div class="infoChart">
	Températures à 14h<br>
	<div id="chart_temp14"></div>
</div>
<div class="infoChart">
	Pression<br>
	<div id="chart_pression"></div>
</div>
<div class="infoChart">
	Humidité<br>
	<div id="chart_humidite"></div>
</div>
<div class="infoChart">
	Point de rosée<br>
	<div id="chart_rosee"></div>
</div>
<div class="infoChart">
	Pluviométrie/heure<br>
	<div id="chart_pluvio"></div>
</div>
<div class="infoChart">
	Pluviométrie/jour<br>
	<div id="chart_pluviojj"></div>
</div>

</body>
</html>
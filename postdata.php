<?php
	$db= new SQLite3("bdd.db");
	if (!isset($_POST['temp'])) {die('Paramètres manquants !');}
	$db->exec("insert into meteo (dt,hh,temp,pression,humidite,rosee) 
			   values ('".date("Ymd")."','".date("Hi")."',".round($_POST['temp'],1).",".floor($_POST['pression']).",".round($_POST['humidite'],1).",".round($_POST['rosee'],1).")");
?>

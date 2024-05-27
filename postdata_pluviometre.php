<?php
	$db= new PDO("bdd.db");
	if (!isset($_POST['nbgodet'])) {die('Parametres manquants !');}
	
	$r=$db->query("select * from pluviometre where dt='".date("Ymd")."' and hh='".date("H")."'");
	$d = $r->fetchArray();
	if (!isset($d['dt'])) {
		$db->exec("insert into pluviometre (dt,hh,qte) 
			   values ('".date("Ymd")."','".date("H")."',".$_POST['nbgodet'].")");
	} else {
		$db->exec("update pluviometre set qte=qte+".$_POST['nbgodet']." where dt='".date("Ymd")."' and hh='".date("H")."'");
	}
	// mise à jour des données trop faibles
	$db->exec("update pluviometre set qte=0 where qte=1 and (dt || hh) <'".date("YmdH")."'");
?>
<?php
// Database connection parameters
$db_host = 'localhost';  // MySQL server host
$db_user = 'root';       // MySQL username
$db_pass = '';           // MySQL password
$db_name = 'meteo';      // Database name

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// SQL to create the pluviometre table
$sql = "
CREATE TABLE IF NOT EXISTS pluviometre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt DATE NOT NULL,
    hh TIME NOT NULL,
    qte DECIMAL(5,2) NOT NULL DEFAULT 0
)";

// Execute the SQL statement
if ($conn->query($sql) === TRUE) {
    echo "Table pluviometre created successfully";
} else {
    echo "Error creating table: ". $conn->error;
}

// Close the database connection
$conn->close();
?>
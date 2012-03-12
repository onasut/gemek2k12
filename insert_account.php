<?php
$title = "Lägg till kontokod";
$bodyId = "insert_account";
include("header.php");
require_once('config.php'); 
?>

<div id="wrap">
<article>  

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
<fieldset>
<legend>Lägg till kontokod</legend>
<p>Fyll i fälten för att lägga till ny kontokod i kontoplanen.</p>
<table class="input"> 

<tr> 
<td><label for="categorycode">Kontokod:</label></td> 
<td><input id="categorycode" type="text" name="categorycode" value="<?php 
if(isset($_GET['categorycode']))echo $_GET['categorycode'];?>" /></td> 
</tr>

<tr> 
<td><label for="categoryname">Kontonamn:</label></td> 
<td><input id="categoryname" type="text" name="categoryname" value="<?php 
if(isset($_GET['categoryname']))echo $_GET['categoryname'];?>" /></td> 
</tr> 

<tr>
<td><label for="options">Löpande:</label></td>
<td><input type="checkbox" name="options[]" value="running" checked="checked" /></td> 
</tr> 

<tr>
<td><label for="options">Gemensam:</label></td>
<td><input type="checkbox" name="options[]" value="mutual" checked="checked" /></td> 
</tr>

<tr> 
<td></td> 
<td style="text-align: right"><button type="submit" name="submit">Spara</button></td> 
</tr> 

</table> 
</fieldset>
</form>

</article>
</div> <!-- wrapper -->


<?php 

function curPageURL() {
$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
return $url;
}

switch (curPageURL()) {
	case "http://192.168.0.196/gemek2k12/insert_account.php": 
	case "http://127.0.0.1/gemek2k12/insert_account.php":
	case "http://localhost/gemek2k12/insert_account.php":	
		goto footer;
}

function replace_swe_entities($string) {
$pattern = array('å','ä','ö');
$replace = array('&aring;','&auml;','&ouml;');
return str_replace($pattern,$replace,$string);
}

// Connect to the database server 
//require_once('config.php'); 
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 

if (mysqli_connect_error()) { 
	echo '<option value="">Kunde inte ansluta: ' . mysqli_connect_error(). '</option>'; 
	exit(); 
} 
// Connected successfully 

// Sanitize data
if (isset($_GET['categorycode'])) {
$categorycode = $mysqli->real_escape_string($_GET['categorycode']); 
}
if(empty($categorycode)){
	echo "Kan inte spara. Kategorikod saknas.";
	goto footer;
}

if (isset($_GET['categoryname'])) {
$categoryname = $mysqli->real_escape_string($_GET['categoryname']); 
}
if(empty($categoryname)){
	echo "Kan inte spara. Kategorinamn saknas.";
	goto footer;
}
$categoryname = replace_swe_entities($categoryname);


$running = 0;
$mutual = 0;
if (isset($_GET['options'])){
	$options = $_GET['options'];

	foreach ($options as $option){
	if ($option == "running") {$running =1;}
	if ($option == "mutual") {$mutual =1;}
	}
	
	// $sizeofoptions = sizeof($options);
	// for ($i=0; $i<$sizeofoptions; $i++){
		// if ($options[$i] == "running") {$running =1;}
		// if ($options[$i] == "mutual") {$mutual =1;}
	// }
}

// Build query
$query = "insert into accounts values ('" . $categorycode . "','" . $categoryname . "'," . $mutual . "," . $running. ")";
$result = $mysqli->query($query)
	or die("Could not query database, query =<br/><pre>{$query}</pre><br/>{$mysqli->error}"); 

echo "Sparat";

//$result->close(); 
$mysqli->close(); 
?> 

<?php 
footer:
include("footer.php"); ?>
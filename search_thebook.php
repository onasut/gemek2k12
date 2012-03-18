<?php
$title = "Sökning";
$bodyId = "search";
include("header.php");
require_once('config.php');  
?>

<div id="wrap">
<article>  

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get"> 

<fieldset> 
<legend>Sök i bokföringen!</legend> 

<p>Ange datum att söka efter i boken.</p> 

<table> 
<tr> 
<td><label for="search">Datum:</label></td> 
<td><input id="search" type="text" name="search" value="<?php 
	if(isset($_GET['search'])){
		echo $_GET['search'];
	}else{
		echo date("Y-m");
}?>" /></td> 
</tr> 
<tr> 
<td></td> 
<td style="text-align: right"><button type="submit" name="submit">Sök</button></td> 
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
	case "http://192.168.0.196/gemek2k12/search_thebook.php": 
	case "http://127.0.0.1/gemek2k12/search_thebook.php":
	case "http://localhost/gemek2k12/search_thebook.php":	
		goto footer;
}


$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
if (mysqli_connect_error()) { 
   echo "Connect failed: ".mysqli_connect_error()."<br>"; 
   goto footer; 
} 


// Sanitize data 
if (isset($_GET['search'])) {
$search = $mysqli->real_escape_string($_GET['search']); 
}

// Prepare query 
if(empty($search)) { 
    echo "Ingen söksträng har angetts."; 
    goto footer; 
} 

$query = "SELECT * FROM keepbook WHERE date LIKE '{$search}%';"; 


// Execute query 
$res = $mysqli->query($query)  
                    or die("Could not query database, query =<br/><pre>{$query}</pre><br/>{$mysqli->error}"); 

echo "<p>Query={$query}</p><p>Number of rows in resultset: " . $res->num_rows . "</p>"; 


// Show the results of the query 

$i = 1; 
while($row = $res->fetch_object()) { 
        echo $i . ": " . $row->amount . " - " . $row->person_id . " - " . $row->account_id . " - " . $row->timestamp . "<br/>"; 
        $i++; 
    } 

$res->close(); 


// Close the connection to the database 

$mysqli->close(); 


//goto footer; 

?> 

<?php 
footer:
include("footer.php"); ?>
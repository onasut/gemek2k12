<?php
$title = "Sökning";
$bodyId = "search";
include("header.php");
require_once('config.php'); 
?>

<!-- Here is the actual content of the page-->
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
if(isset($_GET['search']))echo $_GET['search']?>" /></td> 
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
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
if (mysqli_connect_error()) { 
   echo "Connect failed: ".mysqli_connect_error()."<br>"; 
   exit(); 
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
<?php
$title = "kontoplan";
$bodyId = "account_plan";
include("header.php");
require_once('config.php');
?>

<div id="wrap">
<article>  
  
<?php
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
if (mysqli_connect_error()) { 
	echo '<option value="">Connect failed: ' . mysqli_connect_error(). '</option>'; 
	exit(); 
} 

// Query
$query = "SELECT * FROM accounts order by id;";  
$result = $mysqli->query($query)
	or die("Could not query database, query =<br/><pre>{$query}</pre><br/>{$mysqli->error}");

echo '<table class="output">';
echo '<tr><th width=120px>Kontokod</th>
	<th width=40%>Kontonamn</th>
	<th width=100px>Typ1</th>
	<th width=100px>Typ2</th></tr>';
$i = 1; 
while($row = $result->fetch_object()) { 
		if($row->mutual){$mutual='Gemensam';}else{$mutual='Enskild';}
		if($row->running){$running='Löpande';}else{$running='Engångs';}
        echo '<tr><td align=center>' . $row->id 
		. '</td><td>' . $row->denom 
		. '</td><td>' . $mutual 
		. '</td><td>' . $running
		. '</td></tr>'; 
        $i++; 
    } 
echo '</table>';

$result->close(); 
$mysqli->close(); 
?>  
  
</article>
</div> <!-- wrapper -->

<?php 
footer:
include("footer.php"); ?>
<?php
$title = "Bokföring";
$bodyId = "insert_kb_log"; 
include("header.php");
require_once('config.php');
?>

<div id="wrap">
<article>  

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
<fieldset>
<legend>Bokföring</legend>
<p>Fyll i fälten och klicka Spara för att göra ett införande i boken.</p>

<table class="input"> 
<tr> 
<td><label for="account">Bokföringskonto:</label></td> 
<td>
<select name="account">
<?php 
// Connect to the database server 
//require_once('config.php'); 
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
if (mysqli_connect_error()) { 
	echo '<option value="">Connect failed: ' . mysqli_connect_error(). '</option>'; 
	exit(); 
} 

// Query
$query = "SELECT * FROM accounts order by id;";  
$result = $mysqli->query($query)
	or die("Could not query database, query =<br/><pre>{$query}</pre><br/>{$mysqli->error}");

// Fill dropdown
$i = 1; 
echo '<option value="">Välj kontokod</options>';
while($row = $result->fetch_object()) { 
        echo '<option value="' . $row->id . '">' . $row->denom . '</option>'; 
        $i++; 
    } 

$result->close(); 
$mysqli->close(); 
?>
</select>
</td> 
</tr>
<tr> 
<td><label for="person">Person:</label></td> 
<td><select name="person">
<?php 
//require_once('config.php'); 
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
if (mysqli_connect_error()) { 
	echo '<option value="">Connect failed: ' . mysqli_connect_error(). '</option>'; 
	exit(); 
} 

// Query
$query = "SELECT * FROM persons order by name;";  
$result = $mysqli->query($query)
	or die("Could not query database, query =<br/><pre>{$query}</pre><br/>{$mysqli->error}");

// Determine which user is visiting the page
switch ($_SERVER['REMOTE_ADDR']) {
	case JohansIP:
		$visitor = 'J';
		break;
	case HelenesIP:
		$visitor = 'H';
		break;
	case localhost:
		$visitor = 'J';
		break;
	default:
		$visitor = '';
}

// Fill dropdown
$i = 1; 
echo '<option value="">Välj person</option>';
while($row = $result->fetch_object()) { 
		if ($row->id == $visitor) {$selected = ' selected="selected"';} else {$selected = '';}
        echo '<option value="' . $row->id . '"' . $selected . '>' . $row->name . '</option>'; 
        $i++; 
    } 

$result->close(); 
$mysqli->close(); 
?>
</select>
</td>
</tr> 

<tr>
<td><label for="amount">Belopp:</label></td>
<td><input type="text" name="amount" value="<?php /* suggested value */ ?>"/></td>
</tr>

<tr>
<td><label for="date">Bokföringsdatum:</label></td>
<td><input type="text" name="date" value="<?php echo date("Y-m-d"); ?>"/></td>
</tr>

<tr>
<td><label for="comment">Kommentar:</label></td>
<td><input type="text" name="comment" value=""/></td>
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
// Halvdålig lösning för att inte försöka spara något när sidan laddas in. Endast vid Spara.
// Förutsätter att get används och ändrar url.
function curPageURL() {
$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
return $url;
}
switch (curPageURL()) {
	case "http://192.168.0.196/gemek2k12/insert_kb_log.php": 
	case "http://127.0.0.1/gemek2k12/insert_kb_log.php":
	case "http://localhost/gemek2k12/insert_kb_log.php":	
		goto footer;
}

// Öppna anslutning till databasen
//require_once('config.php'); 
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE); 
if (mysqli_connect_error()) { 
	echo '<option value="">Kunde inte ansluta: ' . mysqli_connect_error(). '</option>'; 
	exit(); 
} 

// Sanitize data
function replace_swe_entities($string) {
$pattern = array('å','ä','ö');
$replace = array('&aring;','&auml;','&ouml;');
return str_replace($pattern,$replace,$string);
}

if (isset($_GET['account'])) {
$account = $mysqli->real_escape_string($_GET['account']); 
}
if(empty($account)){
	echo "Kan inte spara. Konto/kategori saknas.";
	goto footer;
}

if (isset($_GET['person'])) {
$person = $mysqli->real_escape_string($_GET['person']); 
}
if(empty($person)){
	echo "Kan inte spara. Person saknas.";
	goto footer;
}
$person = replace_swe_entities($person);

if (isset($_GET['amount'])) {
$amount = $mysqli->real_escape_string($_GET['amount']); 
}
if(empty($amount)){
	echo "Kan inte spara. Belopp saknas.";
	goto footer;
}

if (isset($_GET['date'])) {
$date = $mysqli->real_escape_string($_GET['date']); 
}
if(empty($date)){
	echo "Kan inte spara. Bokföringsdatum saknas.";
	goto footer;
}

if (isset($_GET['comment'])) {
$comment = $mysqli->real_escape_string($_GET['comment']); 
}
$comment = replace_swe_entities($comment);
//if(empty($comment)) {$comment="";}

// Build query
$query = "insert into keepbook values ('" 
. idate("U") 
. "','" . $account 
. "','" . $person 
. "','" . $amount 
. "','" . $date 
. "','" . $comment
. "','" . date("Y-m-d H:i:s")
. "','" . $_SERVER['REMOTE_ADDR']
. "')";
$result = $mysqli->query($query)
	or die("Åtgärden kunde inte utföras.<br/><pre>{$query}</pre><br/>{$mysqli->error}"); 

echo "Sparat. Antal rader som påverkats: " . $result;

$mysqli->close(); 
?> 

<?php 
footer:
include("footer.php"); ?>
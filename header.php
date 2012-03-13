<?php error_reporting(-1); ?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="utf-8">
    
    <!-- Meta to ease indexing -->
    <meta name="keywords" content="ekonomi">
    <meta name="description" content="Gemensam ekonomi">
    <meta name="author" content="Johan Dalenius, johan.dalenius@gmail.com">
    <meta name="copyright" content="Copyright 2012"> 
    
    <!-- Stylesheets -->
<?php
	//Egen stylesheet för Nene.
	if ($_SERVER['REMOTE_ADDR'] == '192.168.0.199') {$style = 'nene';} else {$style = 'stylesheet';}
	echo '<link rel="stylesheet" media="all" href="style/' . $style . '.css" title="Default" type="text/css">';
?>
    <link rel="stylesheet" media="print" href="style/print.css" type="text/css"> 
    <link rel="alternative stylesheet" href="style/dv1401_kmom01.css" title="Exempel från DV1401 Kmom01" type="text/css">
    <link rel="alternative stylesheet" href="style/dv1401_kmom02.css" title="Exempel från DV1401 Kmom02" type="text/css">
   
    <!-- Page icon -->
    <link rel="shortcut icon" href="/img/favicon.ico">
    
    <!-- Dynamic page title -->
    <?php echo "<title>$title</title>"; ?>
    
     <!-- Use PHP to add style information, used by the CSS-20 page to display examples -->
     <?php if(!empty($head)) echo $head; ?>  
    
</head>

<!-- Use PHP to set id of body, used to highlight current page, together with styling information -->
<body<?php if(!empty($bodyId)) echo " id='$bodyId'"; ?>>

        <!-- Top header with logo and navigation -->
        <header id="top">
                <!-- 
				<img id="logo" src="img/logo.jpg" alt="gemek2k12 logo" width=240 height=30>
                -->
				<nav>
                        <a id="insert_kb_log-" href="insert_kb_log.php">Bokför</a>
						<a id="account_plan-" href="account_plan.php">Kontoplan</a>
                        <a id="insert_account-" href="insert_account.php">Ny kontokod</a>
                        <a id="search-" href="search_thebook.php">TestSökning</a>             
                </nav>
        </header>

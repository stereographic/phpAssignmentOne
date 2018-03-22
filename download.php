<?php
    session_start();
    // prevents people from directly hitting the page
	if (($_SESSION['user'] == "")){
		header("Location: login.html");
		exit;
    }
    // parsing database info & connecting to database 
	$config = parse_ini_file('config.ini'); 
    $db_name = $config['DB_DATABASE'];    
    $contact = $config['DB_TABLE'];
    
    $username = $_SESSION['user'];
    $ts = date("ljSFYhis");

    $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], "") 
    or die(mysql_error());

    $db = @mysqli_select_db($connection, $db_name) or die(mysql_error());

    $sql = "SELECT id, firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact WHERE user='$username' INTO OUTFILE 'C:/wamp64/www/php/WEBD3000AddressBook/{$ts}clients.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
    $result = @mysqli_query($connection, $sql) or die(mysqli_error($connection));
    $connection->close();
?>




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
    
    $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], "") 
    or die(mysql_error());

    $db = @mysqli_select_db($connection, $db_name) or die(mysql_error());

    $id = $_GET['id'];
    $sql = "DELETE FROM $contact WHERE id='$id'";

    $displayblock = "";

    if (@mysqli_query($connection, $sql)) {
        $display_block = "Client Contact Information: Deletion Success";
    } else {
        $display_block = "Client Contact Information: *** Deletion Failure ***";
    }

    $connection->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Client Contact Deletion</title>
	</head>
	<body>
        <h1>
		    <?php echo "$display_block"; ?>
        </h1>
	</body>
</html>
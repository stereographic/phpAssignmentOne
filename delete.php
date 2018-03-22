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
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Oswald" rel="stylesheet">
	</head>
	<body>

    <ul>
        <li><a href="index.php" >Home</a></li>
        <li><a href="add.php" >Add New Customer</a></li>
        <li><a href="currentBirthdays.php" >Current Months Birthdays</a></li>
        <li><a href="download.php" >Download Contacts CSV</a></li>
        <li><a href="upload.php" >Upload Contacts CSV</a></li>
        <li class="logout"><a href="logout.php" >Logout</a></li>
    </ul>
    <div class="mainWrapper">
        <h1>
		    <?php echo "$display_block"; ?>
        </h1>
    </div>
	</body>
</html>
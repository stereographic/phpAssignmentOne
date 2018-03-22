<?php
    session_start();
    $config = parse_ini_file('config.ini'); 
    $db_name = $config['DB_DATABASE'];    
    $contact = $config['DB_TABLE'];
    
    // ------------------------------------------------------------------------- database connection
    $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], "") or die(mysql_error());
    $db = @mysqli_select_db($connection, $db_name) or die(mysql_error());

    // prevents people from directly hitting the page
    if (($_SESSION['user'] == "")){
        header("Location: login.html");
        exit;
    }

?>
<!DOCTYPE html>
<html>
	<head>
        <title><?php echo $_SESSION['user']; ?> Client Contact Information</title>
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Oswald" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.2.1/dt-1.10.16/r-2.2.1/datatables.min.css"/>
	</head>
	<body>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="add.php" >Add New Customer</a></li>
        <li><a href="currentBirthdays.php" >Current Months Birthdays</a></li>
        <li><a href="download.php" >Download Contacts CSV</a></li>
        <li><a href="upload.php" class="active">Upload Contacts CSV</a></li>
        <li class="logout"><a href="logout.php" >Logout</a></li>
    </ul>
    <div class="mainWrapper">
    </div>

</body>
</html>

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
    // pulling out the username from the session
    $username = $_SESSION['user'];
    // setting path
    $dlPath = "/home/croweak/tmp/contacts/{$username}clients.csv";
    // deleting file
    @unlink("/home/croweak/tmp/contacts/{$username}clients.csv");
    // connecting to the database
    $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD']) or die(mysql_error());
    $db = @mysqli_select_db($connection, $db_name) or die(mysql_error());

    // admin downloads a full history of all contacts for all clients, and reps can only download their own contacts
    if ($_SESSION['user'] == "admin"){
        $sql = "SELECT firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact INTO OUTFILE '{$dlPath}' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";

    } else {
        $sql = "SELECT firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact WHERE user='$username' INTO OUTFILE '{$dlPath}' FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";

    }  
    $result = @mysqli_query($connection, $sql) or die(mysqli_error($connection));

    $connection->close();
?>
<!DOCTYPE html>
<html>
	<head>
        <title><?php echo $_SESSION['user']; ?> Client Contact Information</title>
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Oswald" rel="stylesheet">
	</head>
	<body>
        
            <ul>
                <li><a href="index.php" >Home</a></li>
                <li><a href="add.php" >Add New Customer</a></li>
                <li><a href="currentBirthdays.php" >Current Months Birthdays</a></li>
                <li><a href="download.php" class="active">Download Contacts CSV</a></li>
                <li><a href="upload.php" >Upload Contacts CSV</a></li>
                <li><a href="mail.php">Send Mass Email</a></li>
                <li class="logout"><a href="logout.php" >Logout</a></li>
            </ul>
            <div class="mainWrapper">

            <h1>Download Contacts</h1>
            <a href="<?php echo "contacts/{$username}clients.csv"; ?>">Download up to date client list</a>
        </div>
    </body>
</html>




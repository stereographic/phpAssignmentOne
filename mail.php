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
    // if the form has been submitted, get data & send emails
    if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
        // ------------------------------------------------------------------------- database connection
        // initial SQL Query to get emails
        $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD']) or die(mysql_error());
        $db = @mysqli_select_db($connection, $db_name) or die(mysql_error());
        // allows admins to send emails to all clients, and limits users to their specific clients
        if ($_SESSION['user'] == "admin"){
            $sql = "SELECT email FROM $contact";
        } else {
            $sql = "SELECT email FROM $contact WHERE user='$username'";
        }
        $result = @mysqli_query($connection, $sql) or die(mysqli_error($connection));
        // building the email headers
        $subject = $_POST['mail_subject'];
        $sender = $_SESSION['user'].'@croweak.nscctruro.ca';
        $msg = $_POST['message'];
        $headers = "From: webmaster@croweak.nscctruro.ca" . "\r\n" ;
        // sending an email to all addresses listed for a user
        while ($row = mysqli_fetch_array($result)) {
            $email = $row['email'];
            mail($email,$subject,$msg,$headers);
        }
        $connection->close();
    }
?>  
<!DOCTYPE html>
<html>
	<head>
        <title>Client Contact Editing</title>
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
                <li><a href="mail.php">Send Mass Email</a></li>
                <li class="logout"><a href="logout.php" >Logout</a></li>
            </ul>
            <div class="mainWrapper">
            <h1>Send Mass Emails</h1>
            <form method="POST" action="">
                <p>
                    <strong>Your Name:</strong><br> 
                    <input type="text" name="sender_name" size=30>
                </p>
                <p>
                    <strong>Subject:</strong><br>
                    <input type="text" name="mail_subject" size=30>
                </p>
                <p>
                    <strong>Message:</strong><br>
                    <textarea name="message" cols=200 rows=20 wrap=virtual>Enter Message here.</textarea>
                </p>
                <input type="hidden" name="op" value="ds">
                <p>
                <input type="submit" name="submit" value="Send Newsletter">
                </p>
            </form>
        </div>
	</body>
</html>
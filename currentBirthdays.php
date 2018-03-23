<?php
    session_start();
    $config = parse_ini_file('config.ini'); 
    $db_name = $config['DB_DATABASE'];    
    $contact = $config['DB_TABLE'];
    
    // ------------------------------------------------------------------------- database connection
    $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD']) or die(mysql_error());
    $db = @mysqli_select_db($connection, $db_name) or die(mysql_error());

    // prevents people from directly hitting the page
    if (($_SESSION['user'] == "")){
        header("Location: login.html");
        exit;
    }
    $display_block = "";
    $username = $_SESSION['user'];
    $currentMonth = date('m');
    // allowing the admin to view all and users to view only their clients
    if ($_SESSION['user'] == "admin"){
        $sql = "SELECT id, firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact WHERE birthday LIKE '_____$currentMonth%'";

    } else {
        $sql = "SELECT id, firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact WHERE birthday LIKE '_____$currentMonth%' AND user='$username'";

    }
    $result = @mysqli_query($connection, $sql) or die(mysqli_error($connection));
    // creating a table to display data
	while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $phone = $row['phone'];
        $email = $row['email'];
        $address = $row['address'];
        $city = $row['city'];
        $province = $row['province'];
        $postal = $row['postal'];
        $birthday = $row['birthday'];


        $fullname = trim("$firstName $lastName");
        if ($birthday == "0000-00-00") {
            $birthday = "[unknown]";
        }
        $display_block .= "<tr>
            <td>$fullname</td> <td>$phone</td> <td>$email</td> <td>$address</td> <td>$city</td> <td>$province</td> <td>$postal</td> <td>$birthday</td>
        </tr>";
    }

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
                <li><a href="currentBirthdays.php" class="active">Current Months Birthdays</a></li>
                <li><a href="download.php" >Download Contacts CSV</a></li>
                <li><a href="upload.php" >Upload Contacts CSV</a></li>
                <li><a href="mail.php">Send Mass Email</a></li>
                <li class="logout"><a href="logout.php" >Logout</a></li>
            </ul>
            <div class="mainWrapper">
                <h1><?php echo $_SESSION['user']; ?>'s Clients</h1>
                <table style="width:100%;">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Postal</th>
                        <th>Birthday</th>
                    </tr>
                    <?php echo "$display_block"; ?>
                </table>
        </div>
	</body>
</html>
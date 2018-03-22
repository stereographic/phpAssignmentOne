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
    $display_block = "";
    $username = $_SESSION['user'];
    $currentMonth = date('m');

    $sql = "SELECT id, firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact WHERE birthday LIKE '_____$currentMonth%' AND user='$username'";

    $result = @mysqli_query($connection, $sql) or die(mysqli_error($connection));
    
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
	</head>
	<body>
    <ul>
        <li><a href="add.php" target="_blank">Add New Customer</a></li>
        <li><a href="currentBirthdays.php" target="_blank">Current Months Birthdays</a></li>
    </ul>
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
	</body>
</html>
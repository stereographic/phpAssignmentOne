<?php
    session_start();
    // parsing database info & connecting to database 
	$config = parse_ini_file('config.ini'); 
    $db_name = $config['DB_DATABASE'];    
    $contact = $config['DB_TABLE'];
    $login = $config['DB_TABLELOGIN'];
    
    // ------------------------------------------------------------------------- database connection
    $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], "") or die(mysql_error());
    $db = @mysqli_select_db($connection, $db_name) or die(mysql_error());
    
    // prevents people from directly hitting the page
	if (($_SESSION['user'] == "")){
        if (($_POST['username'] == "") && ($_POST['password'] == "")){
            header("Location: login.html");
            exit;
        } else {
            // ------------------------------------------------------------------------- user verification
            $username = $_POST['username'];
            $password = $_POST['password'];
        
            // getting salted hash
            $stmt = $connection->prepare("SELECT ukey FROM $login WHERE user=?");
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $stmt->bind_result($ukey);
            $stmt->fetch();
            $stmt->close();
        
            // generating new users debugging
            // echo password_hash('321',1);
            //user1 = 123
            //user2 = 321
        
            // verifying login info
            if (password_verify($password,$ukey)) {
                $_SESSION['user'] = $username;
            } else {
                echo 'Password Rejected';
                header("Location: login.html");
                exit;
            }
        }
    }
    $username = $_SESSION['user'];
    // ------------------------------------------------------------------------- displaying contact data
    $display_block = "";
    // connecting to the database
    //$connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD']) or die(mysqli_error($connection));
    // $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], "") or die(mysqli_error($connection));
    // // reference to database
    // $db = @mysqli_select_db($connection, $db_name) or die(mysqli_error($connection));
    
    $sql = "SELECT id, firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact WHERE user='$username'";

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
            <td><form action='edit.php' target='_blank' ><input type='hidden' name='id' value='$id'><input type='submit' value='Edit'></form></td>
            <td><form action='delete.php' target='_blank' ><input type='hidden' name='id' value='$id'><input type='submit' value='Delete'></form></td>
        </tr>";
    }
    // ------------------------------------------------------------------------- closing database connection
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
    </ul>
		<h1><?php echo $_SESSION['user']; ?>'s Clients</h1>
        <input type="submit" value="Add Contact" target>
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
                <th>Edit Contact</th>
                <th>Delete Contact</th>
            </tr>
		    <?php echo "$display_block"; ?>
        </table>
	</body>
</html>

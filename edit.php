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
    $sql = "SELECT id, firstName, lastName, phone, email, address, city, province, postal, birthday, user FROM $contact WHERE id='$id'";
    
    $result = @mysqli_query($connection, $sql) or die(mysqli_error($connection));
    $row = mysqli_fetch_array($result);


    $firstName = $row['firstName'];
    $lastName = $row['lastName'];
    $phone = $row['phone'];
    $email = $row['email'];
    $address = $row['address'];
    $city = $row['city'];
    $province = $row['province'];
    $postal = $row['postal'];
    $birthday = $row['birthday'];

    $display_block = 
    "<form action='editSubmit.php'>
    <input type='hidden' name='id' value='$id'>

    <label for='fName'>First Name
    </br>
    <input type='text' name='fName' value='$firstName'>
    </label>
    </br>

    <label for='lName'>Last Name
    </br>
    <input type='text' name='lName' value='$lastName'>
    </label>
    </br>

    <label for='phone'>Phone Number
    </br>
    <input type='text' name='phone' value='$phone'>
    </label>
    </br>

    <label for='email'>E-Mail
    </br>
    <input type='email' name='email' value='$email'>
    </label>
    </br>

    <label for='address'>Address
    </br>
    <input type='text' name='address' value='$address'>
    </label>
    </br>

    <label for='city'>City
    </br>
    <input type='text' name='city' value='$city'>
    </label>
    </br>

    <label for='province'>Province
    </br>
    <input type='text' name='province' value='$province'>
    </label>
    </br>

    <label for='postal'>Postal
    </br>
    <input type='text' name='postal' value='$postal'>
    </label>
    </br>

    <label for='birthday'>Birthday
    </br>
    <input type='date' name='birthday' value='$birthday'>
    </label>
    </br>

    <input type='submit' value='Submit'>
    </form>";



    $connection->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Client Contact Editing</title>
	</head>
	<body>
        <h1>Edit Client Details</h1>
		    <?php echo "$display_block"; ?>

	</body>
</html>
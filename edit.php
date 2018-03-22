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

    $display_block = "";

    if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
        $id = $_POST['id'];
        $firstName = $_POST['fName'];
        $lastName = $_POST['lName'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $postal = $_POST['postal'];
        $birthday = $_POST['birthday'];

        $stmt = $connection->prepare("UPDATE $contact SET firstName=?, lastName=?, phone=?, email=?, address=?, city=?, province=?, postal=?, birthday=? WHERE id=?");
        $stmt->bind_param("sssssssssi", $firstName, $lastName, $phone, $email, $address, $city, $province, $postal, $birthday, $id);
        $stmt->execute();
        $stmt->close();

        $display_block .= "<h3>Client Contact Information: Modification Success</h3>";
    }

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

    $display_block .= 
    "<form action='' method='post'>
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
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans|Oswald" rel="stylesheet">
	</head>
	<body>
        
            <ul>
                <li><a href="index.php" >Home</a></li>
                <li><a href="add.php" >Add New Customer</a></li>
                <li><a href="currentBirthdays.php" >Current Months Birthdays</a></li>
                <li><a href="download.php" >Download Contacts CSV</a></li>
                <li><a href="download.php" >Upload Contacts CSV</a></li>
                <li><a href="logout.php" >Logout</a></li>
            </ul>
            <div class="mainWrapper">
            <h1>Edit Client Details</h1>
            <?php echo "$display_block"; ?>
        </div>
	</body>
</html>
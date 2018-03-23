<?php
    session_start();
    // parsing database info & connecting to database 
	$config = parse_ini_file('config.ini'); 
    $db_name = $config['DB_DATABASE'];    
    $contact = $config['DB_TABLE'];
    $login = $config['DB_TABLELOGIN'];
    $dbpassword = $config['DB_PASSWORD'];
    
    // ------------------------------------------------------------------------- database connection
    $connection = mysqli_connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD']) or die(mysql_error());
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
            <td><form action='edit.php'><input type='hidden' name='id' value='$id'><input type='submit' value='Edit' class='edit'> </form></td>
            <td><form action='delete.php'><input type='hidden' name='id' value='$id'><input type='submit' value='Delete' class='delete'> </form></td>
        </tr>";
    }
    // ------------------------------------------------------------------------- closing database connection
    $connection->close();

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
        <li><a href="index.php" class="active">Home</a></li>
        <li><a href="add.php" >Add New Customer</a></li>
        <li><a href="currentBirthdays.php" >Current Months Birthdays</a></li>
        <li><a href="download.php" >Download Contacts CSV</a></li>
        <li><a href="upload.php" >Upload Contacts CSV</a></li>
        <li class="logout"><a href="logout.php" >Logout</a></li>
    </ul>
        <div class="mainWrapper">
            <h1><?php echo $_SESSION['user']; ?>'s Clients</h1>
            <table id="table_id">
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
                <tbody>
                    <?php echo "$display_block"; ?>
                </tbody>
            </table>
        </div>

    </body>
</html>

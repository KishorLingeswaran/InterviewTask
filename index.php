
<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "interviewTask"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);


   


    $stmt = $conn->prepare("INSERT INTO login (username, password) VALUES (?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
 $stmt->bind_param("ss", $username, $password);


    if ($stmt->execute()) {

        header("Location: monthly_reports.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }


    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Glass House LLP</title>
    <link rel="stylesheet" href="index.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="header">

<div class="header-left">
<i class="fa-solid fa-hourglass-end"></i>
   <span> Glass <br>House LLP</spn>
</div>
<div class="header-right">
    <span class="hello-admin">Hello: Admin <i class="fa-solid fa-right-from-bracket"></i></span>
</div>
</div>

 
    <div class="login-container">
        <h2>Login</h2>
        <p>Already have an account? Use your login details</p>
        <form action="index.php" method="POST">
            <label for="username">User Name</label>
            <input type="text" id="username" name="username" placeholder="demouser" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="*******" required>

            <button type="submit">Submit</button>
        </form>
    </div>

    <div class="footer">
        &copy; 2023-24 All Rights Reserved, Glass House LLP. All Terms and Conditions Apply.
    </div>
</body>
</html>


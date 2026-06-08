<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    if (!empty($username)) {
        $_SESSION['loggedInUser'] = $username;
        header("Location: rooms.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Grand Horizon Hotel</h2>
        <p>Please log in to manage guest bookings</p>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Enter Username (e.g. Rana)" required>
            <input type="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
    </div>
</body>
</html>
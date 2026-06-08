<?php
session_start();
$user = isset($_SESSION['loggedInUser']) ? $_SESSION['loggedInUser'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="nav-bar">
    <h2>Welcome, <?php echo htmlspecialchars($user); ?>!</h2>

```
<div class="nav-links">
    <a href="rooms.php">Home</a>
    <a href="#contact">Contact</a>
    <a href="booking.php" class="nav-btn">Book Now</a>
</div>
```

</div>


    <div class="container large">
        <h2>Available Hotel Rooms</h2>
        <p>Explore our luxury rooms and prices</p>
        
       <div class="rooms-grid">
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=500&auto=format&fit=cover" alt="Single Room">
                <h3>Single Room</h3>
                <p>Capacity: 1 Guest</p>
                <p class="price">$250 / Night</p>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=500&auto=format&fit=cover" alt="Deluxe Suite">
                <h3>Deluxe Suite</h3>
                <p>Capacity: 4 Guests</p>
                <p class="price">$750 / Night</p>
            </div>
            <div class="room-card">
                <img src="https://images.unsplash.com/photo-1591088398332-8a7791972843?w=500&auto=format&fit=cover" alt="Family Room">
                <h3>Family Room</h3>
                <p>Capacity: 5 Guests</p>
                <p class="price">$900 / Night</p>
            </div>
        </div>

<div class="footer-info" id="contact">
            <h3>Contact Reception</h3>
            <p>📞 Phone: +123 456 789 &nbsp;|&nbsp; 📧 Email: info@grandhorizon.com</p>
        </div>
    </div>
</body>
</html>
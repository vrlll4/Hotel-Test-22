<?php
session_start();

// ربط الملف بالاسم المعتمد في مجلدكِ
require_once 'db_conect.php'; 

// إذا كان المستخدم مسجل دخول نضع اسمه كقيمة مبدئية، وإلا يظهر فارغاً ليدخل اسمه
$user = isset($_SESSION['loggedInUser']) ? $_SESSION['loggedInUser'] : '';
$showSuccess = false;
$showError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $guest_name = $_POST['guest_name']; // استقبال الاسم المكتوب في الخانة
    $phone = $_POST['phone'];           // الحقل الجديد: رقم الهاتف
    $room_number = $_POST['room_number'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $num_guests = $_POST['num_guests'];
    $status = $_POST['status'];         // الحقل الجديد: حالة الحجز
    $total_price = $_POST['total_price']; // الحقل الجديد: السعر الإجمالي
    $booking_date = $_POST['booking_date']; // الحقل الجديد: تاريخ إجراء الحجز

    // 1. التحقق من وجود الضيف أو إنشائه مع حفظ رقم الهاتف الجديد
    $stmt = $conn->prepare("SELECT guest_id FROM guests WHERE guest_name = ?");
    $stmt->bind_param("s", $guest_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $guest_id = $row['guest_id'];
        
        // تحديث رقم الهاتف للضيف الحالي
        $updatePhone = $conn->prepare("UPDATE guests SET phone = ? WHERE guest_id = ?");
        $updatePhone->bind_param("si", $phone, $guest_id);
        $updatePhone->execute();
    } else {
        // إنشاء ضيف جديد بالاسم ورقم الهاتف
        $insertGuest = $conn->prepare("INSERT INTO guests (guest_name, phone) VALUES (?, ?)");
        $insertGuest->bind_param("ss", $guest_name, $phone);
        $insertGuest->execute();
        $guest_id = $conn->insert_id; 
    }

    // 2. إدخال بيانات الحجز كاملة متوافقة مع الحقول الجديدة في جدول الحجوزات
    $insertBooking = $conn->prepare("INSERT INTO bookings (guest_id, room_number, check_in_date, check_out_date, num_guests, status, total_price, booking_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertBooking->bind_param("iissisds", $guest_id, $room_number, $check_in, $check_out, $num_guests, $status, $total_price, $booking_date);
    
    if ($insertBooking->execute()) {
        $showSuccess = true;
    } else {
        $showError = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Booking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="nav-bar">
        <h2>Book Your Room</h2>
        <a href="rooms.php" class="nav-btn">Back to Rooms</a>
    </div>

    <div class="container">
        <h2>Reservation Form</h2>
        <form method="POST" action="">
            <label>Guest Name:</label>
            <input type="text" name="guest_name" value="<?php echo htmlspecialchars($user); ?>" placeholder="Enter guest name" required>

            <label>Phone Number:</label>
            <input type="text" name="phone" placeholder="Enter phone number (e.g. 050xxxxxxx)" required>

            <label>Select Room:</label>
            <select name="room_number" id="room_select" onchange="calculateTotal()" required>
                <option value="101" data-price="250">Room 101 - Single Room ($250)</option>
                <option value="102" data-price="400">Room 102 - Double Room ($400)</option>
                <option value="104" data-price="900">Room 104 - Family Room ($900)</option>
                <option value="106" data-price="420">Room 106 - Double Room ($420)</option>
                <option value="107" data-price="700">Room 107 - Deluxe Suite ($700)</option>
            </select>

            <label>Check-in Date:</label>
            <input type="date" name="check_in" id="check_in" onchange="calculateTotal()" required>

            <label>Check-out Date:</label>
            <input type="date" name="check_out" id="check_out" onchange="calculateTotal()" required>

            <label>Number of Guests:</label>
            <input type="number" name="num_guests" min="1" max="6" required>

            <label>Booking Status:</label>
            <select name="status" required>
                <option value="Confirmed">Confirmed</option>
                <option value="Pending">Pending</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <label>Total Price ($):</label>
            <input type="number" step="0.01" name="total_price" id="total_price" placeholder="Total price" readonly required>

            <label>Booking Date:</label>
            <input type="date" name="booking_date" value="<?php echo date('Y-m-d'); ?>" required>

            <button type="submit">Confirm Booking</button>
        </form>

        <?php if ($showSuccess): ?>
        <div class="success-box">
            🎉 Booking Successful! Your room has been reserved with all details.
        </div>
        <?php endif; ?>
    </div>

    <script>
    function calculateTotal() {
        var roomSelect = document.getElementById('room_select');
        var pricePerNight = parseFloat(roomSelect.options[roomSelect.selectedIndex].getAttribute('data-price'));
        
        var checkInDate = new Date(document.getElementById('check_in').value);
        var checkOutDate = new Date(document.getElementById('check_out').value);
        
        if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
            var timeDiff = checkOutDate.getTime() - checkInDate.getTime();
            var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
            
            var total = daysDiff * pricePerNight;
            document.getElementById('total_price').value = total;
        } else {
            document.getElementById('total_price').value = pricePerNight; // قيمة مبدئية لليلة واحدة
        }
    }
    // تشغيل الحسبة لأول مرة تلقائياً
    window.onload = calculateTotal;
    </script>
</body>
</html>
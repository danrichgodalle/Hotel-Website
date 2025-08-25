<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$room = isset($_GET['room']) ? $_GET['room'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkin = $_POST['checkin'];
    $days = intval($_POST['days']);
    $checkout = date('Y-m-d', strtotime($checkin . " +$days days"));

    $rate = floatval($_POST['rate']);
    $total = $rate * $days;
    $guests = intval($_POST['persons']);
    $share = $guests > 0 ? ($total / $guests) : 0;

    $booking = [
        'room' => $_POST['room'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'checkin' => $checkin,
        'checkout' => $checkout,
        'days' => $days,
        'guests' => $guests,
        'rate_per_night' => $rate,
        'total_cost' => $total,
        'share_per_person' => $share,
        'status' => 'Pending',
    ];

    $_SESSION['bookings'][] = $booking;
    $_SESSION['success_message'] = "Booking submitted successfully!";
    header("Location: my_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('image/banner2.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .booking-form-container {
            background: rgba(0, 32, 64, 0.7);
            max-width: 720px;
            margin: 60px auto;
            padding: 40px 30px 30px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            color: #fff;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-group {
            flex: 1 1 calc(50% - 10px);
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 6px;
            font-size: 15px;
        }
        input, select {
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 15px;
        }
        input[readonly] {
            background: #e0e0e0;
            color: #333;
        }
        .full-width {
            flex: 1 1 100%;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #ff5722;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 17px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .submit-btn:hover {
            background: #e64a19;
        }
        .back-btn {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            background: #607d8b;
            color: #fff;
            border-radius: 4px;
            font-size: 15px;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s;
        }
        .back-btn:hover {
            background: #455a64;
        }
        .footer {
            text-align: center;
            color: #fff;
            margin-top: 30px;
            font-size: 13px;
        }
        @media (max-width: 700px) {
            form {
                flex-direction: column;
            }
            .form-group {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
<div class="booking-form-container">
    <h2>Room Booking Form (with Split Cost)</h2>
    <form method="POST">
        <div class="form-group">
            <label for="room">Select Room Type</label>
            <select id="room" name="room" required>
                <option value="">-- Select Room Type --</option>
                <option value="Air Conditioned Cabin" <?php if ($room == "Air Conditioned Cabin") echo "selected"; ?>>Air Conditioned Cabin</option>
                <option value="Family Room" <?php if ($room == "Family Room") echo "selected"; ?>>Family Room</option>
                <option value="Standard Room" <?php if ($room == "Standard Room") echo "selected"; ?>>Standard Room</option>
                <option value="Premium Room" <?php if ($room == "Premium Room") echo "selected"; ?>>Premium Room</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required />
        </div>

        <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required />
        </div>

        <div class="form-group">
            <label for="persons">Number Of Persons</label>
            <input type="number" id="persons" name="persons" min="1" value="1" required />
        </div>

        <div class="form-group">
            <label for="days">Number Of Days</label>
            <input type="number" id="days" name="days" min="1" value="1" required />
        </div>

        <div class="form-group">
            <label for="checkin">Date Of Arrival</label>
            <input type="date" id="checkin" name="checkin" required />
        </div>

        <div class="form-group">
            <label for="rate">Room Rate per Night (₱)</label>
            <input type="number" id="rate" name="rate" value="0" readonly required />
        </div>

        <div class="form-group">
            <label for="total">Total Cost (₱)</label>
            <input type="number" id="total" name="total" readonly />
        </div>

        <div class="form-group full-width">
            <label for="share">Cost Per Person (₱)</label>
            <input type="number" id="share" name="share" readonly />
        </div>

        <button type="submit" class="submit-btn full-width">Submit Booking</button>
    </form>

    <a href="client_dashboard.php" class="back-btn">Back</a>
    <div class="footer">&copy; 2025 Room Booking System. All Rights Reserved.</div>
</div>

<script>
    const roomSelect = document.getElementById('room');
    const rateInput = document.getElementById('rate');
    const daysInput = document.getElementById('days');
    const personsInput = document.getElementById('persons');
    const totalInput = document.getElementById('total');
    const shareInput = document.getElementById('share');

    const roomRates = {
        "Air Conditioned Cabin": 2500,
        "Family Room": 1300,
        "Standard Room": 1700,
        "Premium Room": 2500
    };

    function updateCosts() {
        const rate = parseFloat(rateInput.value) || 0;
        const days = parseInt(daysInput.value) || 1;
        const persons = parseInt(personsInput.value) || 1;

        const total = rate * days;
        const share = persons > 0 ? total / persons : 0;

        totalInput.value = total.toFixed(2);
        shareInput.value = share.toFixed(2);
    }

    roomSelect.addEventListener('change', function () {
        const selectedRoom = roomSelect.value;
        rateInput.value = roomRates[selectedRoom] || 0;
        updateCosts();
    });

    daysInput.addEventListener('input', updateCosts);
    personsInput.addEventListener('input', updateCosts);

    window.addEventListener('DOMContentLoaded', () => {
        if(roomSelect.value) {
            rateInput.value = roomRates[roomSelect.value] || 0;
        }
        updateCosts();
    });
</script>
</body>
</html>

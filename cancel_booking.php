<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['index'])) {
    $index = $_GET['index'];
    if (isset($_SESSION['bookings'][$index])) {
        unset($_SESSION['bookings'][$index]);
        $_SESSION['bookings'] = array_values($_SESSION['bookings']); // Reindex
        $_SESSION['success_message'] = "Booking cancelled successfully!";
    }
}

header("Location: my_bookings.php");
exit();

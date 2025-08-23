<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$room = $_GET['room'] ?? 'Unknown';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment for <?php echo htmlspecialchars($room); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-credit-card-fill"></i> Payment for <?php echo htmlspecialchars($room); ?></h5>
        </div>
        <div class="card-body">
            <p class="mb-3">This is a mock payment page. No real payment will be processed.</p>

            <form>
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-wallet2"></i> Payment Method</label>
                    <select class="form-select" disabled>
                        <option selected>Choose...</option>
                        <option>GCash</option>
                        <option>PayPal</option>
                        <option>Credit Card</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-person-fill"></i> Cardholder Name</label>
                    <input type="text" class="form-control" placeholder="Juan Dela Cruz" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-credit-card-2-front-fill"></i> Card Number</label>
                    <input type="text" class="form-control" placeholder="0000 0000 0000 0000" disabled>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-success" disabled><i class="bi bi-lock-fill"></i> Pay Now</button>
                    <a href="my_bookings.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

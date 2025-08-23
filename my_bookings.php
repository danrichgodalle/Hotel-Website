<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$bookings = $_SESSION['bookings'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #eef2f7;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 2rem 1rem 3rem;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
    }
    h2 {
      font-weight: 700;
      color: #34495e;
      margin-bottom: 2rem;
      text-align: center;
      letter-spacing: 1.2px;
    }
    .booking-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgb(0 0 0 / 0.08);
      padding: 1.5rem 2rem;
      margin-bottom: 1.6rem;
      transition: box-shadow 0.3s ease;
    }
    .booking-card:hover {
      box-shadow: 0 12px 35px rgb(0 0 0 / 0.12);
    }
    .booking-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      flex-wrap: wrap;
      gap: 0.8rem;
    }
    .room-name {
      font-size: 1.5rem;
      font-weight: 700;
      color: #2c3e50;
    }
    .status-badge {
      font-weight: 700;
      font-size: 0.95rem;
      padding: 0.4em 1em;
      border-radius: 50px;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .status-confirmed {
      background-color: #28a745;
      color: white;
    }
    .status-pending {
      background-color: #ffc107;
      color: #212529;
    }
    .status-cancelled {
      background-color: #dc3545;
      color: white;
    }
    .booking-details {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
      font-size: 1rem;
      color: #555;
    }
    .booking-details div {
      flex: 1 1 150px;
    }
    .booking-actions {
      margin-top: 1.2rem;
      text-align: right;
    }
    .btn-sm {
      font-size: 0.95rem;
      padding: 0.45rem 1rem;
      border-radius: 6px;
      font-weight: 600;
      transition: background-color 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    .btn-sm:hover {
      filter: brightness(0.9);
    }
    .btn-pay {
      background-color: #ffc107;
      border: none;
      color: #212529;
    }
    .btn-cancel {
      background-color: #dc3545;
      border: none;
      color: white;
    }
    .btn-back {
      display: block;
      max-width: 180px;
      margin: 3rem auto 0 auto;
      font-weight: 600;
      letter-spacing: 0.06em;
      border-radius: 8px;
      padding: 0.55rem 1.4rem;
      background-color: #6c757d;
      color: white;
      text-align: center;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }
    .btn-back:hover {
      background-color: #5a6268;
      text-decoration: none;
      color: white;
    }
    .alert {
      max-width: 600px;
      margin: 0 auto 2rem auto;
    }
    @media (max-width: 576px) {
      .booking-details div {
        flex: 1 1 100%;
      }
      .booking-actions {
        text-align: center;
      }
      .btn-cancel {
        margin-left: 0;
        margin-top: 0.6rem;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>My Bookings</h2>

  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (empty($bookings)): ?>
    <div class="alert alert-info text-center">
      You have no bookings at the moment.
    </div>
  <?php else: ?>
    <?php foreach ($bookings as $index => $booking): 
      $status = strtolower($booking['status']);
      $statusClass = match($status) {
        'confirmed' => 'status-confirmed',
        'pending' => 'status-pending',
        'cancelled' => 'status-cancelled',
        default => 'badge bg-secondary'
      };
    ?>
      <div class="booking-card">
        <div class="booking-header">
          <div class="room-name">Room <?php echo htmlspecialchars($booking['room']); ?></div>
          <div class="status-badge <?php echo $statusClass; ?>">
            <?php
              if ($status === 'confirmed') echo '<i class="bi bi-check-circle-fill fs-5"></i>';
              elseif ($status === 'pending') echo '<i class="bi bi-clock-fill fs-5"></i>';
              elseif ($status === 'cancelled') echo '<i class="bi bi-x-circle-fill fs-5"></i>';
            ?>
            <?php echo ucfirst($booking['status']); ?>
          </div>
        </div>
        <div class="booking-details">
          <div><strong>Check-in:</strong> <?php echo date('M d, Y', strtotime($booking['checkin'])); ?></div>
          <div><strong>Check-out:</strong> <?php echo date('M d, Y', strtotime($booking['checkout'])); ?></div>
          <div><strong>Guests:</strong> <?php echo (int)$booking['guests']; ?></div>
        </div>
        <div class="booking-actions d-flex flex-wrap justify-content-end gap-2 mt-3">
          <?php if ($status === 'pending'): ?>
            <a href="payment.php?room=<?php echo urlencode($booking['room']); ?>" class="btn btn-pay btn-sm">
              <i class="bi bi-wallet2 fs-5"></i> Pay
            </a>
            <a href="cancel_booking.php?index=<?php echo $index; ?>" onclick="return confirm('Are you sure you want to cancel this booking?');" class="btn btn-cancel btn-sm">
              <i class="bi bi-x-circle fs-5"></i> Cancel
            </a>
          <?php else: ?>
            <span class="text-muted fst-italic">No actions available</span>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <a href="client_dashboard.php" class="btn-back">← Back to Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


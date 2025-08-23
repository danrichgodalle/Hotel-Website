<?php
// Static admin dashboard for hotel system
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hotel Admin Dashboard</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: #f8f9fa;
    }
    #wrapper {
      display: flex;
      width: 100%;
    }
    #sidebar-wrapper {
      min-height: 100vh;
      width: 250px;
      background-color: #343a40;
    }
    #page-content-wrapper {
      flex: 1;
      padding: 20px;
    }
     .sidebar-heading {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 1.5rem 1rem;
      color: #fff;
      font-size: 1.5rem;
      font-family: 'Montserrat', sans-serif;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .sidebar-heading img {
      width: 45px;
      height: 45px;
      object-fit: cover;
      border-radius: 50%;
    }
    .list-group-item {
      border: none;
      padding: 18px 25px;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      gap: 14px;
      background: #343a40;
      color: #fff;
      transition: background 0.2s;
    }
    .list-group-item:hover, .list-group-item.active {
      background-color: #495057 !important;
      color: #fff;
    }
    .navbar {
      background: linear-gradient(90deg, #495057 60%, #6c757d 100%);
    }
    .navbar-title {
      color: #fff;
      font-family: 'Montserrat', sans-serif;
      font-size: 1.8rem;
    }
    .bookings-header {
      font-size: 2rem;
      font-weight: 700;
      color: #198754;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .table th, .table td {
      font-size: 0.95rem;
    }
    .action-buttons .btn {
      margin-right: 6px;
    }
  </style>
</head>
<body>

<div class="d-flex" id="wrapper">
  <!-- Sidebar -->
  <div id="sidebar-wrapper">
    <div class="sidebar-heading">
      <img src="image/carrie-hotel-main.png" alt="Logo">
      Hotel Admin
    </div>
    <div class="list-group list-group-flush" id="sidebar-menu">
      <a data-bs-toggle="tab" data-bs-target="#bookings" class="list-group-item list-group-item-action active">
        <i class="bi bi-calendar2-check"></i> Bookings
      </a>
      <a data-bs-toggle="tab" data-bs-target="#request-bookings" class="list-group-item list-group-item-action">
        <i class="bi bi-envelope-paper"></i> Request Booking
      </a>
      <a data-bs-toggle="tab" data-bs-target="#rooms" class="list-group-item list-group-item-action">
        <i class="bi bi-door-closed"></i> Rooms
      </a>
      <a data-bs-toggle="tab" data-bs-target="#customers" class="list-group-item list-group-item-action">
        <i class="bi bi-people"></i> Customers
      </a>
      <a href="#" class="list-group-item list-group-item-action" id="logout-tab">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div id="page-content-wrapper">
    <nav class="navbar navbar-expand-lg border-bottom">
      <div class="container-fluid">
        <span class="navbar-title">Hotel Management System</span>
      </div>
    </nav>

    <div class="container-fluid mt-4">
      <div class="tab-content">

        <!-- Bookings Tab -->
        <div class="tab-pane fade show active" id="bookings">
          <div class="bookings-header"><i class="bi bi-calendar2-check"></i> Bookings</div>
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>ID</th><th>Customer</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>BK001</td><td>Juan Dela Cruz</td><td>101</td><td>2025-08-20</td><td>2025-08-25</td><td><span class="badge bg-success">Confirmed</span></td></tr>
              <tr><td>BK002</td><td>Maria Santos</td><td>102</td><td>2025-08-22</td><td>2025-08-24</td><td><span class="badge bg-warning text-dark">Pending</span></td></tr>
            </tbody>
          </table>
        </div>

        <!-- Request Booking Tab -->
        <div class="tab-pane fade" id="request-bookings">
          <div class="bookings-header"><i class="bi bi-envelope-paper"></i> Request Bookings</div>
          <table class="table table-striped">
            <thead class="table-dark">
              <tr><th>ID</th><th>Customer</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Action</th></tr>
            </thead>
            <tbody>
              <tr>
                <td>RQ001</td><td>Ana Lopez</td><td>103</td><td>2025-09-01</td><td>2025-09-03</td>
                <td class="action-buttons">
                  <button class="btn btn-success btn-sm fs-5" onclick="showModal('accept')"><i class="bi bi-check-circle"></i></button>
                  <button class="btn btn-danger btn-sm fs-5" onclick="showModal('reject')"><i class="bi bi-x-circle"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Rooms Tab -->
        <div class="tab-pane fade" id="rooms">
          <h3>Rooms</h3>
          <p>Room details here...</p>
        </div>

        <!-- Customers Tab -->
        <div class="tab-pane fade" id="customers">
          <h3>Customers</h3>
          <ul class="list-group mt-3">
            <li class="list-group-item">Juan Dela Cruz</li>
            <li class="list-group-item">Maria Santos</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel"><i class="bi bi-question-circle"></i> Confirm Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="modalMessage">Are you sure?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
      </div>
    </div>
  </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel"><i class="bi bi-box-arrow-right"></i> Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Are you sure you want to log out?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <a href="logout.php" class="btn btn-danger">Yes</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Handle tab highlighting and logout modal
  document.querySelectorAll('#sidebar-menu .list-group-item').forEach(link => {
    link.addEventListener('click', function(e) {
      if (this.id === 'logout-tab') {
        e.preventDefault();
        new bootstrap.Modal(document.getElementById('logoutModal')).show();
        return;
      }
      document.querySelectorAll('#sidebar-menu .list-group-item').forEach(l => l.classList.remove('active'));
      this.classList.add('active');
    });
  });

  // Show confirm modal with action
  function showModal(actionType) {
    const message = actionType === 'accept' ? 'Are you sure you want to accept this booking?' : 'Are you sure you want to reject this booking?';
    document.getElementById('modalMessage').textContent = message;
    new bootstrap.Modal(document.getElementById('confirmModal')).show();
  }
</script>
</body>
</html>

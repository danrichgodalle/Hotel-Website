<?php
// No PHP here because main page mostly HTML + JS. Backend is in rooms-crud.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
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
      vertical-align: middle;
    }
    .action-buttons .btn {
      margin-right: 6px;
    }
    /* Image thumbnail */
    #currentRoomImage {
      max-height: 150px;
      display: none;
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
          <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#roomModal" onclick="openAddRoom()">Add New Room</button>

          <table class="table table-bordered align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Amenities</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="roomsTableBody">
              <!-- Rooms data will be populated here -->
            </tbody>
          </table>
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

<!-- Confirm Modal (generic) -->
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

<!-- Room Add/Edit Modal -->
<div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="roomForm" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="roomModalLabel">Add/Edit Room</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="roomId" name="id" />
          <div class="mb-3">
            <label for="roomName" class="form-label">Room Name</label>
            <input type="text" class="form-control" id="roomName" name="name" required />
          </div>
          <div class="mb-3">
            <label for="roomPrice" class="form-label">Price per Night</label>
            <input type="number" class="form-control" id="roomPrice" name="price" required />
          </div>
          <div class="mb-3">
            <label for="roomAmenities" class="form-label">Amenities</label>
            <input type="text" class="form-control" id="roomAmenities" name="amenities" required />
          </div>
          <div class="mb-3">
            <label for="roomDescription" class="form-label">Description</label>
            <textarea class="form-control" id="roomDescription" name="description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="roomImage" class="form-label">Room Image</label>
            <input type="file" class="form-control" id="roomImage" name="image" accept="image/*" />
            <img id="currentRoomImage" alt="Current Room Image" class="mt-2" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save Room</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel"><i class="bi bi-box-arrow-right"></i> Logout Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>Are you sure you want to log out?</p>
        <div class="d-flex justify-content-center gap-3">
          <form action="logout.php" method="post">
            <button type="submit" class="btn btn-danger">Yes, Logout</button>
          </form>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Utility function to escape quotes for HTML attribute safety in JS
function escapeQuotes(str) {
  if (!str) return '';
  return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
}

// Sample hardcoded rooms data for demo (replace with your actual AJAX call)
let rooms = [
  {
    id: 101,
    name: 'Deluxe Suite',
    price: 3500,
    amenities: 'WiFi, AC, TV, Mini Bar',
    description: 'Spacious deluxe room with sea view',
    image: 'image/room1.jpg'
  },
  {
    id: 102,
    name: 'Standard Room',
    price: 1800,
    amenities: 'WiFi, AC, TV',
    description: 'Comfortable room for two persons',
    image: 'image/room2.jpg'
  }
];

function loadRooms() {
  const tbody = document.getElementById('roomsTableBody');
  tbody.innerHTML = '';
  rooms.forEach(room => {
    tbody.innerHTML += `
      <tr>
        <td>${room.id}</td>
        <td>${room.name}</td>
        <td>₱${room.price}</td>
        <td>${room.amenities}</td>
        <td>${room.description}</td>
        <td>${room.image ? `<img src="${room.image}" style="max-height:80px;" alt="Room Image" />` : 'No image'}</td>
        <td class="action-buttons">
          <button class="btn btn-warning btn-sm" onclick="openEditRoom(${room.id}, '${escapeQuotes(room.name)}', ${room.price}, '${escapeQuotes(room.amenities)}', '${escapeQuotes(room.description)}', '${room.image}')">
            <i class="bi bi-pencil"></i>
          </button>
          <button class="btn btn-danger btn-sm" onclick="deleteRoom(${room.id})">
            <i class="bi bi-trash"></i>
          </button>
        </td>
      </tr>
    `;
  });
}

function openAddRoom() {
  document.getElementById('roomModalLabel').textContent = 'Add New Room';
  document.getElementById('roomForm').reset();
  document.getElementById('roomId').value = '';
  document.getElementById('currentRoomImage').style.display = 'none';
  const roomModal = new bootstrap.Modal(document.getElementById('roomModal'));
  roomModal.show();
}

function openEditRoom(id, name, price, amenities, description, image) {
  document.getElementById('roomModalLabel').textContent = 'Edit Room';
  document.getElementById('roomId').value = id;
  document.getElementById('roomName').value = name;
  document.getElementById('roomPrice').value = price;
  document.getElementById('roomAmenities').value = amenities;
  document.getElementById('roomDescription').value = description;

  const currentImage = document.getElementById('currentRoomImage');
  if (image) {
    currentImage.src = image;
    currentImage.style.display = 'block';
  } else {
    currentImage.style.display = 'none';
  }

  const roomModal = new bootstrap.Modal(document.getElementById('roomModal'));
  roomModal.show();
}

function deleteRoom(id) {
  const room = rooms.find(r => r.id === id);
  if (!room) return;

  const confirmModalEl = document.getElementById('confirmModal');
  const confirmModal = new bootstrap.Modal(confirmModalEl);
  document.getElementById('confirmModalLabel').textContent = 'Delete Room';
  document.getElementById('modalMessage').textContent = `Are you sure you want to delete room "${room.name}"?`;

  // Override confirm button action
  const confirmBtn = confirmModalEl.querySelector('.btn-primary');
  confirmBtn.onclick = () => {
    rooms = rooms.filter(r => r.id !== id);
    loadRooms();
    confirmModal.hide();
    alert(`Room "${room.name}" deleted.`);
  };

  confirmModal.show();
}

// Save room form submit
document.getElementById('roomForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const idInput = document.getElementById('roomId').value;
  const name = document.getElementById('roomName').value.trim();
  const price = Number(document.getElementById('roomPrice').value);
  const amenities = document.getElementById('roomAmenities').value.trim();
  const description = document.getElementById('roomDescription').value.trim();
  const imageInput = document.getElementById('roomImage');

  if (!name || !price || !amenities || !description) {
    alert('Please fill all required fields.');
    return;
  }

  // For demo, image upload not handled, just keep existing image or new image name
  let image = '';
  if (imageInput.files.length > 0) {
    // For demo: just fake image path with filename
    image = 'image/' + imageInput.files[0].name;
  } else if (idInput) {
    // Editing: keep existing image if no new image uploaded
    const room = rooms.find(r => r.id == idInput);
    if (room) image = room.image;
  }

  if (idInput) {
    // Edit existing
    let room = rooms.find(r => r.id == idInput);
    if (room) {
      room.name = name;
      room.price = price;
      room.amenities = amenities;
      room.description = description;
      room.image = image;
    }
  } else {
    // Add new - generate new id
    const newId = Math.max(0, ...rooms.map(r => r.id)) + 1;
    rooms.push({id: newId, name, price, amenities, description, image});
  }

  loadRooms();

  const roomModalEl = document.getElementById('roomModal');
  const roomModal = bootstrap.Modal.getInstance(roomModalEl);
  roomModal.hide();

  alert('Room saved successfully!');
});

// Logout modal trigger
document.getElementById('logout-tab').addEventListener('click', e => {
  e.preventDefault();
  const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
  logoutModal.show();

  document.getElementById('confirmLogoutBtn').onclick = () => {
    logoutModal.hide();
    alert('Logged out!');
    // window.location.href = 'logout.php'; // Change to your logout URL
  };
});

// Initialize rooms on page load
window.onload = () => {
  loadRooms();

  // Activate sidebar first tab (Bookings) on load
  const firstTab = document.querySelector('#sidebar-menu a.list-group-item-action.active');
  if (firstTab) {
    const target = firstTab.getAttribute('data-bs-target');
    if (target) {
      const tab = document.querySelector(target);
      if (tab) tab.classList.add('show', 'active');
    }
  }
};
</script>
</body>
</html>

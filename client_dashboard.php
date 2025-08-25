<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Example booked dates, dapat galing sa DB sa real app
$bookedDates = [
    "2025-09-10",
    "2025-09-11",
    "2025-09-15",
    "2025-09-20",
];

// Convert booked dates array to JSON for JavaScript
$bookedDatesJSON = json_encode($bookedDates);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Client Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f5f7fa;
        }

        .navbar .nav-link {
            font-size: 18px;
            font-weight: 500;
            color: rgba(255,255,255,0.85);
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .navbar .nav-link:hover {
            color: #ffc107;
            text-decoration: underline;
        }

        .navbar-text {
            font-size: 18px;
            margin-right: 10px;
            color: #fff;
            user-select: none;
        }

        .btn-sm {
            font-size: 16px;
            padding: 6px 12px;
        }

        .room-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .room-card img {
            height: 300px;
            object-fit: cover;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 25px;
            font-weight: 600;
            font-style: italic;
            color: #000000cc;
        }

        .card-text {
            font-size: 18px;
            flex-grow: 1;
        }

        .amenities {
            font-size: 18px;
            font-style: italic;
            margin-bottom: 10px;
        }

        .amenity-icon {
            margin-right: 6px;
            color: #198754;
        }

        .see-more {
            cursor: pointer;
            font-size: 18px;
            color: #0d6efd;
            font-weight: 500;
            user-select: none;
        }

        .filter-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn-book {
            margin-top: auto;
            font-size: 20px;
            font-weight: 600;
        }

        /* Flatpickr inline calendar in modal */
        #datePicker {
            width: 100%;
        }
        
        /* Modal calendar and image container */
        .modal-body {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            align-items: flex-start;
        }
        
        .calendar-container {
            flex: 1 1 300px;
            max-width: 400px;
        }
        
        .calendar-image {
            flex: 1 1 200px;
            max-width: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .calendar-image img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 576px) {
            .modal-body {
                flex-direction: column;
            }
            .calendar-image, .calendar-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">Caree Hotel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-3">
                <a href="my_bookings.php" class="nav-link">My Booking</a>
            </li>
            <!-- Check Dates nav item with icon -->
            <li class="nav-item me-3">
                <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#checkDatesModal">
                    <i class="bi bi-calendar-check"></i> Check Dates
                </a>
            </li>

            <li class="nav-item navbar-text text-white me-3">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
            </li>
            <li class="nav-item">
                <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Container -->
<div class="container mt-4">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <h2 class="text-center mb-4">Available Rooms</h2>

    <!-- Filter Form -->
    <div class="filter-section mb-4">
        <form id="filterForm" class="row gy-2 gx-3 align-items-center">
            <div class="col-sm-4">
                <label>Minimum Capacity</label>
                <input type="number" class="form-control" id="minCapacity" placeholder="e.g. 1" />
            </div>
            <div class="col-sm-4">
                <label>Maximum Price (₱)</label>
                <input type="number" class="form-control" id="maxPrice" placeholder="e.g. 5000" />
            </div>
            <div class="col-sm-4 d-grid">
                <button type="button" class="btn btn-primary mt-4" onclick="applyFilter()">Apply Filter</button>
            </div>
        </form>
    </div>

    <!-- Room List -->
    <div class="row" id="roomList">
        <?php
        $rooms = [
            ["Family Room", "Experience luxury with our deluxe rooms, featuring modern amenities and stunning views.", "image/carrie1.jpg", 2, 1300, ["Wi-Fi", "Smart TV", "Air Conditioner", "Mini Fridge", "Hair Dryer"]],
            ["Standard Room", "Enjoy comfort and style in our superior rooms, perfect for business or leisure stays.", "image/standard-room.jpg", 2, 1700, ["Wi-Fi", "Flat TV", "Air Conditioner"]],
            ["Premium Room", "Indulge in our spacious suites, offering premium amenities and exceptional comfort.", "image/premium-room.png", 4, 2500, ["Wi-Fi", "Bathtub", "Air Conditioner", "Jacuzzi", "Balcony"]],
          ["Family Room", "Experience luxury with our deluxe rooms, featuring modern amenities and stunning views.", "image/carrie1.jpg", 2, 1300, ["Wi-Fi", "Smart TV", "Air Conditioner", "Mini Fridge", "Hair Dryer"]],
            ["Standard Room", "Enjoy comfort and style in our superior rooms, perfect for business or leisure stays.", "image/standard-room.jpg", 2, 1700, ["Wi-Fi", "Flat TV", "Air Conditioner"]],
            ["Premium Room", "Indulge in our spacious suites, offering premium amenities and exceptional comfort.", "image/premium-room.png", 4, 2500, ["Wi-Fi", "Bathtub", "Air Conditioner", "Jacuzzi", "Balcony"]],
        ];

        $iconMap = [
            "Wi-Fi" => "bi-wifi",
            "Smart TV" => "bi-tv",
            "Flat TV" => "bi-tv",
            "TV" => "bi-tv",
            "Air Conditioner" => "bi-snow",
            "Mini Fridge" => "bi-cup-straw",
            "Hair Dryer" => "bi-wind",
            "Bathtub" => "bi-droplet",
            "Jacuzzi" => "bi-water",
            "Balcony" => "bi-house",
            "Mini Kitchen" => "bi-cup-hot",
            "Microwave" => "bi-lightning-charge",
            "Work Desk" => "bi-laptop",
            "Coffee Maker" => "bi-cup-hot"
        ];

        foreach ($rooms as $room):
            list($title, $desc, $img, $capacity, $price, $amenities) = $room;
        ?>
        <div class="col-md-4 mb-4 room-item" data-capacity="<?= $capacity; ?>" data-price="<?= $price; ?>">
            <div class="card room-card shadow-sm">
                <img src="<?= $img; ?>" class="card-img-top" alt="<?= $title; ?>" />
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= $title; ?></h5>
                    <p class="card-text"><?= $desc; ?></p>
                    <p><strong>Capacity:</strong> <?= $capacity; ?> person(s)</p>
                    <p><strong>Price:</strong> ₱<?= number_format($price); ?>/night</p>

                    <div class="amenities">
                        <strong>Amenities:</strong><br />
                        <?php
                        foreach (array_slice($amenities, 0, 3) as $a):
                            $icon = $iconMap[$a] ?? 'bi-star';
                            echo "<i class='bi $icon amenity-icon'></i> $a<br />";
                        endforeach;
                        ?>
                        <?php if (count($amenities) > 3): ?>
                            <div id="more-<?= md5($title); ?>" class="collapse mt-2" style="display:none;">
                                <?php
                                foreach (array_slice($amenities, 3) as $a):
                                    $icon = $iconMap[$a] ?? 'bi-star';
                                    echo "<i class='bi $icon amenity-icon'></i> $a<br />";
                                endforeach;
                                ?>
                            </div>
                            <span class="see-more" onclick="toggleAmenities('<?= md5($title); ?>')" aria-expanded="false">See More</span>
                        <?php endif; ?>
                    </div>

                    <a href="book.php?room=<?= urlencode($title); ?>" class="btn btn-success mt-auto btn-book">Book Now</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- View More Rooms Button -->
    <div class="text-center mb-5">
        <button class="btn btn-outline-primary" id="viewMoreBtn" onclick="viewMoreRooms()">View More Rooms</button>
    </div>
</div>

<!-- Check Dates Modal -->
<div class="modal fade" id="checkDatesModal" tabindex="-1" aria-labelledby="checkDatesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title d-flex align-items-center gap-2" id="checkDatesModalLabel">
          <i class="bi bi-calendar-event-fill"></i> Check Reserved & Available Dates
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="calendar-container">
            <input type="text" id="datePicker" class="form-control" placeholder="Select a date" readonly>
            <small class="text-muted mt-2 d-block">Booked dates are disabled.</small>
        </div>
        <div class="calendar-image">
            <img src="image/carrie-hotel-main.png" alt="Superior Room" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    function toggleAmenities(id) {
        const moreDiv = document.getElementById('more-' + id);
        const seeMoreSpan = moreDiv.nextElementSibling;
        if (moreDiv.style.display === 'none' || moreDiv.style.display === '') {
            moreDiv.style.display = 'block';
            seeMoreSpan.textContent = 'See Less';
            seeMoreSpan.setAttribute('aria-expanded', 'true');
        } else {
            moreDiv.style.display = 'none';
            seeMoreSpan.textContent = 'See More';
            seeMoreSpan.setAttribute('aria-expanded', 'false');
        }
    }

    function applyFilter() {
        const minCap = parseInt(document.getElementById('minCapacity').value) || 0;
        const maxPrice = parseInt(document.getElementById('maxPrice').value) || Number.MAX_SAFE_INTEGER;
        const rooms = document.querySelectorAll('.room-item');
        let anyVisible = false;

        rooms.forEach(room => {
            const cap = parseInt(room.getAttribute('data-capacity'));
            const price = parseInt(room.getAttribute('data-price'));

            if (cap >= minCap && price <= maxPrice) {
                room.style.display = '';
                anyVisible = true;
            } else {
                room.style.display = 'none';
            }
        });

        if (!anyVisible) {
            alert('No rooms found matching your criteria.');
        }
    }

    // Show 3 rooms initially, view more shows all
    let roomsShown = 3;
    function viewMoreRooms() {
        const rooms = document.querySelectorAll('.room-item');
        rooms.forEach(room => room.style.display = '');
        document.getElementById('viewMoreBtn').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initially show only 3 rooms
        const rooms = document.querySelectorAll('.room-item');
        rooms.forEach((room, idx) => {
            if (idx >= roomsShown) {
                room.style.display = 'none';
            }
        });

        // Initialize Flatpickr on modal input
        const bookedDates = <?= $bookedDatesJSON ?>;
        flatpickr("#datePicker", {
            inline: true,
            disable: bookedDates,
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr, instance) {
                console.log("Selected date:", dateStr);
            }
        });
    });
</script>
</body>
</html>

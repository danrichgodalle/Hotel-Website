<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Client Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f5f7fa;
        }

        .navbar .nav-link {
            font-size: 18px;
            font-weight: 500;
        }

        .navbar-text {
            font-size: 18px;
            margin-right: 10px;
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
            height: 200px;
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
            color: #000000ff;
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
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">Carrie Hotel</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-3">
                <a href="my_bookings.php" class="nav-link">My Booking</a>
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
            ["Deluxe Room", "Experience luxury with our deluxe rooms, featuring modern amenities and stunning views.", "image/carrie1.jpg", 2, 3500, ["Wi-Fi", "Smart TV", "Air Conditioner", "Mini Fridge", "Hair Dryer"]],
            ["Superior Room", "Enjoy comfort and style in our superior rooms, perfect for business or leisure stays.", "image/carrie-hotel-main.png", 2, 3000, ["Wi-Fi", "Flat TV", "Air Conditioner"]],
            ["Suite Room", "Indulge in our spacious suites, offering premium amenities and exceptional comfort.", "image/carrie-hotel-main.png", 4, 3000, ["Wi-Fi", "Bathtub", "Air Conditioner", "Jacuzzi", "Balcony"]],
            ["Family Room", "Perfect for families, our family rooms provide ample space and convenience for everyone.", "image/carrie-hotel-main.png", 5, 1500, ["Wi-Fi", "TV", "Mini Kitchen", "Microwave"]],
            ["Single Room", "Ideal for solo travelers, our single rooms offer comfort and privacy at great value.", "image/carrie-hotel-main.png", 1, 2000, ["Wi-Fi", "TV"]],
            ["Executive Room", "Upgrade your stay in our executive rooms, designed for luxury and productivity.", "image/carrie-hotel-main.png", 2, 2500, ["Wi-Fi", "Work Desk", "Air Conditioner", "Coffee Maker"]],
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
<script>
    function toggleAmenities(id) {
        const moreDiv = document.getElementById('more-' + id);
        const seeMoreSpan = moreDiv.nextElementSibling || document.querySelector(`.see-more[onclick*="${id}"]`);

        if (moreDiv.classList.contains('show')) {
            moreDiv.classList.remove('show');
            moreDiv.style.display = 'none';
            seeMoreSpan.textContent = 'See More';
            seeMoreSpan.setAttribute('aria-expanded', 'false');
        } else {
            moreDiv.classList.add('show');
            moreDiv.style.display = 'block';
            seeMoreSpan.textContent = 'See Less';
            seeMoreSpan.setAttribute('aria-expanded', 'true');
        }
    }

    function applyFilter() {
        const minCapacity = parseInt(document.getElementById('minCapacity').value) || 0;
        const maxPrice = parseInt(document.getElementById('maxPrice').value) || Infinity;

        const rooms = document.querySelectorAll('.room-item');

        rooms.forEach(room => {
            const capacity = parseInt(room.getAttribute('data-capacity'));
            const price = parseInt(room.getAttribute('data-price'));

            if (capacity >= minCapacity && price <= maxPrice) {
                room.style.display = '';
            } else {
                room.style.display = 'none';
            }
        });
    }

    function viewMoreRooms() {
        const roomList = document.getElementById('roomList');
        const currentRooms = Array.from(roomList.children);
        currentRooms.forEach(room => {
            const clone = room.cloneNode(true);
            roomList.appendChild(clone);
        });
        document.getElementById('viewMoreBtn').disabled = true;
        document.getElementById('viewMoreBtn').textContent = "No More Rooms";
    }
</script>
</body>
</html>

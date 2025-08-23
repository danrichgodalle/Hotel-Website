<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
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
            font-size: 1.25rem;
            font-weight: 600;
            color: #0d6efd;
        }

        .card-text {
            font-size: 0.95rem;
            flex-grow: 1;
        }

        .amenities {
            font-size: 0.95rem;
            margin-bottom: 10px;
        }

        .amenity-icon {
            margin-right: 6px;
            color: #198754;
        }

        .see-more {
            cursor: pointer;
            font-size: 0.875rem;
            color: #0d6efd;
            font-weight: 500;
        }

        .filter-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn-book {
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">Carrie Hotel</a>
        <div class="ms-auto">
            <span class="text-white me-3">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">

        <!-- Success Message -->
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
                    <input type="number" class="form-control" id="minCapacity" placeholder="e.g. 1">
                </div>
                <div class="col-sm-4">
                    <label>Maximum Price (₱)</label>
                    <input type="number" class="form-control" id="maxPrice" placeholder="e.g. 5000">
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
                ["Deluxe Room", "Experience luxury with our deluxe rooms, featuring modern amenities and stunning views.", "image/room1.jpg", 2, 3500, ["Wi-Fi", "Smart TV", "Air Conditioner", "Mini Fridge", "Hair Dryer"]],
                ["Superior Room", "Enjoy comfort and style in our superior rooms, perfect for business or leisure stays.", "image/room2.jpg", 2, 3000, ["Wi-Fi", "Flat TV", "Air Conditioner"]],
                ["Suite Room", "Indulge in our spacious suites, offering premium amenities and exceptional comfort.", "image/room3.jpg", 4, 6000, ["Wi-Fi", "Bathtub", "Air Conditioner", "Jacuzzi", "Balcony"]],
                ["Family Room", "Perfect for families, our family rooms provide ample space and convenience for everyone.", "image/room4.jpg", 5, 4500, ["Wi-Fi", "TV", "Mini Kitchen", "Microwave"]],
                ["Single Room", "Ideal for solo travelers, our single rooms offer comfort and privacy at great value.", "image/room5.jpg", 1, 2000, ["Wi-Fi", "TV"]],
                ["Executive Room", "Upgrade your stay in our executive rooms, designed for luxury and productivity.", "image/room6.jpg", 2, 5000, ["Wi-Fi", "Work Desk", "Air Conditioner", "Coffee Maker"]],
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
                    <img src="<?= $img; ?>" class="card-img-top" alt="<?= $title; ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $title; ?></h5>
                        <p class="card-text"><?= $desc; ?></p>
                        <p><strong>Capacity:</strong> <?= $capacity; ?> person(s)</p>
                        <p><strong>Price:</strong> ₱<?= number_format($price); ?>/night</p>

                        <div class="amenities">
                            <strong>Amenities:</strong><br>
                            <?php
                            foreach (array_slice($amenities, 0, 3) as $a):
                                $icon = $iconMap[$a] ?? 'bi-star';
                                echo "<i class='bi $icon amenity-icon'></i> $a<br>";
                            endforeach;
                            ?>
                            <?php if (count($amenities) > 3): ?>
                                <div id="more-<?= md5($title); ?>" class="collapse mt-2">
                                    <?php
                                    foreach (array_slice($amenities, 3) as $a):
                                        $icon = $iconMap[$a] ?? 'bi-star';
                                        echo "<i class='bi $icon amenity-icon'></i> $a<br>";
                                    endforeach;
                                    ?>
                                </div>
                                <span class="see-more" data-bs-toggle="collapse" data-bs-target="#more-<?= md5($title); ?>">See More</span>
                            <?php endif; ?>
                        </div>

                        <a href="book.php?room=<?= urlencode($title); ?>" class="btn btn-success mt-auto btn-book">Book Now</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <hr>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
</body>
</html>


<!--css
        .banner-container::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4); /* ⬅ adjust darkness here */
    z-index: 1;
}

.banner-title {
    position: absolute;
    top: 50%;
    left: 50%;
    font-style: italic;
    transform: translate(-50%, -50%);
    color: #fff;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
    text-align: center;
    width: 90%;
    z-index: 2; /* ⬅ on top of overlay */
}
-->



<!-- Page Heading -->
<div class="container-fluid-head">
  <h1 class="our-room-head">CONTACT US</h1>
</div>

<!-- Contact Section -->
<div class="contact py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Contact Form -->
      <div class="col-md-6">
        <form id="request" class="main_form">
          <input class="form-control" placeholder="Name" type="text" name="Name">
          <input class="form-control" placeholder="Email" type="email" name="Email">
          <input class="form-control" placeholder="Phone Number" type="text" name="Phone">
          <textarea class="form-control" placeholder="Message" name="Message"></textarea>
          <button type="submit" class="send_btn mt-2">Send</button>
        </form>
      </div>
<!-- Google Map (Fixed Size Like Original) -->
<div class="col-md-6">
  <div class="map_main">
    <div class="map-responsive">
      <iframe
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=Eiffel+Tower+Paris+France"
        width="600"
        height="400"
        frameborder="0"
        style="border:0; width: 100%; height: 600px;"
        allowfullscreen>
      </iframe>
    </div>
  </div>
</div>
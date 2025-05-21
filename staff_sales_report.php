<?php
include 'includes/db.php';

$sql = "SELECT 
            ir.report_date, 
            ir.damage_description, 
            ir.image_path,
            sv.variety_name,
            si.seed_name,
            ir.damaged_quantity
        FROM inventory_reports ir
        JOIN planting_plot pp ON ir.plot_id = pp.plot_id
        JOIN seedling_variety sv ON pp.seedling_variety_id = sv.id
        JOIN seedling_info si ON sv.seed_id = si.id
        ORDER BY ir.report_date DESC";
$result = $conn->query($sql);

$reports = [];
$imageCount = 0; 

while ($row = $result->fetch_assoc()) {
    $key = $row['report_date'] . '|' . $row['damage_description'];

    if (!isset($reports[$key])) {
        $reports[$key] = [
            'report_date' => $row['report_date'],
            'damage_description' => $row['damage_description'],
            'image_paths' => [],
            'seedlings' => []
        ];
    }

    $images = explode(',', $row['image_path']);
    $reports[$key]['image_paths'] = array_merge($reports[$key]['image_paths'], $images);
    $imageCount += count($images);

    $seedKey = $row['seed_name'] . ' - ' . $row['variety_name'];
    if (!isset($reports[$key]['seedlings'][$seedKey])) {
        $reports[$key]['seedlings'][$seedKey] = 0;
    }
    $reports[$key]['seedlings'][$seedKey] += $row['damaged_quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
    <style>
        .sales-damage-card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
            background-color: #fff;
        }
        .sales-damage-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .sales-damage-body {
            padding: 15px;
        }
        .sales-damage-date {
            font-size: 0.9rem;
            color: #888;
        }
        .sales-damage-view-icon {
            cursor: pointer;
            color: #007bff;
            font-size: 1.2rem;
        }
        .sales-damage-modal-body {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 10px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .sales-damage-modal-img {
            width: 100%;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .sales-damage-modal-header .sales-damage-image-count {
            font-size: 1.1rem;
            color: #333;
            margin-left: auto;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg border-bottom">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary">Plant Nursery</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="staff_sales_plot.php">Plots</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="staff_sales_inventory.php">Inventory</a>
        </li>
        <!-- <li class="nav-item">
                    <a class="nav-link" href="staff_sales_fertilizer.php">Fertilizer</a>
                </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="scheduleDropdown" data-bs-toggle="dropdown">
            Orders
          </a>
          <ul class="dropdown-menu" aria-labelledby="scheduleDropdown">
            <li><a class="dropdown-item" href="staff_sales_orders.php">Add Order</a></li>
            <li><a class="dropdown-item" href="staff_sales_reserve_orders.php">Reserve Order</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <!-- <a class="nav-link active" href="staff_sales_report.php">Reports</a> -->
          <li class="nav-item"><a class="nav-link" href="staff_login.php"><i class="fas fa-sign-out-alt"></i></a></li>
        </li>
      </ul>
    </div>
  </div>
</nav>

    <div class="container my-5">
        <h3 class="mb-4 text-center">Damage Reports</h3>
        <div class="row">
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $report): ?>
                    <div class="col-md-4">
                        <div class="sales-damage-card">
                            <div class="sales-damage-body">
                                <p class="sales-damage-date mb-1">
                                    <i class="fas fa-calendar-alt"></i> <?= date("F j, Y g:i A", strtotime($report['report_date'])) ?>
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-box-open"></i> <strong>Damaged Seedlings:</strong>
                                </p>
                                <ul class="ps-3">
                                    <?php foreach ($report['seedlings'] as $name => $qty): ?>
                                        <li><?= htmlspecialchars($name) ?> (<?= $qty ?>)</li>
                                    <?php endforeach; ?>
                                </ul>
                                <p class="mb-0">
                                    <strong>Description:</strong><br><?= nl2br(htmlspecialchars($report['damage_description'])) ?>
                                </p>
                                <p class="mt-2">
                                    <i class="fas fa-image"></i> 
                                    <span class="sales-damage-view-icon" 
                                          data-bs-toggle="modal" 
                                          data-bs-target="#salesDamageViewImagesModal" 
                                          data-images="<?= implode(',', $report['image_paths']) ?>" 
                                          data-image-count="<?= count($report['image_paths']) ?>">
                                        View Images
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No damage reports found.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="salesDamageViewImagesModal" tabindex="-1" aria-labelledby="salesDamageViewImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header sales-damage-modal-header">
                    <h5 class="modal-title" id="salesDamageViewImagesModalLabel">Damage Images</h5>
                    <span class="sales-damage-image-count"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body sales-damage-modal-body" id="salesDamageImageGallery"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('salesDamageViewImagesModal').addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var images = button.getAttribute('data-images').split(',');
            var imageCount = button.getAttribute('data-image-count');

            document.querySelector('.sales-damage-image-count').textContent = `Total Images: ${imageCount}`;

            var gallery = document.getElementById('salesDamageImageGallery');
            gallery.innerHTML = '';

            images.forEach(function(image) {
                var imgElement = document.createElement('img');
                imgElement.src = image;
                imgElement.classList.add('img-fluid', 'sales-damage-modal-img', 'mb-2');
                gallery.appendChild(imgElement);
            });
        });
    </script>
    <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

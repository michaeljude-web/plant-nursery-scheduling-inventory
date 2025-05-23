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

while ($row = $result->fetch_assoc()) {
    $key = $row['report_date'] . '|' . $row['damage_description'] . '|' . $row['image_path'];
    
    if (!isset($reports[$key])) {
        $reports[$key] = [
            'report_date' => $row['report_date'],
            'damage_description' => $row['damage_description'],
            'image_path' => $row['image_path'],
            'seedlings' => []
        ];
    }

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Damage Report</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <style>
        .report-card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
            background-color: #fff;
        }
        .report-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .report-body {
            padding: 15px;
        }
        .report-date {
            font-size: 0.9rem;
            color: #888;
        }
    </style>
</head>
<body>

<section class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Damage Reports</h2> <hr> <br>
        <div class="row">
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $report): ?>
                    <div class="col-md-4">
                        <div class="report-card">
                            <img src="<?= !empty($report['image_path']) ? $report['image_path'] : 'placeholder.jpg' ?>" alt="Damage Image">
                            <div class="report-body">
                                <p class="report-date mb-1"><i class="fas fa-calendar-alt"></i> <?= date("F j, Y g:i A", strtotime($report['report_date'])) ?></p>
                                
                                <p class="quantity-box mb-2"><i class="fas fa-box-open"></i> <strong>Damaged Seedlings:</strong></p>
                                <ul class="ps-3">
                                    <?php foreach ($report['seedlings'] as $name => $qty): ?>
                                        <li><?= htmlspecialchars($name) ?> (<?= $qty ?>)</li>
                                    <?php endforeach; ?>
                                </ul>

                                <p class="mb-0"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($report['damage_description'])) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No damage reports found.</p>
            <?php endif; ?>
        </div>
        </main>
    </div>
</section>


<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

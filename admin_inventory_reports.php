<?php
include 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$sql = "
    SELECT CONCAT(si.seed_name, ' (', sv.variety_name, ')') AS full_name, 
           SUM(pp.current_quantity) AS quantity, 
           DATE(pp.date_planted) AS date,
           MAX(pp.date_planted) AS full_date,
           'New planted' AS remarks,
           NULL AS damage_description,
           NULL AS image_path
    FROM planting_plot pp
    JOIN seedling_variety sv ON pp.seedling_variety_id = sv.id
    JOIN seedling_info si ON sv.seed_id = si.id
    GROUP BY full_name, DATE(pp.date_planted)

    UNION ALL

    SELECT CONCAT(si.seed_name, ' (', sv.variety_name, ')') AS full_name, 
           -SUM(o.quantity) AS quantity, 
           DATE(o.date_added) AS date,
           MAX(o.date_added) AS full_date,
           'Sold to customer' AS remarks,
           NULL AS damage_description,
           NULL AS image_path
    FROM orders o
    JOIN seedling_variety sv ON o.seedling_variety_id = sv.id
    JOIN seedling_info si ON sv.seed_id = si.id
    WHERE LOWER(o.status) = 'pending'
    GROUP BY full_name, DATE(o.date_added)

    UNION ALL

    SELECT CONCAT(si.seed_name, ' (', sv.variety_name, ')') AS full_name, 
           SUM(si2.quantity) AS quantity,
           DATE(si2.date_added) AS date,
           MAX(si2.date_added) AS full_date,
           'New stock' AS remarks,
           NULL AS damage_description,
           NULL AS image_path
    FROM seedling_inventory si2
    JOIN seedling_variety sv ON si2.seedling_variety_id = sv.id
    JOIN seedling_info si ON sv.seed_id = si.id
    GROUP BY full_name, DATE(si2.date_added)

    UNION ALL

    SELECT 
        CONCAT(si.seed_name, ' (', sv.variety_name, ')') AS full_name,
        -SUM(ir.damaged_quantity) AS quantity,
        DATE(ir.report_date) AS date,
        MAX(ir.report_date) AS full_date,
        'Damage' AS remarks,
        GROUP_CONCAT(DISTINCT ir.damage_description SEPARATOR '\n') AS damage_description,
        GROUP_CONCAT(DISTINCT ir.image_path SEPARATOR ',') AS image_path
    FROM inventory_reports ir
    JOIN planting_plot pp ON ir.plot_id = pp.plot_id
    JOIN seedling_variety sv ON pp.seedling_variety_id = sv.id
    JOIN seedling_info si ON sv.seed_id = si.id
    GROUP BY full_name, DATE(ir.report_date)

    UNION ALL

    SELECT 
        f.fertilizer_name AS full_name,
        SUM(fi.quantity) AS quantity,
        DATE(fi.date_added) AS date,
        MAX(fi.date_added) AS full_date,
        'New Fertilizer Stock' AS remarks,
        NULL AS damage_description,
        NULL AS image_path
    FROM fertilizer_inventory fi
    JOIN seedling_fertilizer f ON fi.fertilizer_id = f.id
    GROUP BY full_name, DATE(fi.date_added)

    UNION ALL

    SELECT 
        f.fertilizer_name AS full_name,
        -SUM(fd.quantity) AS quantity,
        DATE(fd.date_deducted) AS date,
        MAX(fd.date_deducted) AS full_date,
        'Fertilizer Deducted' AS remarks,
        NULL AS damage_description,
        GROUP_CONCAT(DISTINCT fd.image_path SEPARATOR ',') AS image_path
    FROM fertilizer_deductions fd
    JOIN seedling_fertilizer f ON fd.fertilizer_id = f.id
    GROUP BY full_name, DATE(fd.date_deducted)
    
    ORDER BY full_date DESC;  -- Sort by the most recent report date
";


$result = $conn->query($sql);
if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Dashboard</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <style>

        table {
            background-color: white;
        }
        .inventory-report-fullscreen-modal .modal-dialog {
            width: 100%;
            max-width: none;
            height: 100%;
            margin: 0;
        }
        .inventory-report-fullscreen-modal .modal-content {
            height: 100%;
            border: none;
            border-radius: 0;
        }
        .inventory-report-fullscreen-modal .modal-body {
            height: calc(100% - 60px);
            overflow-y: auto;
            background-color: #000;
            color: #fff;
        }
        .inventory-report-fullscreen-modal img {
            max-width: 100%;
            height: auto;
        }
        .inventory-report-fullscreen-modal .modal-header {
            border-bottom: none;
            background-color: #000;
        }
    </style>
</head>
<body>

<section class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Inventory Reports</h2> <hr>
            <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>Seedling Name</th>
                    <th>Quantity</th>
                    <th>Remarks</th>
                    <th>Date</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $quantity = (int)$row['quantity'];
                        $formattedQty = $quantity > 0 ? '+' . $quantity : $quantity;
                        $modalId = "imgModal" . $count;
                ?>
                    <tr>
                      
                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                        <td class="text-center"><?= $formattedQty; ?></td>
                        <td><?= htmlspecialchars($row['remarks']); ?></td>
                        <td><?= date("F d, Y h:i A", strtotime($row['full_date'])); ?></td>
                        <td>
                            <?php if (!empty($row['image_path'])): ?>
                                <button class="btn btn-sm btn-outline-light bg-dark" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">View</button>

                                <!-- Modal -->
                                <div class="modal fade inventory-report-fullscreen-modal" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
                                    <div class="modal-dialog modal-fullscreen">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-white"><?= htmlspecialchars($row['full_name']) ?> - Damage Report</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <div class="container">
                                                    <?php
                                                    $images = explode(',', $row['image_path']);
                                                    foreach ($images as $img) {
                                                        echo '<img src="' . htmlspecialchars($img) . '" class="img-fluid mb-3"><br>';
                                                    }
                                                    ?>
                                                    <?php if (!empty($row['damage_description'])): ?>
                                                        <div class="text-start mt-4 p-3 bg-dark text-white rounded">
                                                            <h6>Damage Description:</h6>
                                                            <p style="white-space: pre-line;"><?= nl2br(htmlspecialchars($row['damage_description'])) ?></p>
                                                        </div>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">No image</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php
                        $count++;
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No report data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
        </main>
    </div>
</section>


<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

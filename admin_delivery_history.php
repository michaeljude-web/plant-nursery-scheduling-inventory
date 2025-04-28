<?php
include 'includes/db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';
$sql = "SELECT 
    ci.customer_id,
    ci.full_name,
    ci.address,
    ci.contact_number,
    GROUP_CONCAT(CONCAT(si.seed_name, ' - ', sv.variety_name, ' x', sfs.quantity) SEPARATOR '<br>') AS seedlings_ordered,
    MAX(sfs.date_added) AS latest_date,
    SUBSTRING_INDEX(GROUP_CONCAT(sfs.status ORDER BY sfs.date_added DESC), ',', 1) AS status,
    SUBSTRING_INDEX(GROUP_CONCAT(sfs.reason ORDER BY sfs.date_added DESC), ',', 1) AS reason
FROM seedling_for_sale sfs
JOIN customer_info ci ON sfs.customer_id = ci.customer_id
JOIN seedling_variety sv ON sfs.seedling_variety_id = sv.id
JOIN seedling_info si ON sv.seed_id = si.id
WHERE sfs.status IN ('Delivered', 'Cancelled')";

        
if ($filter === 'Delivered' || $filter === 'Cancelled') {
    $sql .= " AND sfs.status = '$filter'";
}

$sql .= " GROUP BY ci.customer_id ORDER BY latest_date DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Order History</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>

<section class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Customer Order History</h2>
            <hr>

            <form method="get" class="mb-3">
                <label for="filter" class="form-label me-2 fw-bold">Sort by:</label>
                <select name="filter" id="filter" class="form-select d-inline w-auto" onchange="this.form.submit()">
                    <option value="All" <?= $filter === 'All' ? 'selected' : '' ?>>All</option>
                    <option value="Delivered" <?= $filter === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                    <option value="Cancelled" <?= $filter === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </form>

            <ul class="list-group">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($row['full_name']) ?></strong><br>
                                <small>
                                    <?= date("M d, Y", strtotime($row['latest_date'])) ?> &bullet;
                                    <i class="fa-solid <?= $row['status'] === 'Cancelled' ? 'fa-circle-xmark text-danger' : 'fa-circle-check text-success' ?>"></i>
                                    <span class="<?= $row['status'] === 'Cancelled' ? 'text-danger' : 'text-success' ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </small>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['customer_id'] ?>">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </li>

                        <div class="modal fade" id="viewModal<?= $row['customer_id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" id="printArea<?= $row['customer_id'] ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delivery Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Customer:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
                                        <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact_number']) ?></p>
                                        <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                                        <p><strong>Seedlings Ordered:</strong><br> <?= $row['seedlings_ordered'] ?></p>
                                        <p><strong>Date:</strong> <?= date("M d, Y h:i A", strtotime($row['latest_date'])) ?></p>
                                        <?php 
                                            $statuses = explode(",", $row['statuses']);
                                            $reasons = explode(",", $row['reasons']);
                                            foreach ($statuses as $index => $status): 
                                        ?>
                                            <p><strong>Status:</strong> 
                                                <span class="<?= $row['status'] === 'Delivered' ? 'text-success' : 'text-danger' ?>">
                                                    <?= $row['status'] ?>
                                                </span>
                                                <?php if ($row['status'] === 'Cancelled' && !empty(trim($row['reason']))): ?>
                                                    <br><strong>Reason:</strong> <?= htmlspecialchars($row['reason']) ?>
                                                <?php endif; ?>
                                            </p>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn bg-primary text-white" onclick="printModal('printArea<?= $row['customer_id'] ?>')">
                                            <i class="fa-solid fa-print"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="list-group-item text-center text-muted">No records found.</li>
                <?php endif; ?>
            </ul>
        </main>
    </div>
</section>

<script>
    function printModal(id) {
        const content = document.getElementById(id).innerHTML;
        const win = window.open('', '', 'height=700,width=900');

        win.document.write('<html><head><title>Print</title>');
        win.document.write('<link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">');
        win.document.write(`
            <style>
                body {
                    font-family: Arial, sans-serif;
                    font-size: 30px;
                    color: #000;
                    padding: 20px;
                }

                .modal-header, .modal-footer {
                    display: none !important;
                }

                .print-title {
                    text-align: center;
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 20px;
                }

                @page {
                    margin: 0;
                }
            </style>
        `);
        win.document.write('</head><body>');
        win.document.write('<div class="print-title"><h1>EJ`s Plant Nursery</h1></div>');
        win.document.write('<div class="print-title"><br> </div>');
        win.document.write(content);
        win.document.write('</body></html>');
        win.document.close();

        win.onload = () => win.print();
    }
</script>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

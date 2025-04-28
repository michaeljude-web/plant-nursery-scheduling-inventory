<?php
include 'includes/db.php';

$query = "
    SELECT fs.schedule_id, fs.seedling_id, fs.fertilizer_id, fs.scheduled_date, fs.status,
           sv.variety_name, si.seed_name, sf.fertilizer_name
    FROM fertilizer_schedule fs
    JOIN seedling_variety sv ON fs.seedling_id = sv.id
    JOIN seedling_info si ON sv.seed_id = si.id
    JOIN seedling_fertilizer sf ON fs.fertilizer_id = sf.id
    WHERE fs.status = 'complete' OR (fs.status != 'complete' AND fs.scheduled_date < CURDATE())
    ORDER BY fs.scheduled_date DESC"; 

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

$history = [];
while ($row = mysqli_fetch_assoc($result)) {
    $status = $row['status'] == 'complete' ? 'Complete' : (strtotime($row['scheduled_date']) < strtotime(date('Y-m-d')) && $row['status'] != 'complete' ? 'Missed' : 'Pending');
    
    $insert_history_query = "
        INSERT INTO fertilizer_schedule_history (schedule_id, seedling_id, fertilizer_id, scheduled_date, status, seed_name, fertilizer_name, variety_name)
        VALUES ('{$row['schedule_id']}', '{$row['seedling_id']}', '{$row['fertilizer_id']}', '{$row['scheduled_date']}', 
                '{$status}', '{$row['seed_name']}', '{$row['fertilizer_name']}', '{$row['variety_name']}')
    ";
    mysqli_query($conn, $insert_history_query);

    $history[] = [
        'schedule_id' => $row['schedule_id'],
        'seed_name' => $row['seed_name'],
        'fertilizer_name' => $row['fertilizer_name'],
        'variety_name' => $row['variety_name'],
        'scheduled_date' => $row['scheduled_date'],
        'status' => $status
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8" />
          <title>EJ's Plant Nursery</title>
          <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
          <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
     </head>
     <body class="bg-light">
          <nav class="navbar navbar-expand-lg border-bottom">
               <div class="container">
                    <a class="navbar-brand fw-bold text-primary">Plant Nursery</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                         <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                         <ul class="navbar-nav ms-auto">
                              <li class="nav-item">
                                   <a class="nav-link" href="staff_nutritionist_calendar.php">Calendar</a>
                              </li>
                              <li class="nav-item dropdown">
                                   <a class="nav-link dropdown-toggle active" href="#" id="scheduleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Schedule
                                   </a>
                                   <ul class="dropdown-menu" aria-labelledby="scheduleDropdown">
                                        <li><a class="dropdown-item" href="staff_nutritionist_add_schedule.php">Add Schedule</a></li>
                                        <li><a class="dropdown-item" href="staff_nutritionist_schedule_history.php">Schedule History</a></li>
                                   </ul>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="staff_nutritionist_fertilizer_inventory.php">Inventory</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="staff_nutritionist_fertilizer_deduction.php">Deduction</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="staff_login.php">
                                        <i class="fas fa-sign-out-alt"></i>
                                   </a>
                              </li>
                         </ul>
                    </div>
               </div>
          </nav>

          <div class="container mt-3">
               <h1>Fertilizer Schedule History</h1>
               <hr>
               <table class="table">
                    <thead>
                         <tr>
                              <th scope="col">Seedling Name</th>
                              <th scope="col">Fertilizer Name</th>
                              <th scope="col">Variety</th>
                              <th scope="col">Scheduled Date</th>
                              <th scope="col">Status</th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php foreach ($history as $entry): ?>
                              <tr>
                                   <td><?= $entry['seed_name'] ?></td>
                                   <td><?= $entry['fertilizer_name'] ?></td>
                                   <td><?= $entry['variety_name'] ?></td>
                                   <td><?= date('F j, Y g:i A', strtotime($entry['scheduled_date'])) ?></td>
                                   <td><?= $entry['status'] ?></td>
                              </tr>
                         <?php endforeach; ?>
                    </tbody>
               </table>
          </div>

          <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
     </body>
</html>

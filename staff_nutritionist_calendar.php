<?php
include 'includes/db.php';

$schedules_query = "
    SELECT fs.schedule_id, fs.seedling_id, fs.fertilizer_id, fs.scheduled_date, fs.status,
           sv.variety_name, si.seed_name, sf.fertilizer_name
    FROM fertilizer_schedule fs
    JOIN seedling_variety sv ON fs.seedling_id = sv.id
    JOIN seedling_info si ON sv.seed_id = si.id
    JOIN seedling_fertilizer sf ON fs.fertilizer_id = sf.id
";
$schedule_result = mysqli_query($conn, $schedules_query);

if (!$schedule_result) {
    die('Query failed: ' . mysqli_error($conn));
}

$events = [];
$three_day_alerts = [];
$today = new DateTime();
while ($row = mysqli_fetch_assoc($schedule_result)) {
    $events[] = [
        'id' => $row['schedule_id'],
        'title' => $row['seed_name'] . ' - ' . $row['fertilizer_name'],
        'start' => $row['scheduled_date'],
        'extendedProps' => [
            'seedling' => $row['seed_name'] . ' - ' . $row['variety_name'],
            'fertilizer' => $row['fertilizer_name'],
            'status' => $row['status']
        ],
        'color' => $row['status'] === 'completed' ? '#28a745' : '#dc3545'
    ];

    $scheduleDate = new DateTime($row['scheduled_date']);
    $interval = $today->diff($scheduleDate)->days;
    if ($scheduleDate >= $today && $interval <= 3 && $row['status'] !== 'complete') {
        $three_day_alerts[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_completed'])) {
    $id = $_POST['update_id'];
    mysqli_query($conn, "UPDATE fertilizer_schedule SET status = 'complete' WHERE schedule_id = '$id'");
    echo "<script>window.location.href = window.location.href;</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>EJ's Plant Nursery</title>
        <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/bootstrap-5/js/bootstrap.bundle.min.js" />
        <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <style>
            /* #calendar {
    width: 100%;
    height: 85vh; 
    margin: 0 auto;
    border: 3px solid #333;
    padding: 10px;
    background-color: white;
} */

            #calendar {
                border: 3px solid #333;
                padding: 10px;
            }

            .fc .fc-toolbar {
                color: #000;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 10px;
            }

            .fc .fc-col-header-cell-cushion {
                color: darkblue;
                text-decoration: none;
                font-weight: bold;
                font-size: 2.2rem;
            }

            .fc .fc-col-header-cell.fc-day-sun .fc-col-header-cell-cushion {
                color: red !important;
            }

            .fc .fc-col-header-cell.fc-day-sat .fc-col-header-cell-cushion {
                color: blue !important;
            }

            .fc .fc-daygrid-day-frame {
                height: 30px;
                padding: 5px;
            }

            .fc-daygrid-day {
                border: 1px solid #b0c4de;
            }

            .fc .fc-day-other .fc-daygrid-day-number {
                display: none !important;
            }

            .fc .fc-daygrid-day-number {
                position: relative;
                z-index: 2;
                text-align: center;
                width: 100%;
                display: block;
                font-weight: bold;
                font-size: 3rem;
                color: black !important;
                text-decoration: none !important;
            }

            .fc-day-sun .fc-daygrid-day-number {
                color: red !important;
            }

            .fc-day-sat .fc-daygrid-day-number {
                color: blue !important;
            }

            .fc-daygrid-event-dot {
                display: none !important;
            }

            .fc-toolbar-chunk:nth-child(1) {
                background-color: rgb(31, 38, 173);
                color: white;
                width: 240px;
                text-align: center;
                padding: 20px;
                font-weight: bold;
            }

            .fc-toolbar-chunk:nth-child(2) {
                background-color: rgb(195, 3, 22);
                color: white;
                width: 550px;
                text-align: center;
                font-size: 25px;
                padding: 20px;
                border-radius: 10px;
                font-weight: bold;
            }

            .fc-toolbar-chunk:nth-child(3) {
                background-color: rgb(31, 38, 173);
                color: white;
                width: 250px;
                text-align: center;
                padding: 20px;
                font-weight: bold;
            }

            .fc .fc-button {
                background-color: transparent !important;
                border: none !important;
                box-shadow: none !important;
                font-size: 25px;
                font-weight: bold;
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            }
        </style>
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
                            <a class="nav-link active" href="staff_nutritionist_calendar.php">Calendar</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="scheduleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
            <div id="scheduleAlert" role="alert" style="z-index: 1055; display: none;">
                <?php if (!empty($three_day_alerts)): ?>
                <div id="scheduleAlert" class="alert alert-warning border border-3 border-dark shadow-lg rounded-4 fade show position-fixed top-0 start-50 translate-middle-x mt-3 w-75 text-dark" role="alert" style="z-index: 1055;">
                    <div class="d-flex align-items-center">
                        <div class="me-3 fs-2">📢</div>
                        <i class="fas fa-bullhorn me-2 text-warning"></i>
                        <div>
                            <h5 class="fw-bold mb-1">Upcoming Fertilizer Schedule</h5>
                            <ul class="mb-0 ps-3">
                                <?php foreach ($three_day_alerts as $alert): ?>
                                <li>
                                    <strong><?= $alert['seed_name'] ?></strong> -
                                    <?= $alert['fertilizer_name'] ?>
                                    <em>(<?= date('F j, Y', strtotime($alert['scheduled_date'])) ?>)</em>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" id="exitAlertBtn"></button>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="text-center" style="border: 1px solid black;">
                <h1>AJ's Plant Nursery Calendar System</h1>
                <br />
                <h2>Kablon, Tupi, South Cotabato</h2>
            </div>

            <br />

            <div id="calendar"></div>
        </div>

        <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Fertilizer Schedule Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Seedling:</strong> <span id="modalSeedling"></span></p>
                            <p><strong>Fertilizer:</strong> <span id="modalFertilizer"></span></p>
                            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                            <input type="hidden" name="update_id" id="modalId" />
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="mark_completed" id="markCompletedBtn" class="btn btn-success">Mark as Completed</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>

            document.addEventListener('DOMContentLoaded', function () {

            if (localStorage.getItem('scheduleAlertDismissed') === 'true') {
                const alertBox = document.getElementById('scheduleAlert');
                if (alertBox) alertBox.style.display = 'none';
            }

            <?php if (!empty($three_day_alerts)): ?>
                document.getElementById('scheduleAlert').style.display = 'flex';
            <?php endif; ?>

            document.getElementById('exitAlertBtn')?.addEventListener('click', function () {
                const alertBox = document.getElementById('scheduleAlert');
                if (alertBox) alertBox.style.display = 'none';
                localStorage.setItem('scheduleAlertDismissed', 'true');
            });

            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                fixedWeekCount: false,
                events: <?= json_encode($events) ?>,
                headerToolbar: {
                    left: 'customYearButton',
                    center: 'title',
                    right: 'prev,next'
                },
                titleFormat: { month: 'long' },
                customButtons: {
                    customYearButton: {
                        text: new Date().getFullYear(),
                        click: function () {}
                    }
                },
                eventClick: function (info) {
                    const props = info.event.extendedProps;
                    const scheduledDate = new Date(info.event.start);
                    scheduledDate.setHours(0, 0, 0, 0);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    const diffTime = scheduledDate.getTime() - today.getTime();
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    function showAlert(message) {
                        const alertModal = new bootstrap.Modal(document.createElement('div'));
                        const modalHTML = `
                            <div class="modal fade show" style="display: block;" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-4">
                                            <p>${message}</p>
                                            <button type="button" class="btn btn-secondary mt-2" onclick="this.closest('.modal').remove()">Exit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = modalHTML;
                        document.body.appendChild(tempDiv.firstElementChild);
                    }

                    if (props.status === 'completed') {
                        showAlert("This schedule is already marked as completed and cannot be updated.");
                        return;
                    }

                    if (diffDays < 0) {
                        showAlert("This schedule has already passed and can no longer be updated.");
                        return;
                    }

                    if (diffDays > 0) {
                        showAlert("You can only update the schedule on the scheduled day or later.");
                        return;
                    }

                    document.getElementById('modalSeedling').textContent = props.seedling;
                    document.getElementById('modalFertilizer').textContent = props.fertilizer;
                    document.getElementById('modalStatus').textContent = props.status;
                    document.getElementById('modalId').value = info.event.id;

                    const modal = new bootstrap.Modal(document.getElementById('scheduleModal'));
                    modal.show();
                }
            });

            calendar.render();

            });
        </script>

        <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

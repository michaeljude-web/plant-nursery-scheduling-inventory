<?php
session_start();
include 'includes/db.php';

$nutritionist_id = $_SESSION['nutritionist_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['description'], $_POST['schedule_date'])) {
    $description = $_POST['description'];
    $schedule_date = $_POST['schedule_date'];

    $check_sql = "SELECT * FROM schedules WHERE nutritionist_id = '$nutritionist_id' AND schedule_date = '$schedule_date'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo "You already have a schedule on this date.";
    } else {
        $sql = "INSERT INTO schedules (nutritionist_id, description, schedule_date) VALUES ('$nutritionist_id', '$description', '$schedule_date')";
        echo ($conn->query($sql) === TRUE) ? "Schedule added successfully!" : "Error: " . $conn->error;
    }
    exit;
}

if (isset($_GET['fetch'])) {
    $sql = "SELECT * FROM schedules WHERE nutritionist_id = '$nutritionist_id'";
    $result = $conn->query($sql);
    $schedules = [];

    while ($row = $result->fetch_assoc()) {
        $schedules[] = [
            'id' => $row['id'],
            'title' => $row['description'],
            'start' => $row['schedule_date'],
            'color' => '#28a745'
        ];
    }

    echo json_encode($schedules);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_schedule'])) {
    $schedule_id = $_POST['delete_schedule'];
    $sql = "DELETE FROM schedules WHERE id = '$schedule_id' AND nutritionist_id = '$nutritionist_id'";
    echo ($conn->query($sql) === TRUE) ? "Schedule deleted successfully!" : "Error: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nutritionist | Dashboard</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>

 <nav class="navbar navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-center">
            <div>
            <a class="nav-link d-inline" href="staff_nutritionist_dashboard.php">
    <i class="fa-solid fa-calendar"></i> Calendar
</a> |
<a class="nav-link d-inline" href="staff_nutritionist_team.php">
    <i class="fa-solid fa-users"></i> Team
</a> |
<a class="nav-link d-inline" href="staff_login.php">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
</a>

            </div>
        </div>
    </nav>
 <style>
        a {
            text-decoration: none !important;
        }
        body {
            font-family: Times New Roman;
            background-color: #f4f4f4;
            text-align: center;
        }
        .signage-container {
            width: 81%;
            margin: auto;
            padding: 20px;
            border: 1px solid black;
            position: relative;
            top: 25px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
        }
        .subtitle {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }
        .store-info {
            font-size: 16px;
            margin-top: 10px;
        }
        .location {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .fc-day-sun a {
            color: red !important;
        }
        .fc-day-sat a {
            color: blue !important;
        }
        .fc-day-mon a,
        .fc-day-tue a,
        .fc-day-wed a,
        .fc-day-thu a,
        .fc-day-fri a {
            color: black !important;
        }
        #calendar-container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        #calendar {
            font-family: 'Times New Roman', Times, serif;
            font-size: 20px;
            font-weight: bold;
            padding-top: 2px;
        }
        .fc-daygrid-day-frame {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 10px;
        }
        .fc {
            max-width: 900px;
            margin: auto;
        }
        .fc-daygrid-day-number {
            font-size: 16px;
            font-weight: bold;
            color: black;
            text-align: center;
        }
        .fc-event {
            font-size: 10px;
            background-color: yellow;
            color: black;
            border: none;
            padding: 5px;
            text-align: center;
        }
        .fc-daygrid-day-bottom {
            font-size: 10px;
            color: black;
            text-align: center;
        }


        /** Seond  */
        .fc-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 3px;
    border-radius: 5px;
    position: relative;
    top: 26px;
}

.fc-toolbar-title {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    text-align: center;
    flex-grow: 1;
    padding-left: 130px;
}



.fc-toolbar .fc-left, .fc-toolbar .fc-right {
    flex: 1;
    display: flex;
    justify-content: space-between;
    font-size: 20px;
    font-weight: bold;
}

@media (max-width: 1000px) {
    .fc-toolbar-title {
        font-size: 14px !important;
        padding-left: 0 !important;
    }

    .fc-toolbar .fc-left, .fc-toolbar .fc-right {
        font-size: 10px !important;
        padding: 2px !important;
    }
}


    </style>
</head>
<body>
    <div id="calendar-container">
        <div class="signage-container">
            <h2>Nutritionist Schedule</h2>
            <div class="title">GCHC AGRI BUY & SELL <br> AND FARM SERVICES</div>
            <div class="store-info">CENTERLUCK PETRON STATION <br> & <br> RICAVIV'S CONVENIENCE STORE</div>
            <div class="location">Banga, South Cotabato</div>
        </div>
        <div class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#addScheduleModal"></div>
        <div id="calendar"></div>
    </div>
    <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addScheduleModalLabel">Add Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="scheduleForm">
                        <div class="mb-2">
                            <textarea name="description" class="form-control" placeholder="Enter Schedule Details" required></textarea>
                        </div>
                        <div class="mb-2">
                            <input type="date" name="schedule_date" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    selectable: true,
    events: 'staff_nutritionist_dashboard.php?fetch=true',
    headerToolbar: {
        rigth: 'today prev,next',
        center: 'title',
        left: 'yearDisplay'
    },
    customButtons: {
        yearDisplay: {
            text: new Date().getFullYear(),
            click: function() {}
        }
    },
    eventClick: function (info) {
        if (confirm("Do you want to delete this schedule?")) {
            let formData = new FormData();
            formData.append('delete_schedule', info.event.id);
            fetch('staff_nutritionist_dashboard.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                info.event.remove();
            })
            .catch(error => console.error('Error:', error));
        }
    },
    dateClick: function (info) {
        document.querySelector('[name="schedule_date"]').value = info.dateStr;
        new bootstrap.Modal(document.getElementById('addScheduleModal')).show();
    }
});
calendar.render();

            document.getElementById('scheduleForm').addEventListener('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                fetch('staff_nutritionist_dashboard.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.text()).then(data => {
                    alert(data);
                    location.reload();
                });
                var modal = bootstrap.Modal.getInstance(document.getElementById('addScheduleModal'));
                modal.hide();
            });
        });
        
    </script>
</body>
</html>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

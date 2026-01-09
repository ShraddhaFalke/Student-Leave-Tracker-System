<?php
session_start();
include '../includes/db_connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}





// Fetch student details (optional, if you need more info)
$student_name = $_SESSION['student_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            background: #6f42c1;
            color: white;
            border-radius: 0;          /* square corners */
            padding: 40px 30px;       /* bigger padding */
            transition: background-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
            box-shadow: 0 8px 15px rgba(111, 66, 193, 0.2);
            display: flex;
            align-items: center;
            height: 150px;            /* fixed height for squares */
            justify-content: center;  /* center content horizontally */
            flex-direction: column;   /* icon above text */
            text-align: center;
        }
        .dashboard-card:hover {
            background: #7e57c2;
            transform: translateY(-5px);
            text-decoration: none;
            color: white;
        }
        .dashboard-card i {
            font-size: 50px;
            margin-bottom: 15px;  /* space below icon */
        }
        .dashboard-card span {
            font-size: 1.25rem;
            font-weight: 600;
        }
        h3 {
            color: #4b0082;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3>Welcome, <?php echo htmlspecialchars($student_name); ?> 👋</h3>
    <hr>

    <div class="row">

   

        <div class="col-md-6 mb-4">
            <a href="apply_leave.php" class="dashboard-card text-white d-block">
                <i class="fas fa-file-alt"></i>
                <span>Apply for Leave</span>
            </a>
        </div>
        <div class="col-md-6 mb-4">
            <a href="leave_history.php" class="dashboard-card text-white d-block">
                <i class="fas fa-history"></i>
                <span>View Leave History</span>
            </a>
        </div>
        <div class="col-md-6 mb-4">
            <a href="attendance.php" class="dashboard-card text-white d-block">
                <i class="fas fa-chart-bar"></i>
                <span>View Attendance</span>
            </a>
        </div>

        
        <div class="col-md-6 mb-4">
            <a href="../logout.php" class="dashboard-card text-white d-block" style="background: #a156c7;">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

</body>
</html>

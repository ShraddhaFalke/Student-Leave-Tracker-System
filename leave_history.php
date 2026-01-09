<?php
session_start();
require_once '../includes/db_connection.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch leave history for this student
$query = "SELECT * FROM leaves WHERE student_id = '$student_id' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Leave History</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <style>
    body {
        background-color: #f9f6fc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 32px 6px;
        color: #3e2c70;
    }
    .container {
        max-width: 100%; /* wider for more space */
        margin: auto;
    }
    h2 {
        text-align: center;
        font-weight: 700;
        color: #5e3fa3;
        margin-bottom: 35px;
    }
    .btn-back {
        background-color: #5e3fa3;
        color: white;
        padding: 8px 18px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 30px;
        transition: background-color 0.3s ease;
    }
    .btn-back:hover {
        background-color: #482a7a;
        color: white;
        text-decoration: none;
    }
    table {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgb(94 63 163 / 0.15);
        width: 100%;
        border-collapse: separate;
        border-spacing: 0px 15px; /* less horizontal spacing, keep vertical */
    }
    thead tr {
        background-color: #6a47b2;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        border-radius: 10px;
    }
    thead th {
        padding: 16px 14px; /* reduce horizontal padding */
    }
    tbody tr {
        background-color: #f3edf9;
        border-radius: 10px;
        transition: background-color 0.3s ease;
    }
    tbody tr:hover {
        background-color: #dcd1f9;
    }
    tbody td {
        text-align: center;
        padding: 16px 14px; /* reduce horizontal padding */
        font-size: 0.95rem;
        color: #4b3e7a;
        vertical-align: middle;
        white-space: normal; /* allow wrapping to avoid excessive width */
    }
    .badge {
        font-size: 0.85rem;
        padding: 7px 14px;
        border-radius: 15px;
    }
    .badge.bg-success {
        background-color: #28a745; /* standard green */
        color: white;
    }
    .badge.bg-danger {
        background-color: #dc3545; /* standard red */
        color: white;
    }
    .badge.bg-warning {
        background-color: #ffc107; /* standard yellow */
        color: #212529;
    }
    /* Responsive tweaks */
    @media (max-width: 768px) {
        tbody td, thead th {
            padding: 10px 12px;
            font-size: 0.85rem;
            white-space: normal;
        }
        .btn-back {
            padding: 6px 12px;
            font-size: 0.9rem;
        }
    }
</style>
</head>
<body>
<div class="container">

    <a href="dashboard.php" class="btn-back">&larr; Back to Dashboard</a>

    <h2>My Leave History</h2>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Leave Type</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Reason</th>
                <th>Faculty Status</th>
                <th>HOD Status</th>
                <th>Document</th>
                <th>Address</th>
                <th>Attendance</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                    <td><?= htmlspecialchars($row['leave_type']) ?></td>
                    <td><?= htmlspecialchars($row['from_date']) ?></td>
                    <td><?= htmlspecialchars($row['to_date']) ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <?php
                    // Faculty Status badge
                    $faculty_status = $row['status_faculty'];
                    $faculty_class = ($faculty_status == 'Approved') ? 'success' : (($faculty_status == 'Rejected') ? 'danger' : 'warning');
                    ?>
                    <td><span class="badge bg-<?= $faculty_class ?>"><?= htmlspecialchars($faculty_status) ?></span></td>
                    <?php
                    // HOD Status badge
                    $hod_status = $row['status_hod'];
                    $hod_class = ($hod_status == 'Approved') ? 'success' : (($hod_status == 'Rejected') ? 'danger' : 'warning');
                    ?>
                    <td><span class="badge bg-<?= $hod_class ?>"><?= htmlspecialchars($hod_status) ?></span></td>
                    <td>
                        <?php if (!empty($row['document'])): ?>
                            <a href="../documents/<?= htmlspecialchars($row['document']) ?>" target="_blank" style="color:#5e3fa3; font-weight:600;">View</a>
                        <?php else: ?>
                            No Document
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['attendance']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
            <?php
                endwhile;
            else:
            ?>
            <tr>
                <td colspan="11" style="text-align:center; padding: 20px; color: #7d6fc1; font-weight: 600;">
                    No leave records found.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
session_start();
include '../includes/db_connection.php';

if (!isset($_SESSION['hod_id'])) {
    header("Location: login.php");
    exit();
}

$hod_name = $_SESSION['hod_name'];

// Fetch pending leaves for HoD approval
$sql_pending = "SELECT leaves.id, students.name, students.student_id, leaves.leave_type, leaves.from_date, leaves.to_date, leaves.reason, leaves.document
FROM leaves
JOIN students ON leaves.student_id = students.student_id
WHERE leaves.status_faculty = 'Approved' AND leaves.status_hod = 'Pending'";

$result_pending = mysqli_query($conn, $sql_pending);

// Handle student ID filter for leave history
$filter_student_id = '';
if (isset($_GET['student_id']) && !empty(trim($_GET['student_id']))) {
    $filter_student_id = trim($_GET['student_id']);
    $stmt = $conn->prepare("SELECT leaves.id, students.name, students.student_id, leaves.leave_type, leaves.from_date, leaves.to_date, leaves.reason, leaves.document, leaves.status_faculty, leaves.status_hod
                            FROM leaves
                            JOIN students ON leaves.student_id = students.student_id
                            WHERE students.student_id = ?
                            ORDER BY students.student_id ASC");
    $stmt->bind_param("s", $filter_student_id);
    $stmt->execute();
    $result_history = $stmt->get_result();
} else {
    $result_history = false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HoD Dashboard - Leave Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-clickable {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            cursor: pointer;
            user-select: none;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
            transition: background 0.3s ease;
        }
        .card-clickable:hover {
            background: linear-gradient(135deg, #4b0fb8 0%, #1e57d1 100%);
        }
        .content-section {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem 1.5rem;
            margin-top: 1rem;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .table thead th {
            background-color: #6a11cb;
            color: white;
        }
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        .btn-approve:hover {
            background-color: #218838;
            color: white;
        }
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        .btn-reject:hover {
            background-color: #c82333;
            color: white;
        }
        .filter-form {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-4">Welcome, <?php echo htmlspecialchars($hod_name); ?></h3>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div id="pendingCard" class="card-clickable">
                <h4>Pending Leave Requests</h4>
                <p>Click to view pending leave applications</p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div id="historyCard" class="card-clickable">
                <h4>Leave History</h4>
                <p>Click to view leave history and search by Student ID</p>
            </div>
        </div>
    </div>

    <div id="pendingContent" class="content-section">
        <?php if(mysqli_num_rows($result_pending) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Reason</th>
                            <th>Document</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result_pending)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['from_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['to_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td>
                                <?php if($row['document']): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars(basename($row['document'])); ?>" target="_blank">View</a>
                                <?php else: ?>
                                    No Document
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="leave_action.php" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-approve btn-sm">Approve</button>
                                </form>
                                <form action="leave_action.php" method="POST" style="display:inline-block;" onsubmit="return validateRejectForm(<?php echo $row['id']; ?>);">
                                     <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                                     <button type="button" class="btn btn-reject btn-sm" onclick="toggleReason(<?php echo $row['id']; ?>)">Reject</button>
                                     <div id="reason-box-<?php echo $row['id']; ?>" style="display:none; margin-top: 5px;">
                                        <textarea name="reason" id="reason-text-<?php echo $row['id']; ?>" class="form-control mb-2" placeholder="Enter rejection reason" required></textarea>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Confirm Reject</button>
                                     </div>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No pending leave requests.</p>
        <?php endif; ?>
    </div>

    <div id="historyContent" class="content-section">
        <form method="GET" class="filter-form form-inline">
            <label for="student_id" class="mr-2">Enter Student ID:</label>
            <input type="text" name="student_id" id="student_id" class="form-control mr-2" value="<?php echo htmlspecialchars($filter_student_id); ?>" placeholder="Student ID">
            <button type="submit" class="btn btn-primary">Search</button>
            <button type="button" id="clearFilter" class="btn btn-secondary ml-2">Clear</button>
        </form>

        <?php if ($result_history && mysqli_num_rows($result_history) > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Reason</th>
                            <th>Faculty Status</th>
                            <th>HoD Status</th>
                            <th>Document</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result_history)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['from_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['to_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><?php echo htmlspecialchars($row['status_faculty']); ?></td>
                            <td><?php echo htmlspecialchars($row['status_hod']); ?></td>
                            <td>
                                <?php if($row['document']): ?>
                                    <a href="../uploads/<?php echo htmlspecialchars(basename($row['document'])); ?>" target="_blank">View</a>
                                <?php else: ?>
                                    No Document
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($filter_student_id !== ''): ?>
            <p>No leave records found for student ID "<?php echo htmlspecialchars($filter_student_id); ?>".</p>
        <?php else: ?>
            <p>Please enter a Student ID to view leave history.</p>
        <?php endif; ?>
    </div>

    <a href="../logout.php" class="btn btn-secondary mt-4">Logout</a>
</div>

<script>
    const pendingCard = document.getElementById('pendingCard');
    const historyCard = document.getElementById('historyCard');
    const pendingContent = document.getElementById('pendingContent');
    const historyContent = document.getElementById('historyContent');
    const clearFilterBtn = document.getElementById('clearFilter');

    function toggleSection(sectionToShow, sectionToHide) {
        if (sectionToShow.style.display === 'none' || sectionToShow.style.display === '') {
            sectionToShow.style.display = 'block';
            sectionToHide.style.display = 'none';
        } else {
            sectionToShow.style.display = 'none';
        }
    }

    pendingCard.addEventListener('click', () => {
        toggleSection(pendingContent, historyContent);
    });

    historyCard.addEventListener('click', () => {
        toggleSection(historyContent, pendingContent);
    });

    clearFilterBtn.addEventListener('click', () => {
        document.getElementById('student_id').value = '';
        window.location.href = window.location.pathname;
    });

    <?php if ($filter_student_id !== ''): ?>
        historyContent.style.display = 'block';
    <?php endif; ?>
</script>
<script>
function toggleReason(id) {
    const box = document.getElementById('reason-box-' + id);
    box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
}

function validateRejectForm(id) {
    const reason = document.getElementById('reason-text-' + id).value.trim();
    if (!reason) {
        alert('Please enter a reason for rejection.');
        return false;
    }
    return true;
}
</script>


</body>
</html>

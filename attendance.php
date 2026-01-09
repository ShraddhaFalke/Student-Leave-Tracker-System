<?php
session_start();
include('../includes/db_connection.php'); // Adjust as needed

$student_id = $_SESSION['student_id'] ?? null;

if (!$student_id) {
    echo "Please log in to view your attendance.";
    exit;
}

$sql = "SELECT total_days, present_days, percentage, date_recorded 
        FROM attendance 
        WHERE student_id = ? 
        ORDER BY date_recorded DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Attendance History</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f0fa;
    margin: 40px auto;
    max-width: 900px;
    color: #2e2e2e;
  }
  h2 {
    text-align: center;
    color: #4b0082;
    margin-bottom: 16px;
    font-weight: 700;
  }
  .back-btn {
    display: inline-block;
    margin-bottom: 24px;
    background-color: #6a0dad;
    color: #fff;
    padding: 8px 18px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease;
  }
  .back-btn:hover {
    background-color: #4b007a;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 2px 8px rgba(75, 0, 130, 0.15);
    border-radius: 8px;
    overflow: hidden;
    background: white;
  }
  thead tr {
    background: #6a0dad;
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
  }
  th, td {
    padding: 10px 14px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    font-size: 0.9rem;
  }
  tbody tr:hover {
    background-color: #f2e6ff;
  }
  tbody tr:nth-child(even) {
    background-color: #faf5ff;
  }
  .no-data {
    padding: 20px;
    color: #8b6fc1;
    font-weight: 600;
  }
</style>
</head>
<body>

<a href="dashboard.php" class="back-btn">&larr; Back to Dashboard</a>

<h2>Attendance History</h2>
<table>
  <thead>
    <tr>
      <th>Date Recorded</th>
      <th>Total Days</th>
      <th>Present Days</th>
      <th>Attendance %</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars(date('d M Y', strtotime($row['date_recorded']))); ?></td>
        <td><?= htmlspecialchars($row['total_days']); ?></td>
        <td><?= htmlspecialchars($row['present_days']); ?></td>
        <td><?= htmlspecialchars($row['percentage']); ?>%</td>
      </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td class="no-data" colspan="4">No attendance records found.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

</body>
</html>

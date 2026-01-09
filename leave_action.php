<?php
session_start();
include '../includes/db_connection.php';
include '../includes/send_mail.php'; // Include mail function

if (!isset($_SESSION['hod_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_id = intval($_POST['leave_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $status = 'Approved';
        $stmt = $conn->prepare("UPDATE leaves SET status_hod = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $leave_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'reject') {
        $status = 'Rejected';
        $reason = trim($_POST['reject_reason']);

        $stmt = $conn->prepare("UPDATE leaves SET status_hod = ?, hod_reject_reason = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $reason, $leave_id);
        $stmt->execute();
        $stmt->close();
    } else {
        die('Invalid action');
    }

    // Send email to student
    $query = "SELECT students.email, students.name, leaves.status_hod, leaves.hod_reject_reason
              FROM leaves
              JOIN students ON leaves.student_id = students.student_id
              WHERE leaves.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $leave_id);
    $stmt->execute();
    $stmt->bind_result($student_email, $student_name, $hod_status, $hod_reason);
    $stmt->fetch();
    $stmt->close();

    if ($hod_status === 'Approved') {
        $subject = "Leave Approved by HoD";
        $body = "Dear $student_name,<br><br>Your leave request has been <b>approved</b> by the Head of Department.<br><br>Regards,<br>Leave Management System";
    } else {
        $subject = "Leave Rejected by HoD";
        $body = "Dear $student_name,<br><br>Your leave request has been <b>rejected</b> by the Head of Department.<br><br><b>Reason:</b> " . nl2br(htmlspecialchars($hod_reason)) . "<br><br>Regards,<br>Leave Management System";
    }

    sendEmail($student_email, $subject, $body);

    header("Location: dashboard.php");
    exit();
}
?>



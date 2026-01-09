<?php
session_start();
include '../includes/db_connection.php';
include '../includes/send_mail.php'; // ✅ PHPMailer

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_type = mysqli_real_escape_string($conn, $_POST['type']);
    $from_date = mysqli_real_escape_string($conn, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($conn, $_POST['to_date']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $parent_phone = mysqli_real_escape_string($conn, $_POST['parent_phone']);
    $self_phone = mysqli_real_escape_string($conn, $_POST['self_phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $attendance = (int)$_POST['attendance'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $document_path = null;
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['document']['tmp_name'];
        $file_name = basename($_FILES['document']['name']);
        $file_size = $_FILES['document']['size'];
        $file_type = mime_content_type($file_tmp);

        $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $new_file_name = uniqid('doc_', true) . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $target_path = $upload_dir . $new_file_name;
            if (move_uploaded_file($file_tmp, $target_path)) {
                $document_path = $new_file_name;
            }
        }
    }

    $stmt = $conn->prepare("INSERT INTO leaves 
        (student_id, leave_type, from_date, to_date, reason, document, status_faculty, status_hod, parent_phone, self_phone, address, attendance, email)
        VALUES (?, ?, ?, ?, ?, ?, 'Pending', 'Pending', ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssssis",
        $student_id, $leave_type, $from_date, $to_date, $reason, $document_path,
        $parent_phone, $self_phone, $address, $attendance, $email
    );

    if ($stmt->execute()) {
        // ✅ Email to faculty
        $facultyEmail = "mkhan@csmssengg.org";  // Update with real faculty email
        $subject = "New Leave Request Submitted";
        $body = "Dear Faculty,<br><br>
        A leave request has been submitted by Student ID: <strong>$student_id</strong><br>
        Leave Type: $leave_type<br>
        From: $from_date<br>
        To: $to_date<br>
        Reason: $reason<br>
        Contact (Parent): $parent_phone<br>
        Contact (Self): $self_phone<br>
        Address: $address<br><br>
        Please review the request at your earliest convenience.<br><br>Regards,<br>Leave System";

        sendEmail($facultyEmail, $subject, $body);

        $_SESSION['success'] = "Leave request submitted successfully.";
    } else {
        $_SESSION['error'] = "Database Error: " . $stmt->error;
    }

    $stmt->close();
    header("Location: apply_leave.php");
    exit();
}
?>
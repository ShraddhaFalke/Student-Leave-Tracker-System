<?php
session_start();
include '../includes/db_connection.php';

$student_id = trim($_POST['student_id']);
$password = $_POST['password'];

// Prepare the query
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['student_name'] = $row['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Incorrect password.'); window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('Student ID not found.'); window.location='login.php';</script>";
}
?>





<?php
include '../includes/db_connection.php';

$student_id    = $_POST['student_id'];
$name          = $_POST['name'];
$class         = $_POST['class'];
$student_phone = $_POST['student_phone'];
$parent_phone  = $_POST['parent_phone'];
$email         = $_POST['email'];
$address       = $_POST['address'];
$password      = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check for duplicates
$check_stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ? OR email = ?");
$check_stmt->bind_param("ss", $student_id, $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Student ID or Email already exists. Please try a different one.'); window.location='register.php';</script>";
    exit();
}
$check_stmt->close();

// Insert new student
$insert_stmt = $conn->prepare("INSERT INTO students (student_id, name, class, student_phone, parent_phone, email, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$insert_stmt->bind_param("ssssssss", $student_id,  $name, $class, $student_phone, $parent_phone, $email, $address, $hashed_password);

if ($insert_stmt->execute()) {
    echo "<script>alert('Registration Successful. You can now login.'); window.location='login.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>




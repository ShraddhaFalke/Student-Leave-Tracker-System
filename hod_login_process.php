<?php
session_start();
include '../includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check HOD login
    $sql = "SELECT * FROM hod WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $hod = $result->fetch_assoc();
        $_SESSION['hod_id'] = $hod['id'];
        $_SESSION['hod_name'] = $hod['name'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Username or Password'); window.location.href='login.php';</script>";
    }
}
?>

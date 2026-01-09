<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f3f0f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .leave-form-container {
            max-width: 700px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(102, 51, 153, 0.2);
            border-left: 6px solid #663399; /* Purple */
        }
        .leave-form-container h3 {
            color: #663399; /* Purple */
            margin-bottom: 25px;
            font-weight: 700;
            text-align: center;
        }
        label {
            font-weight: 600;
            color: #4b367c;
        }
        .btn-primary {
            background-color: #6f42c1;
            border-color: #6f42c1;
            font-weight: 600;
            padding: 10px 25px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #532d91;
            border-color: #532d91;
        }
        .btn-secondary {
            font-weight: 600;
            padding: 10px 25px;
            color: #6f42c1;
            border: 1px solid #6f42c1;
            background: transparent;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #6f42c1;
            color: white;
        }
        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 8px rgba(111, 66, 193, 0.3);
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="file"] {
            border: 1px solid #ced4da;
            padding: 6px 12px;
            border-radius: 4px;
            width: 100%;
        }
        /* Back button styling */
        .btn-back {
            display: inline-block;
            background-color: #6f42c1;
            color: white;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 30px;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #532d91;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 700px; margin: 40px auto;">
    <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>

    <div class="leave-form-container shadow-sm">
        <h3>Apply for Leave</h3>
        <form action="apply_leave_process.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Type of Leave</label>
                <select name="type" class="form-control" required>
                    <option value="">-- Select Leave Type --</option>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Casual Leave">Casual Leave</option>
                    <option value="Emergency Leave">Emergency Leave</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" required min="<?= date('Y-m-d'); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" required min="<?= date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Reason for Leave</label>
                <textarea name="reason" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Parent's Phone Number</label>
                    <input type="tel" name="parent_phone" class="form-control" pattern="[0-9]{10}" maxlength="10" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Student Phone Number</label>
                    <input type="tel" name="self_phone" class="form-control" pattern="[0-9]{10}" maxlength="10" required>
                </div>
            </div>

            <div class="form-group">
                <label>Address During Leave</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label>Attendance Given by Faculty (%)</label>
                <input type="number" name="attendance" class="form-control" min="0" max="100" required>
            </div>

            <div class="form-group">
                <label>Your Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Supporting Document (optional, PDF/JPG/PNG, Max 2MB)</label>
                <input type="file" name="document" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary mr-2">Submit Leave Application</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
